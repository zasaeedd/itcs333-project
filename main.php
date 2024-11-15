<?php 
session_start();


if (!isset($_SESSION['username'])) {
    echo'login first';
}

else
echo"this is the main page";
?>