<?php
include 'fetch_data.php';

// Check if Room no. parameter is set in the URL query string
if (isset($_GET['roomNo'])) {
    $roomNo = $_GET['roomNo']; // Retrieve the value of 'roomNo' from the URL query string
    $roomDetails = fetchRoomDetails($roomNo); // Fetch details of that room

    // If no details are found for the specified room number
    if (!$roomDetails) {
        echo "<p class='text-danger text-center mt-3'>Room not found!</p>";
        exit;
    }
} else {
    echo "<p class='text-danger text-center mt-3'>No room specified!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand">IT College Room Booking</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="navbar-nav">
                    <a href="room_browse.php" class="btn btn-outline-primary me-2">Home</a>
                    <a href="../login.php" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="h3 text-center mb-0">Room Details: <?= htmlspecialchars($roomDetails['room']['RoomNo']) ?></h1>
            </div>
            <div class="card-body">
                <!-- Room Image -->
                <div class="row">
                    <div class="col-md-6">
                        <img src='../images/room.jpg' alt="Room Image" class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">
                    </div>
                    <div class="col-md-6">
                        <!-- Room Details -->
                        <h4 class="mb-3">Details</h4>
                        <p><strong>Status:</strong> <?= htmlspecialchars($roomDetails['room']['Status']) ?></p>
                        <p><strong>Capacity:</strong> <?= htmlspecialchars($roomDetails['room']['Capacity']) ?></p>
                        <p><strong>Room Type:</strong> <?= htmlspecialchars($roomDetails['room']['RoomType']) ?></p>
                        <p><strong>Department:</strong> <?= htmlspecialchars($roomDetails['room']['Department']) ?></p>
                        <p><strong>Equipment:</strong> <?= htmlspecialchars($roomDetails['room']['Equipment'] ?: 'None') ?></p>
                    </div>
                </div>

                <!-- Booking Form -->
                <h4 class="mt-4">Book This Room</h4>
                <form id="bookingForm" action="book_room.php" method="POST">
                    <input type="hidden" name="roomID" value="<?= htmlspecialchars($roomDetails['room']['RoomID']) ?>">
                    <label for="startTime">Start Time:</label>
                    <input type="datetime-local" name="startTime" required>
                    <label for="endTime">End Time:</label>
                    <input type="datetime-local" name="endTime" required>
                    <button type="submit" class="btn btn-success mt-3">Book Room</button>
                </form>

                <?php if (isset($_GET['success'])): ?>
                    <p class="alert alert-success mt-3">Booking submitted successfully!</p>
                <?php elseif (isset($_GET['error'])): ?>
                    <p class="alert alert-danger mt-3"><?= htmlspecialchars($_GET['error']) ?></p>
                <?php endif; ?>

                <!-- Day Selector -->
                <h4 class="mt-4">Select a Day</h4>
                <div class="day-selector">
                    <?php 
                    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    foreach ($daysOfWeek as $day): ?>
                        <button class="btn btn-outline-primary day-btn" data-day="<?= $day ?>"><?= $day ?></button>
                    <?php endforeach; ?>
                </div>

                <!-- Timeslots Table -->
                <h4 id="selected-day-heading" class="mt-4 d-none">Available Timeslots for <span id="selected-day"></span></h4>
                <table id="timeslot-table" class="table table-bordered timeslot-table">
                    <thead class="table-primary">
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="timeslot-tbody">
                        <!-- Timeslots will be populated dynamically -->
                    </tbody>
                </table>

                <!-- Back Button -->
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-sm btn-secondary">Back to Rooms</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Variables -->
    <script>
        const roomTimeslots = <?= json_encode($roomDetails['timeslots']); ?>;
    </script>
    <!-- Link External JavaScript File -->
    <script src="../js/roomscript.js"></script>
</body>
</html>
