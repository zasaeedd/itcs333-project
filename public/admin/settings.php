<?php
// public/admin/settings.php

session_start();
require_once '../../config/connect.php';
require_once '../../utils/helpers.php';
require_once '../../utils/settings.php';

// Redirect if not logged in or not an Admin
if (!is_logged_in() || $_SESSION['role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

$db = connect();

// Initialize message variable
$message = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $site_name = test_input($_POST['site_name']);
    $admin_email = test_input($_POST['admin_email']);
    $default_booking_duration = test_input($_POST['default_booking_duration']);
    $max_booking_limit = intval($_POST['max_booking_limit']);
    $enable_notifications = isset($_POST['enable_notifications']) ? 'Yes' : 'No';

    // Basic validation (you can expand this as needed)
    if (empty($site_name) || empty($admin_email) || empty($default_booking_duration) || empty($max_booking_limit)) {
        $message = '<div class="alert alert-danger mt-3">All fields except notifications are required.</div>';
    } elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger mt-3">Please enter a valid email address.</div>';
    } else {
        // Update settings in the database using helper function
        update_setting('SiteName', $site_name, $db);
        update_setting('AdminEmail', $admin_email, $db);
        update_setting('DefaultBookingDuration', $default_booking_duration, $db);
        update_setting('MaxBookingLimit', $max_booking_limit, $db);
        update_setting('EnableNotifications', $enable_notifications, $db);

        $message = '<div class="alert alert-success mt-3">Settings updated successfully!</div>';
    }
}

// Fetch current settings
$site_name = get_setting('SiteName', $db) ?? 'Room Booking System';
$admin_email = get_setting('AdminEmail', $db) ?? 'admin@example.com';
$default_booking_duration = get_setting('DefaultBookingDuration', $db) ?? '2';
$max_booking_limit = get_setting('MaxBookingLimit', $db) ?? '5';
$enable_notifications = get_setting('EnableNotifications', $db) ?? 'Yes';

$page_title = 'Settings';
include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/side_nav.php'; ?>

        <main class="col ms-sm-auto px-md-4">
            <h1 class="mt-4">Settings</h1>

            <?php 
            // Display message if set
            if (!empty($message)): 
                echo $message;
            endif; 
            ?>

            <div class="form-container mt-4 shadow">
                <form method="POST" action="settings.php">
                    <div class="mb-4">
                        <label for="site_name" class="form-label fw-bold">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="<?= htmlspecialchars($site_name) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="admin_email" class="form-label fw-bold">Admin Email</label>
                        <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?= htmlspecialchars($admin_email) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="default_booking_duration" class="form-label fw-bold">Default Booking Duration (hours)</label>
                        <input type="number" class="form-control" id="default_booking_duration" name="default_booking_duration" value="<?= htmlspecialchars($default_booking_duration) ?>" min="1" required>
                    </div>
                    <div class="mb-4">
                        <label for="max_booking_limit" class="form-label fw-bold">Max Active Bookings Per User</label>
                        <input type="number" class="form-control" id="max_booking_limit" name="max_booking_limit" value="<?= htmlspecialchars($max_booking_limit) ?>" min="1" required>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="Yes" id="enable_notifications" name="enable_notifications" <?= $enable_notifications === 'Yes' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="enable_notifications">
                            Enable Email Notifications
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
