<?php
// public/auth/logout.php

session_start();
session_unset();
session_destroy();
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

header('Location: login.php');
exit();
?>
