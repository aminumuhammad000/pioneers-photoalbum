<?php
session_start();

// If the session 'user' is not set, redirect to login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
