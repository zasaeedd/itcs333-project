<?php
include 'fetch_data.php';

function getRoomIDFromRoomNo($roomNo) {
    $db = connect();
    $stmt = $db->prepare("SELECT RoomID FROM Rooms WHERE RoomNo = ?");
    $stmt->execute([$roomNo]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['RoomID'] : null;
}

$roomNo = $_GET['roomId'];  // This is actually RoomNo from the URL
$date = $_GET['date'];
$startTime = $_GET['startTime'];
$endTime = $_GET['endTime'];

// Get the actual RoomID from RoomNo
$roomID = getRoomIDFromRoomNo($roomNo);

// Convert date from DD/MM/YYYY to YYYY-MM-DD
$dateParts = explode('/', $date);
$mysqlDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

$db = connect();
$stmt = $db->prepare("
    SELECT COUNT(*) as count 
    FROM Bookings 
    WHERE RoomID = :roomID 
    AND DATE(StartTime) = :date
    AND StartTime = :startDateTime
    AND EndTime = :endDateTime
    AND Status IN ('Pending', 'Confirmed')
");

$startDateTime = $mysqlDate . ' ' . $startTime;
$endDateTime = $mysqlDate . ' ' . $endTime;

$stmt->execute([
    ':roomID' => $roomID,
    ':date' => $mysqlDate,
    ':startDateTime' => $startDateTime,
    ':endDateTime' => $endDateTime
]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['isBooked' => $result['count'] > 0]);
?>