<?php
// public/analytics/analytics.php

session_start();
require_once '../../config/connect.php';
require_once '../../utils/helpers.php';

if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$db = connect();

try {
    // Fetch key analytics data
    $total_bookings = $db->query("SELECT COUNT(*) AS total_bookings FROM Bookings")->fetchColumn();
    $total_users = $db->query("SELECT COUNT(*) AS total_users FROM Users WHERE Role != 'Admin'")->fetchColumn();
    $available_rooms = $db->query("SELECT COUNT(*) AS available_rooms FROM Rooms WHERE Status = 'Available'")->fetchColumn();
    $completed_bookings = $db->query("SELECT COUNT(*) AS completed FROM Bookings WHERE Status = 'Completed'")->fetchColumn();
    $pending_bookings = $db->query("SELECT COUNT(*) AS pending FROM Bookings WHERE Status = 'Pending'")->fetchColumn();
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Get the current timestamp for "Last Updated"
$last_updated = date("Y-m-d H:i:s");

$page_title = 'Analytics Dashboard';
include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/side_nav.php'; ?>

        <main class="col ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h1>Analytics Dashboard</h1>
                <form method="POST">
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </form>
            </div>
            <p class="last-updated">Last Updated: <?= htmlspecialchars($last_updated) ?></p>

            <div class="row mt-4">
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-primary shadow">
                        <div class="card-body">
                            <h5 class="card-title">Total Bookings</h5>
                            <p class="card-text display-6"><?= htmlspecialchars($total_bookings) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-success shadow">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text display-6"><?= htmlspecialchars($total_users) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-info shadow">
                        <div class="card-body">
                            <h5 class="card-title">Available Rooms</h5>
                            <p class="card-text display-6"><?= htmlspecialchars($available_rooms) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="mt-5">Pending and Completed Bookings</h2>
            <div class="table-responsive mt-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pending</td>
                            <td><?= htmlspecialchars($pending_bookings) ?></td>
                        </tr>
                        <tr>
                            <td>Completed</td>
                            <td><?= htmlspecialchars($completed_bookings) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2 class="mt-5">Top Active Users</h2>
            <div class="table-responsive mt-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Total Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $db->query("
                            SELECT Users.Username, COUNT(Bookings.BookingID) AS booking_count
                            FROM Users
                            JOIN Bookings ON Users.UserID = Bookings.BookedBy
                            GROUP BY Users.Username
                            ORDER BY booking_count DESC
                            LIMIT 5
                        ");
                        $top_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($top_users):
                            foreach ($top_users as $user):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($user['Username']) ?></td>
                                <td><?= htmlspecialchars($user['booking_count']) ?></td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="2" class="text-center">No data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
