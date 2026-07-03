<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pendaftaran = intval($_POST['id_pendaftaran']);
    $status = $_POST['status'];
    $catatan = $_POST['catatan']; 
    $sql = "UPDATE pendaftaran_beasiswa SET status = ?, catatan_admin = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $status, $catatan, $id_pendaftaran);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Status pendaftaran beasiswa berhasil diperbarui!'); window.location='../pages/data-pendaftaran.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status beasiswa!'); window.history.back();</script>";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    header("Location: ../pages/data-pendaftaran.php");
}
?>