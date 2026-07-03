<?php 
session_start();

include('config.php');

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] === 'mahasiswa') {
    header("Location: pages/dashboard-mhs.php");
    exit();
} else if ($_SESSION['role'] === 'admin') {
    header("Location: pages/dashboard-admin.php");
    exit();
} else {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>