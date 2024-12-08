<?php
require_once '../../config/connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch user details
$db = connect();
$stmt = $db->prepare("SELECT UserID, FirstName, LastName, Username, Email, ProfileImage, ImageType FROM Users WHERE Username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Create data URL for the image
$imageSource = $user['ProfileImage'] ? 
    "data:" . $user['ImageType'] . ";base64," . base64_encode($user['ProfileImage']) : 
    "../images/default-profile.jpg";

// Fetch user's bookings
$bookingStmt = $db->prepare("
    SELECT b.*, r.RoomNo, r.Department 
    FROM Bookings b 
    JOIN Rooms r ON b.RoomID = r.RoomID 
    WHERE b.BookedBy = ? 
    AND b.Status IN ('Pending', 'Confirmed')
    ORDER BY b.StartTime ASC
");
$bookingStmt->execute([$user['UserID']]);
$bookings = $bookingStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/roomstyle.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand">IT College Room Booking</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="navbar-nav align-items-center">
                    <a href="../room-browsing/room_browse.php" class="btn btn-outline-primary me-2">Home</a>
                    <a href="profile_page.php" class="nav-link me-2">
                        <img src="<?= htmlspecialchars($imageSource) ?>" 
                             alt="Profile" 
                             class="rounded-circle profile-icon"
                             style="width: 32px; height: 32px; object-fit: cover;">
                    </a>
                    <a href="../logout.php" class="nav-link">
                        <img src="../images/bxs-exit.svg" 
                             alt="Logout" 
                             class="logout-icon"
                             style="width: 24px; height: 24px;">
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <h2 class="text-primary mb-4">Profile</h2>
                <hr class="mb-4">
            </div>
        </div>

        <!-- Profile Information -->
        <div class="row mb-5">
            <div class="col-md-4 text-center">
                <img src="<?= htmlspecialchars($imageSource) ?>" 
                     alt="Profile Picture" 
                     class="rounded-circle mb-3"
                     style="width: 200px; height: 200px; object-fit: cover;">
                <div class="mt-3">
                    <a href="profile.php" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">User Information</h3>
                        <p><strong>Name:</strong> <?= htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']) ?></p>
                        <p><strong>Username:</strong> <?= htmlspecialchars($user['Username']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user['Email']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="row">
            <div class="col">
                <h3 class="text-primary mb-4">Your Bookings</h3>
                <?php if ($bookings): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Room</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): 
                                    $startDateTime = new DateTime($booking['StartTime']);
                                    $endDateTime = new DateTime($booking['EndTime']);
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($booking['RoomNo']) ?></td>
                                        <td><?= htmlspecialchars($booking['Department']) ?></td>
                                        <td><?= $startDateTime->format('d/m/Y') ?></td>
                                        <td><?= $startDateTime->format('H:i') ?></td>
                                        <td><?= $endDateTime->format('H:i') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $booking['Status'] === 'Confirmed' ? 'success' : 'warning' ?>">
                                                <?= htmlspecialchars($booking['Status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form action="../room-browsing/book_room.php" method="POST" style="display: inline;">
                                                <input type="hidden" name="booking_id" value="<?= $booking['BookingID'] ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="btn btn-link p-0" 
                                                        onclick="return confirm('Are you sure you want to delete this booking?');">
                                                    <img src="../images/bxs-trash.svg" 
                                                         alt="Delete" 
                                                         class="delete-icon"
                                                         style="width: 20px; height: 20px;">
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        You haven't booked any rooms yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
