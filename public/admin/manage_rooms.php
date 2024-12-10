<?php
// public/admin/manage_rooms.php

session_start();
require_once '../../config/connect.php';
require_once '../../utils/helpers.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$db = connect();
$message = '';
$success_message = '';

// Define allowed departments for validation
$allowed_departments = ['IS', 'CS', 'CE'];

// Handle Room Add, Edit, and Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $room_no = test_input($_POST['room_no']);
        $room_type = test_input($_POST['room_type']);
        $capacity = intval($_POST['capacity']);
        $status = test_input($_POST['status']);
        $department = test_input($_POST['department']);

        if (empty($room_no) || empty($room_type) || empty($status) || empty($department) || $capacity < 1) {
            $message = "All fields are required.";
        } elseif (!in_array($department, $allowed_departments)) {
            $message = "Invalid department selected.";
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM Rooms WHERE RoomNo = ?");
            $stmt->execute([$room_no]);
            if ($stmt->fetchColumn() > 0) {
                $message = "Room number already exists.";
            } else {
                $stmt = $db->prepare("INSERT INTO Rooms (RoomNo, RoomType, Capacity, Status, Department) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$room_no, $room_type, $capacity, $status, $department]);
                $success_message = "Room added successfully.";
                header('Location: manage_rooms.php?success=1');
                exit();
            }
        }
    }

    if ($action === 'edit') {
        $room_id = intval($_POST['room_id']);
        $room_no = test_input($_POST['room_no']);
        $room_type = test_input($_POST['room_type']);
        $capacity = intval($_POST['capacity']);
        $status = test_input($_POST['status']);
        $department = test_input($_POST['department']);

        if (empty($room_no) || empty($room_type) || empty($status) || empty($department) || $capacity < 1) {
            $message = "All fields are required.";
        } elseif (!in_array($department, $allowed_departments)) {
            $message = "Invalid department selected.";
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM Rooms WHERE RoomNo = ? AND RoomID != ?");
            $stmt->execute([$room_no, $room_id]);
            if ($stmt->fetchColumn() > 0) {
                $message = "Another room with the same number already exists.";
            } else {
                $stmt = $db->prepare("UPDATE Rooms SET RoomNo = ?, RoomType = ?, Capacity = ?, Status = ?, Department = ? WHERE RoomID = ?");
                $stmt->execute([$room_no, $room_type, $capacity, $status, $department, $room_id]);
                $success_message = "Room updated successfully.";
                header('Location: manage_rooms.php?success=2');
                exit();
            }
        }
    }

    if ($action === 'delete') {
        $room_id = intval($_POST['room_id']);
        $stmt = $db->prepare("DELETE FROM Rooms WHERE RoomID = ?");
        $stmt->execute([$room_id]);
        $success_message = "Room deleted successfully.";
        header('Location: manage_rooms.php?success=3');
        exit();
    }
}

// Handle Search, Filter, and Pagination
$search = isset($_GET['search']) ? test_input($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? test_input($_GET['status']) : '';
$department_filter = isset($_GET['department']) ? test_input($_GET['department']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

$where_clause = "WHERE 1=1";
$params = [];

if (!empty($search)) {
    $where_clause .= " AND (RoomNo LIKE ? OR RoomType LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if (!empty($status_filter)) {
    $where_clause .= " AND Status = ?";
    $params[] = $status_filter;
}
if (!empty($department_filter)) {
    $where_clause .= " AND Department = ?";
    $params[] = $department_filter;
}

// Count Total Rooms for Pagination
$stmt = $db->prepare("SELECT COUNT(*) FROM Rooms $where_clause");
$stmt->execute($params);
$total_items = $stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Fetch Rooms for Current Page
$sql = "SELECT RoomID, RoomNo, RoomType, Capacity, Status, Department 
        FROM Rooms 
        $where_clause 
        ORDER BY RoomNo ASC 
        LIMIT $items_per_page OFFSET $offset";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Manage Rooms';
include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../../includes/side_nav.php'; ?>

        <main class="col ms-sm-auto px-md-4">
            <h1 class="mt-4">Manage Rooms</h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <form class="d-flex w-75" method="GET" action="manage_rooms.php">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search by Room No or Type" value="<?= htmlspecialchars($search) ?>">
                    <select class="form-select me-2" name="status">
                        <option value="">All Statuses</option>
                        <option value="Available" <?= $status_filter === 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Occupied" <?= $status_filter === 'Occupied' ? 'selected' : '' ?>>Occupied</option>
                        <option value="Maintenance" <?= $status_filter === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                    <select class="form-select me-2" name="department">
                        <option value="">All Departments</option>
                        <option value="IS" <?= $department_filter === 'IS' ? 'selected' : '' ?>>IS</option>
                        <option value="CS" <?= $department_filter === 'CS' ? 'selected' : '' ?>>CS</option>
                        <option value="CE" <?= $department_filter === 'CE' ? 'selected' : '' ?>>CE</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Filter</button>
                </form>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#roomModal" onclick="openAddRoomModal()">
                    <i class='fas fa-plus me-2'></i> Add New Room
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover shadow">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Room No</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rooms): ?>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?= htmlspecialchars($room['RoomNo']) ?></td>
                                    <td><?= htmlspecialchars($room['RoomType']) ?></td>
                                    <td><?= htmlspecialchars($room['Capacity']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $room['Status'] === 'Available' ? 'success' : ($room['Status'] === 'Occupied' ? 'danger' : 'warning') ?>">
                                            <?= htmlspecialchars($room['Status']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($room['Department']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick='openEditRoomModal(<?= json_encode($room) ?>)'>
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method="POST" class="d-inline-block" action="manage_rooms.php" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="room_id" value="<?= $room['RoomID'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No rooms found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&department=<?= urlencode($department_filter) ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&department=<?= urlencode($department_filter) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&department=<?= urlencode($department_filter) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </main>
    </div>
</div>

<?php include '../../includes/modals.php'; ?>
<?php include '../../includes/footer.php'; ?>
