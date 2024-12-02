<?php
include 'fetch_data.php'; // Database connection and helper functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookedBy = $_SESSION['userID']; // Ensure session tracks the logged-in user
    $roomID = $_POST['roomID'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $timeSlotID = $_POST['timeSlotID'] ?? null; // Optional if you're using time slots

    $result = bookRoom($bookedBy, $roomID, $startTime, $endTime, $timeSlotID);

    if (strpos($result, 'successful') !== false) {
        header("Location: room_details.php?roomNo=$roomID&success=1");
    } else {
        header("Location: room_details.php?roomNo=$roomID&error=" . urlencode($result));
    }
    exit;
}

function bookRoom($bookedBy, $roomID, $startTime, $endTime, $timeSlotID = null) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare("
        SELECT COUNT(*) FROM Bookings 
        WHERE RoomID = :roomID 
          AND Status IN ('Pending', 'Confirmed') 
          AND ((StartTime < :endTime AND EndTime > :startTime))
    ");
    $stmt->execute([
        ':roomID' => $roomID,
        ':startTime' => $startTime,
        ':endTime' => $endTime
    ]);

    if ($stmt->fetchColumn() > 0) {
        return "Conflict detected! The selected time slot is already booked.";
    }

    $insertStmt = $db->prepare("
        INSERT INTO Bookings (BookedBy, RoomID, StartTime, EndTime, TimeSlotID, Status) 
        VALUES (:bookedBy, :roomID, :startTime, :endTime, :timeSlotID, 'Pending')
    ");
    $insertStmt->execute([
        ':bookedBy' => $bookedBy,
        ':roomID' => $roomID,
        ':startTime' => $startTime,
        ':endTime' => $endTime,
        ':timeSlotID' => $timeSlotID
    ]);

    return "Booking successful! Pending approval.";
}
?>
