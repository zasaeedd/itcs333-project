<?php 
session_start();

if (!isset($_SESSION['username'])) {
    echo 'Login first';
} else {
    header("Location: room-browsing/room_browse.php");
    exit(); 
}
?>