<?php
// Reusable DB connection
function getDatabaseConnection() {
    return new PDO('mysql:host=localhost;dbname=333project', 'root', '');
}

// Fetch rooms
function fetchRooms() {
    $db = getDatabaseConnection();
    $stmt = $db->prepare("SELECT RoomID, Status, Capacity, RoomType, Equipment, Department FROM rooms");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchRoomDetails($roomNo) {
    $db = getDatabaseConnection();

    // Fetch room details using RoomNo
    $stmt = $db->prepare("SELECT * FROM rooms WHERE RoomNo = :RoomNo");
    $stmt->bindParam(':RoomNo', $roomNo, PDO::PARAM_STR);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        return null; // Room not found
    }

    // Fetch available timeslots using RoomID
    $stmt = $db->prepare("SELECT DayOfWeek, StartTime, EndTime, IsAvailable FROM TimeSlots WHERE RoomID = :RoomID");
    $stmt->bindParam(':RoomID', $room['RoomID'], PDO::PARAM_INT);
    $stmt->execute();
    $timeslots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ['room' => $room, 'timeslots' => $timeslots];
}

// Fetch rooms grouped by department (this is for the purpose of separating each department's rooms in website display)
function fetchRoomsByDepartment($department) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare("SELECT RoomNo, Capacity FROM Rooms WHERE Department = :Department");
    $stmt->bindParam(':Department', $department, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
