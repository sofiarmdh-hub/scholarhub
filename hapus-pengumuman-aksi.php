<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include('../config.php');

if (isset($_GET['id'])) {
    $id_pengumuman = intval($_GET['id']);
    $sql = "DELETE FROM pengumuman WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_pengumuman);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Pengumuman berhasil dihapus!'); window.location='../pages/pengumuman.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus pengumuman!'); window.history.back();</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Gagal memproses kueri database!'); window.history.back();</script>";
    }
} else {
    header("Location: ../pages/pengumuman.php");
    exit();
}
?>