<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/lomba-model.php');

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_lomba = $_POST['judul'];
    $penyelenggara = $_POST['penyelenggara'];
    $kategori = $_POST['kategori'];
    $tingkat = $_POST['tingkat'];
    $deadline_pendaftaran = $_POST['deadline_pendaftaran'];
    $status = $_POST['status'];
    $deskripsi = $_POST['deskripsi'];
    $benefit = $_POST['benefit'];

    $lomba_lama = get_lomba_by_id($conn, $id);
    $file_panduan = $lomba_lama['file_panduan']; // Default pakai file lama

    if (isset($_FILES['file_panduan']) && $_FILES['file_panduan']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file_panduan']['tmp_name'];
        $file_real_name = $_FILES['file_panduan']['name'];
        
        $file_extension = pathinfo($file_real_name, PATHINFO_EXTENSION);
        $new_file_name = time() . "_" . preg_replace("/[^a-zA-Fi0-9]/", "_", pathinfo($file_real_name, PATHINFO_FILENAME)) . "." . $file_extension;
        
        $target_dir = "../uploads/panduan/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $target_dir . $new_file_name)) {
            if (!empty($lomba_lama['file_panduan']) && file_exists($target_dir . $lomba_lama['file_panduan'])) {
                unlink($target_dir . $lomba_lama['file_panduan']);
            }
            $file_panduan = $new_file_name;
        }
    }

    $update_status = update_lomba($conn, $id, $nama_lomba, $penyelenggara, $kategori, $tingkat, $deadline_pendaftaran, $status, $deskripsi, $benefit, $file_panduan);

    if ($update_status) {
        $_SESSION['sukses'] = "Informasi lomba berhasil diperbarui!";
        header("Location: ../pages/data-lomba.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data lomba. Silakan coba lagi.";
        header("Location: ../pages/edit-lomba.php?id=" . $id);
        exit();
    }
} else {
    header("Location: ../pages/data-lomba.php");
    exit();
}
?>