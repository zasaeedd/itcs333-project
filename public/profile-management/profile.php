<?php
require_once '../../config/connect.php';

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['username'];

// Fetch user details from the database
$db = connect();
$stmt = $db->prepare("SELECT FirstName, LastName, Username, Email, ImagePath FROM Users WHERE Username = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
        $uploadDir = '../images/profile-pictures/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Update database with new image path
            $stmt = $db->prepare("UPDATE Users SET ImagePath = ? WHERE Username = ?");
            $relativePath = 'images/profile-pictures/' . $fileName;
            $stmt->execute([$relativePath, $user_id]);
            
            // Update the user array to show new image immediately
            $user['ImagePath'] = $relativePath;
            
            header("Location: profile.php?success=profile_updated");
            exit();
        }
    }
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

        <!-- Profile Picture Section -->
        <div class="profile-picture-container">
            <img src="../<?= htmlspecialchars($user['ImagePath']) ?>" alt="Profile Picture" class="profile-picture">
            
            <!-- Separate form for profile picture upload -->
            <form action="profile.php" method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="file-input">
                <button type="submit" class="upload-btn">Update Picture</button>
            </form>
        </div>

        <!-- User Details Form -->
        <form action="update_profile.php" method="POST" class="details-form">
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

        <!-- Add this right after the Update Profile button -->
        <div class="text-end mt-3">
            <a href="profile_page.php" class="btn btn-outline-primary">
                <img src="../images/bxs-exit.svg" alt="Profile" class="profile-back-icon">
                Back to Profile
            </a>
        </div>
    </div>

    <style>
        .profile-picture-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .upload-form {
            margin: 10px 0;
        }
        .file-input {
            margin-bottom: 10px;
        }
        .upload-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .upload-btn:hover {
            background-color: #45a049;
        }
    </style>
</body>
</html>