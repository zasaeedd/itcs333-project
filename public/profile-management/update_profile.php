<?php
require_once 'connect.php'; // Database connection

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['Username']; // Get the logged-in user's ID

// Handle POST request to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];


        $db = connect();

        // Update user details in the database
        $stmt = $db->prepare("
            UPDATE Users
            SET FirstName = :first_name,
                LastName = :last_name,
                Username = :username,
                Email = :email
            WHERE Username = :Username
        ");
        $stmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'email' => $email,
        ]);

        // Redirect back to profile page with success message
        header("Location: profile.php?success=1");
        exit();
    }
?>