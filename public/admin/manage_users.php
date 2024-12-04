<?php
// public/admin/manage_users.php

session_start();
require_once '../../config/connect.php';
require_once '../../utils/helpers.php';

if (!is_logged_in() || $_SESSION['role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

$db = connect();

// Initialize message variables
$message = '';
$success_message = '';

// Define allowed roles for validation
$allowed_roles = ['User', 'Admin'];

// Handle user addition, editing, and deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        // Validate and insert new user
        $first_name = test_input($_POST['first_name']);
        $last_name = test_input($_POST['last_name']);
        $username = test_input($_POST['username']);
        $email = test_input($_POST['email']);
        $role = $_POST['role'];
        $password = $_POST['password'];

        // Validate required fields
        if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($role) || empty($password)) {
            $message = "All fields, including password, are required.";
        } elseif (!in_array($role, $allowed_roles)) {
            $message = "Invalid role selected.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
        } else {
            // Check for existing username or email
            $stmt = $db->prepare("SELECT COUNT(*) FROM Users WHERE Username = ? OR Email = ?");
            $stmt->execute([$username, $email]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $message = "Username or email already exists.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert into database
                $stmt = $db->prepare("INSERT INTO Users (FirstName, LastName, Username, Email, Password, Role) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $username, $email, $hashed_password, $role]);

                header('Location: manage_users.php?success=1');
                exit();
            }
        }
    }

    if ($action === 'edit') {
        // Validate and update user details
        if (isset($_POST['user_id'], $_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['email'], $_POST['role'])) {
            $user_id = intval($_POST['user_id']);
            $first_name = test_input($_POST['first_name']);
            $last_name = test_input($_POST['last_name']);
            $username = test_input($_POST['username']);
            $email = test_input($_POST['email']);
            $role = $_POST['role'];
            $password = $_POST['password']; // Optional

            // Validate required fields
            if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($role)) {
                $message = "All fields except password are required.";
            } elseif (!in_array($role, $allowed_roles)) {
                $message = "Invalid role selected.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Invalid email format.";
            } else {
                // Check for existing username or email excluding the current user
                $stmt = $db->prepare("SELECT COUNT(*) FROM Users WHERE (Username = ? OR Email = ?) AND UserID != ?");
                $stmt->execute([$username, $email, $user_id]);
                $exists = $stmt->fetchColumn();

                if ($exists) {
                    $message = "Another user with the same username or email already exists.";
                } else {
                    if (!empty($password)) {
                        // Hash the new password
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                        $stmt = $db->prepare("UPDATE Users SET FirstName = ?, LastName = ?, Username = ?, Email = ?, Password = ?, Role = ? WHERE UserID = ?");
                        $stmt->execute([$first_name, $last_name, $username, $email, $hashed_password, $role, $user_id]);
                    } else {
                        // Update without changing the password
                        $stmt = $db->prepare("UPDATE Users SET FirstName = ?, LastName = ?, Username = ?, Email = ?, Role = ? WHERE UserID = ?");
                        $stmt->execute([$first_name, $last_name, $username, $email, $role, $user_id]);
                    }

                    header('Location: manage_users.php?success=2');
                    exit();
                }
            }
        } else {
            $message = "All required fields must be provided for editing a user.";
        }
    }

    if ($action === 'delete') {
        if (isset($_POST['user_id'])) {
            $user_id = intval($_POST['user_id']);

            // Prevent admin from deleting themselves
            if ($user_id == $_SESSION['user_id']) {
                $message = "You cannot delete your own account.";
            } else {
                $stmt = $db->prepare("DELETE FROM Users WHERE UserID = ?");
                $stmt->execute([$user_id]);

                header('Location: manage_users.php?success=3');
                exit();
            }
        }
    }
}

// Handle Search, Pagination, and Sorting
$search = isset($_GET['search']) ? test_input($_GET['search']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

// Build WHERE clause for search
$where_clause = "WHERE 1=1";
$params = [];

if (!empty($search)) {
    $where_clause .= " AND (FirstName LIKE ? OR LastName LIKE ? OR Username LIKE ? OR Email LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
}

// Count total users for pagination
$stmt = $db->prepare("SELECT COUNT(*) FROM Users $where_clause");
$stmt->execute($params);
$total_users = $stmt->fetchColumn();
$total_pages = ceil($total_users / $items_per_page);

// Fetch users for current page
$sql = "SELECT UserID, FirstName, LastName, Username, Email, Role 
        FROM Users 
        $where_clause 
        ORDER BY Username ASC 
        LIMIT $items_per_page OFFSET $offset";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle success messages based on URL parameters
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1':
            $success_message = "User added successfully.";
            break;
        case '2':
            $success_message = "User updated successfully.";
            break;
        case '3':
            $success_message = "User deleted successfully.";
            break;
        default:
            $success_message = "";
    }
}

$page_title = 'Manage Users';
include '../../includes/header.php';
?>

<div class="container-fluid px-3">
    <div class="row">
        <?php include '../../includes/side_nav.php'; ?>

        <main class="col ms-sm-auto px-md-4">
            <h1 class="mt-4">Manage Users</h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-danger mt-3"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success mt-3"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <form class="d-flex w-75" method="GET" action="manage_users.php">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search users" value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
                <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddUserModal()">
                    <i class='fas fa-user-plus me-2'></i> Add New User
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover shadow">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']) ?></td>
                                    <td><?= htmlspecialchars($user['Username']) ?></td>
                                    <td><?= htmlspecialchars($user['Email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['Role'] === 'Admin' ? 'success' : 'info' ?>">
                                            <?= htmlspecialchars($user['Role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button 
                                                class="btn btn-sm btn-outline-primary" 
                                                onclick="editUser(this)"
                                                data-user-id="<?= htmlspecialchars($user['UserID']) ?>"
                                                data-first-name="<?= htmlspecialchars($user['FirstName']) ?>"
                                                data-last-name="<?= htmlspecialchars($user['LastName']) ?>"
                                                data-username="<?= htmlspecialchars($user['Username']) ?>"
                                                data-email="<?= htmlspecialchars($user['Email']) ?>"
                                                data-role="<?= htmlspecialchars($user['Role']) ?>"
                                            >
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?= $user['UserID'] ?>)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Modal for Adding/Editing Users -->
<?php include '../../includes/modals.php'; ?>
<?php include '../../includes/footer.php'; ?>
