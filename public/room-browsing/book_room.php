<?php
include 'fetch_data.php';

session_start();
if (!isset($_SESSION['username'])) {
    echo "You need to be logged in to book a room.";
    exit;
}

function getRoomIDFromRoomNo($roomNo) {
    try {
        $db = connect();
        $stmt = $db->prepare("SELECT RoomID FROM Rooms WHERE RoomNo = ?");
        $stmt->execute([$roomNo]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug output
        error_log("RoomNo: " . $roomNo . ", Found RoomID: " . ($result ? $result['RoomID'] : 'not found'));
        
        return $result ? $result['RoomID'] : null;
    } catch (PDOException $e) {
        error_log("Error fetching RoomID: " . $e->getMessage());
        return null;
    }
}

function bookRoom() {
    $jsonData = json_decode(file_get_contents('php://input'), true);
    
    // Debug log the incoming data
    error_log("Received booking data: " . print_r($jsonData, true));
    
    $roomNo = $jsonData['roomID']; // This is the RoomNo from the URL
    $roomID = getRoomIDFromRoomNo($roomNo);
    
    error_log("Looking up RoomNo: $roomNo, Found RoomID: $roomID");
    
    if (!$roomID) {
        echo "Invalid room number: $roomNo";
        exit;
    }

    $startTime = $jsonData['startTime'];
    $endTime = $jsonData['endTime'];
    $timeSlotID = $jsonData['timeSlotID'];
    $userID = getUserIDByUsername($_SESSION['username']);

    if (!$userID) {
        echo "User not found";
        exit;
    }

    try {
        $db = connect();
        $stmt = $db->prepare("INSERT INTO Bookings (BookedBy, RoomID, StartTime, EndTime, TimeSlotID, Status) 
                             VALUES (:userID, :roomID, :startTime, :endTime, :timeSlotID, 'Pending')");
        
        $params = [
            ':userID' => $userID,
            ':roomID' => $roomID,
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            ':timeSlotID' => $timeSlotID
        ];
        
        // Debug log the parameters being used
        error_log("Executing insert with params: " . print_r($params, true));
        
        $stmt->execute($params);
        echo "Booking successful!";
    } catch (PDOException $e) {
        echo "Booking failed: " . $e->getMessage();
        error_log("Booking error: " . $e->getMessage());
    }
}

function getUserIDByUsername($username) {
    try {
        $db = connect();
        $stmt = $db->prepare("SELECT UserID FROM Users WHERE Username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug output
        error_log("Username: " . $username . ", Found UserID: " . ($result ? $result['UserID'] : 'not found'));
        
        return $result ? $result['UserID'] : null;
    } catch (PDOException $e) {
        error_log("Error fetching UserID: " . $e->getMessage());
        return null;
    }
}

function deleteBooking($bookingId, $username) {
    try {
        $db = connect();
        
        // First verify that this booking belongs to the user
        $stmt = $db->prepare("
            SELECT b.BookingID 
            FROM Bookings b 
            JOIN Users u ON b.BookedBy = u.UserID 
            WHERE b.BookingID = ? AND u.Username = ?
        ");
        $stmt->execute([$bookingId, $username]);
        
        if ($stmt->fetch()) {
            // Delete the booking
            $deleteStmt = $db->prepare("DELETE FROM Bookings WHERE BookingID = ?");
            $deleteStmt->execute([$bookingId]);
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Delete Booking Error: " . $e->getMessage());
        return false;
    }
}

// Handle POST request for booking deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    session_start();
    
    if (!isset($_SESSION['username']) || !isset($_POST['booking_id'])) {
        header("Location: ../profile-management/profile_page.php?error=invalid_request");
        exit();
    }

    $success = deleteBooking($_POST['booking_id'], $_SESSION['username']);
    
    if ($success) {
        header("Location: ../profile-management/profile_page.php?success=booking_deleted");
    } else {
        header("Location: ../profile-management/profile_page.php?error=delete_failed");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    bookRoom();
}
?>

