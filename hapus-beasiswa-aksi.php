<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/beasiswa-model.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $beasiswa = get_beasiswa_by_id($conn, $id);

    if ($beasiswa) {
        $target_dir = "../uploads/beasiswa/";
        if (!empty($beasiswa['file_panduan']) && file_exists($target_dir . $beasiswa['file_panduan'])) {
            unlink($target_dir . $beasiswa['file_panduan']);
        }
        $delete_status = delete_beasiswa($conn, $id);

        if ($delete_status) {
            $_SESSION['sukses'] = "Program beasiswa berhasil dihapus secara permanen.";
        } else {
            $_SESSION['error'] = "Gagal menghapus data dari database.";
        }
    } else {
        $_SESSION['error'] = "Data beasiswa tidak ditemukan atau sudah terhapus.";
    }
} else {
    $_SESSION['error'] = "ID data tidak valid.";
}

header("Location: ../pages/data-beasiswa.php");
exit();
?>