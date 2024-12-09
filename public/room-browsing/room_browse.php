<?php
include 'fetch_data.php';
session_start();

// Sanitize and validate search query
function sanitizeAndValidateInput($input) {
    // Trim any unnecessary whitespace
    $input = trim($input);

    // Ensure the input is not empty and doesn't contain spaces (extra checking here)
    if (empty($input) || strpos($input, ' ') !== false) {
        return false; // Invalid input (empty or contains space)
    }

    // Validate the input (only numeric values allowed)
    if (preg_match('/^[0-9]+$/', $input)) {
        return $input;
    }

    return false; // Invalid room number (non-numeric characters)
}

// Search functionality based on user input
$searchedRoom = '';
$roomsFound = [];
$errorMessage = '';

if (isset($_GET['query'])) {
    $query = sanitizeAndValidateInput($_GET['query']);

    // If the input is valid, search for the room
    if ($query) {
        $db = connect();  // Use the connect() function from config/connect.php
        $stmt = $db->prepare("SELECT RoomNo, Capacity FROM Rooms WHERE RoomNo = :RoomNo");
        $stmt->bindParam(':RoomNo', $query, PDO::PARAM_STR);
        $stmt->execute();
        $roomsFound = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $searchedRoom = $query;

        // If no rooms are found, display error message
        if (empty($roomsFound)) {
            $errorMessage = "Invalid Room";
        }
    } else {
        // Invalid format or empty input
        $errorMessage = "Invalid Room: Input should be a number";
    }
}

// Fetch rooms for each department
$isRooms = fetchRoomsByDepartment('IS');
$csRooms = fetchRoomsByDepartment('CS');
$ceRooms = fetchRoomsByDepartment('CE');

// Room Image path
$imagePath = '../images/room.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/roomstyle.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand text-primary">IT College Room Booking</a>
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> -->
            <!-- <div class="collapse navbar-collapse justify-content-end" id="navbarNav"> -->

            <div class="nav-buttons">
                    <a href="room_browse.php" class="btn home-btn me-2">Home</a>
                    <?php if (isset($_SESSION['username'])): 
                        // Fetch user's profile image
                        $stmt = $db->prepare("SELECT ProfileImage, ImageType FROM Users WHERE Username = ?");
                        $stmt->execute([$_SESSION['username']]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        // Create data URL for the image
                        $imageSource = $user['ProfileImage'] ? 
                            "data:" . $user['ImageType'] . ";base64," . base64_encode($user['ProfileImage']) : 
                            "../images/default-profile.jpg";
                    ?>
                        <a href="../profile-management/profile_page.php" class="btn nav-btn me-2">
                            <img src="<?= htmlspecialchars($imageSource) ?>" 
                                 alt="Profile" 
                                 class="rounded-circle profile-icon"
                                 style="width: 32px; height: 32px; object-fit: cover;">
                        </a>
                        <a href="../logout.php" class="btn nav-btn">
                            <img src="../images/bxs-exit.svg" 
                                 alt="Logout" 
                                 class="logout-icon"
                                 style="width: 24px; height: 24px;">
                        </a>
                    <?php else: ?>
                        <a href="../login.php" class="btn nav-btn">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Search Form -->
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <form action="room_browse.php" method="GET" class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Enter Room Number..." required>
                    <button type="submit" class="btn btn-success">Search</button>
                </form>
            </div>
        </div>

        <!-- Display error message or search results -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php elseif (!empty($roomsFound)): ?>
            <div class="my-4">
                <h3>Search Results for Room: <?= htmlspecialchars($searchedRoom) ?></h3>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($roomsFound as $room): ?>
                        <div class="col">
                            <div class="room-card">
                            <a href="room_details.php?roomNo=<?= htmlspecialchars($room['RoomNo']) ?>" class="text-decoration-none">
                                <img src="<?= htmlspecialchars('../images/room.jpg') ?>" alt="Room Image">
                                <div class="room-info">
                                    <p>Room: <?= htmlspecialchars($room['RoomNo']) ?></p>
                                    <p>Capacity: <?= htmlspecialchars($room['Capacity']) ?></p>
                                </div>
                                <div class="room-overlay"><?= htmlspecialchars($room['RoomNo']) ?></div>
                            </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Department: IS -->
        <div class="department-section my-5">
            <h2 class="department-heading">IS Department</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($isRooms as $room): ?>
                    <div class="col">
                        <div class="room-card">
                        <a href="room_details.php?roomNo=<?= htmlspecialchars($room['RoomNo']) ?>" class="text-decoration-none">
                            <img src="<?= htmlspecialchars('../images/room.jpg') ?>" alt="Room Image">
                            <div class="room-info">
                                <p>Room: <?= htmlspecialchars($room['RoomNo']) ?></p>
                                <p>Capacity: <?= htmlspecialchars($room['Capacity']) ?></p>
                            </div>
                            <div class="room-overlay"><?= htmlspecialchars($room['RoomNo']) ?></div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Department: CS -->
        <div class="department-section my-5">
            <h2 class="department-heading">CS Department</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($csRooms as $room): ?>
                    <div class="col">
                        <div class="room-card">
                        <a href="room_details.php?roomNo=<?= htmlspecialchars($room['RoomNo']) ?>" class="text-decoration-none">
                            <img src="<?= htmlspecialchars('../images/room.jpg') ?>" alt="Room Image">
                            <div class="room-info">
                                <p>Room: <?= htmlspecialchars($room['RoomNo']) ?></p>
                                <p>Capacity: <?= htmlspecialchars($room['Capacity']) ?></p>
                            </div>
                            <div class="room-overlay"><?= htmlspecialchars($room['RoomNo']) ?></div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Department: CE -->
        <div class="department-section my-5">
            <h2 class="department-heading">CE Department</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($ceRooms as $room): ?>
                    <div class="col">
                        <div class="room-card">
                        <a href="room_details.php?roomNo=<?= htmlspecialchars($room['RoomNo']) ?>" class="text-decoration-none">
                            <img src="<?= htmlspecialchars('../images/room.jpg') ?>" alt="Room Image">
                            <div class="room-info">
                                <p>Room: <?= htmlspecialchars($room['RoomNo']) ?></p>
                                <p>Capacity: <?= htmlspecialchars($room['Capacity']) ?></p>
                            </div>
                            <div class="room-overlay"><?= htmlspecialchars($room['RoomNo']) ?></div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>
