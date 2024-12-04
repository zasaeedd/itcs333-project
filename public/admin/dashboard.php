<?php
// public/admin/dashboard.php

session_start();
require_once '../../config/connect.php';
require_once '../../utils/helpers.php';

// Redirect to login if not logged in or not an admin
if (!is_logged_in() || $_SESSION['role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

// Connect to the database
$db = connect();

// Fetch data for dashboard cards
$total_bookings = $db->query("SELECT COUNT(*) FROM Bookings")->fetchColumn();
$active_users = $db->query("SELECT COUNT(*) FROM Users WHERE Role != 'Admin'")->fetchColumn();
$available_rooms = $db->query("SELECT COUNT(*) FROM Rooms WHERE Status = 'Available'")->fetchColumn();

// Fetch recent bookings
$stmt = $db->prepare("
    SELECT 
        Users.Username, 
        Rooms.RoomNo, 
        DATE(Bookings.StartTime) AS BookingDate, 
        TIME(Bookings.StartTime) AS StartTime, 
        TIME(Bookings.EndTime) AS EndTime, 
        Bookings.Status 
    FROM Bookings
    JOIN Users ON Bookings.BookedBy = Users.UserID 
    JOIN Rooms ON Bookings.RoomID = Rooms.RoomID 
    ORDER BY Bookings.StartTime DESC 
    LIMIT 5
");
$stmt->execute();
$recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Dashboard';
include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Include Side Navigation -->
        <?php include '../../includes/side_nav.php'; ?>

        <!-- Main Content -->
        <main>
            <!-- Dashboard Header -->
            <h1 class="mt-4">Dashboard</h1>

            <!-- Dashboard Cards -->
            <div class="row mt-4">
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Bookings</h5>
                            <p class="card-text display-6"><?= htmlspecialchars($total_bookings) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Active Users</h5>
                            <p class="card-text display-6"><?= htmlspecialchars($active_users) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Available Rooms</h5>
                            <p class="card-text display-6"><?= htmlspecialchars($available_rooms) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings Section -->
            <div class="table-responsive mt-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_bookings): ?>
                            <?php foreach ($recent_bookings as $booking): ?>
                                <tr>
                                    <td><?= htmlspecialchars($booking['Username']) ?></td>
                                    <td><?= htmlspecialchars($booking['RoomNo']) ?></td>
                                    <td><?= htmlspecialchars($booking['BookingDate']) ?></td>
                                    <td><?= htmlspecialchars($booking['StartTime']) ?> - <?= htmlspecialchars($booking['EndTime']) ?></td>
                                    <td>
                                        <?php
                                        // Assign status and determine badge class
                                        $status = trim(htmlspecialchars($booking['Status']));
                                        $badge_class = 'unknown';
                                        $icon = '<i class="fas fa-question-circle"></i>'; // Default icon

                                        if (strcasecmp($status, 'Pending') === 0) {
                                            $badge_class = 'pending';
                                            $icon = '<i class="fas fa-clock"></i>';
                                        } elseif (strcasecmp($status, 'Confirmed') === 0) {
                                            $badge_class = 'confirmed';
                                            $icon = '<i class="fas fa-check-circle"></i>';
                                        } elseif (strcasecmp($status, 'Cancelled') === 0) {
                                            $badge_class = 'cancelled';
                                            $icon = '<i class="fas fa-times-circle"></i>';
                                        }elseif (strcasecmp($status, 'Completed') === 0) {
                                            $badge_class = 'completed';
                                            $icon = '<i class="fas fa-check-double"></i>'; // Choose an appropriate icon
                                        }
                                        ?>
                                        <span class="status-badge <?= $badge_class ?>"><?= $icon ?> <?= $status ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No recent bookings.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
