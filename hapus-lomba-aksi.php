<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/lomba-model.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $lomba_id = $_GET['id'];
    $lomba = get_lomba_by_id($conn, $lomba_id);

    if ($lomba && $lomba['status'] === 'buka') {
        $_SESSION['error'] = "Gagal! Informasi lomba yang masih berstatus DIBUKA dilarang keras untuk dihapus.";
        header("Location: ../pages/data-lomba.php");
        exit();
    }

    if ($lomba) {
        $hapus_status = delete_lomba($conn, $lomba_id);
        if ($hapus_status) {
            $_SESSION['sukses'] = "Informasi kompetisi berhasil dihapus dari sistem.";
        } else {
            $_SESSION['error'] = "Gagal menghapus data kompetisi.";
        }
    }
}

header("Location: ../pages/data-lomba.php");
exit();
?>