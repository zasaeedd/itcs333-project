<?php
// public/admin/manage_bookings.php

session_start();
require_once '../../config/connect.php';
require_once '../../utils/helpers.php';

if (!is_logged_in() || $_SESSION['role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

$db = connect();

// Handle booking updates and deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'edit' && isset($_POST['booking_id'], $_POST['status'])) {
        $booking_id = intval($_POST['booking_id']);
        $status = $_POST['status'];

        $stmt = $db->prepare("UPDATE Bookings SET Status = ? WHERE BookingID = ?");
        $stmt->execute([$status, $booking_id]);

        header('Location: manage_bookings.php');
        exit();
    }

    if ($action === 'delete' && isset($_POST['booking_id'])) {
        $booking_id = intval($_POST['booking_id']);

        $stmt = $db->prepare("DELETE FROM Bookings WHERE BookingID = ?");
        $stmt->execute([$booking_id]);

        header('Location: manage_bookings.php');
        exit();
    }
}

// Fetch all bookings
$stmt = $db->prepare("
    SELECT 
        Bookings.BookingID,
        Users.Username,
        Rooms.RoomNo,
        Bookings.StartTime,
        Bookings.EndTime,
        Bookings.Status
    FROM Bookings
    JOIN Users ON Bookings.BookedBy = Users.UserID
    JOIN Rooms ON Bookings.RoomID = Rooms.RoomID
    ORDER BY Bookings.StartTime DESC
");
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Manage Bookings';
include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/side_nav.php'; ?>

        <main class="col ms-sm-auto px-md-4">
            <h1 class="mt-4">Manage Bookings</h1>

            <div class="table-responsive mt-4">
                <table class="table table-hover shadow">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>User</th>
                            <th>Room</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bookings): ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?= htmlspecialchars($booking['Username']) ?></td>
                                    <td><?= htmlspecialchars($booking['RoomNo']) ?></td>
                                    <td><?= htmlspecialchars($booking['StartTime']) ?></td>
                                    <td><?= htmlspecialchars($booking['EndTime']) ?></td>
                                    <td>
                                        <?php
                                        $status = htmlspecialchars($booking['Status']);
                                        $badge_class = '';
                                        if ($status === 'Confirmed') {
                                            $badge_class = 'status-badge confirmed';
                                        } elseif ($status === 'Pending') {
                                            $badge_class = 'status-badge pending';
                                        } elseif ($status === 'Cancelled') {
                                            $badge_class = 'status-badge cancelled';
                                        } elseif ($status === 'Completed') {
                                            $badge_class = 'status-badge completed';
                                        }
                                        ?>
                                        <span class="<?= $badge_class ?>"><?= $status ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick='openEditBookingModal(<?= json_encode($booking) ?>)'>
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteBooking(<?= $booking['BookingID'] ?>)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No bookings found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Modal for Editing Bookings -->
<?php include '../../includes/modals.php'; ?>
<?php include '../../includes/footer.php'; ?>
