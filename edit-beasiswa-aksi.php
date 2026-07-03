<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/beasiswa-model.php');

if (isset($_POST['update'])) {
    $id                   = $_POST['id'];
    $nama_beasiswa         = $_POST['nama_beasiswa'];
    $penyelenggara        = $_POST['penyelenggara'];
    $deskripsi            = $_POST['deskripsi'];
    $jenis_beasiswa       = $_POST['jenis_beasiswa'];
    $benefit              = $_POST['benefit'];
    $persyaratan          = $_POST['persyaratan'];
    $deadline_pendaftaran = $_POST['deadline_pendaftaran'];
    $status               = $_POST['status'];

    $old_data = get_beasiswa_by_id($conn, $id);
    if (!$old_data) {
        $_SESSION['error'] = "Data beasiswa tidak ditemukan.";
        header("Location: ../pages/data-beasiswa.php");
        exit();
    }
    
    $file_panduan = $old_data['file_panduan']; // Default pakai file lama

    if (isset($_FILES['file_panduan']) && $_FILES['file_panduan']['error'] === UPLOAD_ERR_OK) {
        $file_tmp       = $_FILES['file_panduan']['tmp_name'];
        $file_real_name = $_FILES['file_panduan']['name'];
        
        $file_extension = pathinfo($file_real_name, PATHINFO_EXTENSION);
        $new_file_name  = time() . "_" . preg_replace("/[^a-zA-Fi0-9]/", "_", pathinfo($file_real_name, PATHINFO_FILENAME)) . "." . $file_extension;
        
        $target_dir = "../uploads/beasiswa/";

        if (move_uploaded_file($file_tmp, $target_dir . $new_file_name)) {
            // Jika berhasil upload yang baru, hapus file lama dari server (jika ada)
            if (!empty($old_data['file_panduan']) && file_exists($target_dir . $old_data['file_panduan'])) {
                unlink($target_dir . $old_data['file_panduan']);
            }
            $file_panduan = $new_file_name;
        }
    }

    $update_status = update_beasiswa($conn, $id, $nama_beasiswa, $penyelenggara, $deskripsi, $jenis_beasiswa, $benefit, $persyaratan, $deadline_pendaftaran, $status, $file_panduan);

    if ($update_status) {
        $_SESSION['sukses'] = "Data program beasiswa berhasil diperbarui!";
        header("Location: ../pages/data-beasiswa.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data beasiswa. Silakan periksa kembali inputan Anda.";
        header("Location: ../pages/edit-beasiswa.php?id=" . $id);
        exit();
    }
} else {
    header("Location: ../pages/data-beasiswa.php");
    exit();
}
?>