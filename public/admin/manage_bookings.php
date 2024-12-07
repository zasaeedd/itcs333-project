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

// Handle booking updates, deletions, and bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Single Actions: Edit or Delete
    if (isset($_POST['action'])) {
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

    // Bulk Actions
    if (isset($_POST['bulk_action']) && isset($_POST['selected_bookings'])) {
        $bulk_action = $_POST['bulk_action'];
        $selected_bookings = array_map('intval', $_POST['selected_bookings']); // Sanitize input

        if (empty($selected_bookings)) {
            // No bookings selected
            header('Location: manage_bookings.php?error=No bookings selected');
            exit();
        }

        if ($bulk_action === 'bulk_delete') {
            // Bulk Delete
            $placeholders = implode(',', array_fill(0, count($selected_bookings), '?'));
            $stmt = $db->prepare("DELETE FROM Bookings WHERE BookingID IN ($placeholders)");
            $stmt->execute($selected_bookings);

            header('Location: manage_bookings.php');
            exit();
        }

        if ($bulk_action === 'bulk_confirm' || $bulk_action === 'bulk_cancel' || $bulk_action === 'bulk_complete') {
            // Determine new status based on action
            $new_status = '';
            switch ($bulk_action) {
                case 'bulk_confirm':
                    $new_status = 'Confirmed';
                    break;
                case 'bulk_cancel':
                    $new_status = 'Cancelled';
                    break;
                case 'bulk_complete':
                    $new_status = 'Completed';
                    break;
            }

            if ($new_status) {
                $placeholders = implode(',', array_fill(0, count($selected_bookings), '?'));
                $stmt = $db->prepare("UPDATE Bookings SET Status = ? WHERE BookingID IN ($placeholders)");
                $params = array_merge([$new_status], $selected_bookings);
                $stmt->execute($params);

                header('Location: manage_bookings.php');
                exit();
            }
        }

        // If action not recognized
        header('Location: manage_bookings.php?error=Invalid action');
        exit();
    }
}

// Handle Search Filter
$search_keyword = '';
$where_clauses = [];
$search_params = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_keyword = trim($_GET['search']);
    $where_clauses[] = '(Users.Username LIKE ? OR Rooms.RoomNo LIKE ? OR Bookings.Status LIKE ?)';
    $search_params[] = '%' . $search_keyword . '%';
    $search_params[] = '%' . $search_keyword . '%';
    $search_params[] = '%' . $search_keyword . '%';
}

// Build the SQL query with search filter
$sql = "
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
";

if (!empty($where_clauses)) {
    $sql .= ' WHERE ' . implode(' AND ', $where_clauses);
}

$sql .= ' ORDER BY Bookings.StartTime DESC';

$stmt = $db->prepare($sql);
$stmt->execute($search_params);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Manage Bookings';
include '../../includes/header.php';
?>

<style>
    /* Add some custom styles for the status badges */
    .status-badge {
        padding: 0.3em 0.6em;
        border-radius: 0.25rem;
        color: #fff;
        font-size: 0.9em;
    }
    .confirmed { background-color: #28a745; }
    .pending { background-color: #ffc107; }
    .cancelled { background-color: #dc3545; }
    .completed { background-color: #17a2b8; }
</style>

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/side_nav.php'; ?>

        <main class="col ms-sm-auto px-md-4">
            <h1 class="mt-4">Manage Bookings</h1>

            <!-- Search Form -->
            <form method="GET" class="mt-4 mb-4 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by Username, Room No, or Status" value="<?= htmlspecialchars($search_keyword) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="manage_bookings.php" class="btn btn-secondary ms-2">Reset</a>
            </form>

            <!-- Bulk Actions Form -->
            <form method="POST" id="bulkActionForm">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <select name="bulk_action" class="form-select d-inline-block w-auto" required>
                            <option value="" disabled selected>-- Bulk Actions --</option>
                            <option value="bulk_delete">Delete Selected</option>
                            <option value="bulk_confirm">Set as Confirmed</option>
                            <option value="bulk_cancel">Set as Cancelled</option>
                            <option value="bulk_complete">Set as Completed</option>
                        </select>
                        <button type="submit" class="btn btn-outline-primary ms-2">Apply</button>
                    </div>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-hover shadow">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
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
                                        <td>
                                            <input type="checkbox" name="selected_bookings[]" value="<?= $booking['BookingID'] ?>">
                                        </td>
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
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick='openEditBookingModal(<?= json_encode($booking) ?>)'>
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBooking(<?= $booking['BookingID'] ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No bookings found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </main>
    </div>
</div>

<!-- Modal for Editing Bookings -->
<?php include '../../includes/modals.php'; ?>
<?php include '../../includes/footer.php'; ?>
