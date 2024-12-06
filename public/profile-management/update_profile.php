<?php
require_once '../../config/connect.php'; // Database connection

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['username']; // Get the logged-in user's ID

// Handle POST request to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        // Redirect with an error if the email is invalid
        header("Location: profile.php?error=invalid_email");
        exit();
    }

    try {
        $db = connect();

        // Update user details in the database
        $stmt = $db->prepare("
            UPDATE Users
            SET FirstName = :first_name,
                LastName = :last_name,
                Username = :new_username,
                Email = :email
            WHERE Username = :current_username
        ");

        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':new_username' => $username,
            ':email' => $email,
            ':current_username' => $user_id,
        ]);

        // Update session username if changed
        if ($user_id !== $username) {
            $_SESSION['username'] = $username;
        }

        // Redirect back to profile page with success message
        header("Location: profile.php?success=1");
        exit();
    } catch (PDOException $e) {
        // Log error and redirect with failure message
        error_log("Database Error: " . $e->getMessage());
        header("Location: profile.php?error=db_error");
        exit();
    }
}
?>
