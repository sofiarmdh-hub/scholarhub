<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/mahasiswa-model.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $mahasiswa_id = $_GET['id'];
    $mhs = get_mahasiswa_by_id($conn, $mahasiswa_id);

    if ($mhs && $mhs['status'] === 'aktif') {
        $_SESSION['error'] = "Celah Keamanan Dicegah: Akun mahasiswa yang berstatus AKTIF tidak boleh dihapus via URL!";
        header("Location: ../pages/data-mhs.php");
        exit();
    }

    if ($mhs) {
        $hapus_status = delete_mahasiswa($conn, $mahasiswa_id);
        if ($hapus_status) {
            $_SESSION['sukses'] = "Data mahasiswa nonaktif berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus data mahasiswa.";
        }
    }
}

header("Location: ../pages/data-mhs.php");
exit();
?>