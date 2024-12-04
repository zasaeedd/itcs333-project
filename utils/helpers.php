<?php
// utils/helpers.php

/**
 * Sanitize and validate input data
 */
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['username']);
}

/**
 * Check if user is Admin
 */
function is_admin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin');
}

/**
 * Ensure user is logged in
 */
function ensure_logged_in() {
    if (!is_logged_in()) {
        header('Location: /auth/login.php');
        exit();
    }
}

/**
 * Get User ID by Username
 */
function get_user_id($username, $pdo) {
    $stmt = $pdo->prepare("SELECT UserID FROM Users WHERE Username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['UserID'] ?? null;
}
?>
