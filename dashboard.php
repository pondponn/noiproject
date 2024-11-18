<?php
// dashboard.php

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome to your dashboard, " . $_SESSION['email'] . "!";
?>
