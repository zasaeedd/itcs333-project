<?php
require_once '../config/connect.php'; // Database connection

// Start session
session_start();

// Check if the user is logged in (replace with your actual authentication logic)
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['Username']; // Get the logged-in user's ID

// Fetch user details from the database
    $db = connect();
    $stmt = $db->prepare("SELECT FirstName, LastName, Username, Email FROM Users WHERE Username = :username");
    $stmt->execute(['Username' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/editprofile.css">
</head>
<body>
    <div class="profile-container">
        <h1>User Profile</h1>

        <!-- Display User Details -->
        <div class="profile-picture-container">
            <img src="default-profile.png" alt="Profile Picture" class="profile-picture">
        </div>

        <form action="update_profile.php" method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['FirstName']) ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['LastName']) ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['Username']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>