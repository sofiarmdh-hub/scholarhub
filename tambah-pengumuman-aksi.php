<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = isset($_POST['kategori']) ? $_POST['kategori'] : ''; 
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $sql = "INSERT INTO pengumuman (judul, isi, kategori) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $judul, $isi, $kategori);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Pengumuman berhasil diterbitkan!'); window.location='../pages/pengumuman.php';</script>";
        } else {
            echo "<script>alert('Gagal menerbitkan pengumuman!'); window.history.back();</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Gagal menyiapkan query database!'); window.history.back();</script>";
    }
} else {
    header("Location: ../pages/pengumuman.php");
}
?>