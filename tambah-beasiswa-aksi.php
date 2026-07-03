<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/beasiswa-model.php');

if (isset($_POST['simpan'])) {
    $nama_beasiswa         = $_POST['nama_beasiswa'];
    $penyelenggara        = $_POST['penyelenggara'];
    $deskripsi            = $_POST['deskripsi'];
    $jenis_beasiswa       = $_POST['jenis_beasiswa'];
    $benefit              = $_POST['benefit'];
    $persyaratan          = $_POST['persyaratan'];
    $deadline_pendaftaran = $_POST['deadline_pendaftaran'];
    $status               = $_POST['status'];
    
    $file_panduan = ""; 

    if (isset($_FILES['file_panduan']) && $_FILES['file_panduan']['error'] === UPLOAD_ERR_OK) {
        $file_tmp       = $_FILES['file_panduan']['tmp_name'];
        $file_real_name = $_FILES['file_panduan']['name'];
        
        $file_extension = pathinfo($file_real_name, PATHINFO_EXTENSION);
        $new_file_name  = time() . "_" . preg_replace("/[^a-zA-Fi0-9]/", "_", pathinfo($file_real_name, PATHINFO_FILENAME)) . "." . $file_extension;
        
        $target_dir = "../uploads/beasiswa/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $target_dir . $new_file_name)) {
            $file_panduan = $new_file_name;
        }
    }

    $insert_status = insert_beasiswa($conn, $nama_beasiswa, $penyelenggara, $deskripsi, $jenis_beasiswa, $benefit, $persyaratan, $deadline_pendaftaran, $status, $file_panduan);

    if ($insert_status) {
        $_SESSION['sukses'] = "Program beasiswa baru berhasil diterbitkan!";
        header("Location: ../pages/data-beasiswa.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menyimpan data beasiswa. Silakan periksa kembali format inputan Anda.";
        header("Location: ../pages/tambah-beasiswa.php");
        exit();
    }
} else {
    header("Location: ../pages/data-beasiswa.php");
    exit();
}
?>