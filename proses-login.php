<?php
session_start();
include('../config.php');

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query_user = "SELECT * FROM users WHERE email = '$email' AND password = '$password' LIMIT 1";
    $result_user = mysqli_query($conn, $query_user);

    if (mysqli_num_rows($result_user) > 0) {
        $user = mysqli_fetch_assoc($result_user);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'mahasiswa') {
            $user_id = $user['id'];
            $query_mhs = "SELECT id FROM mahasiswa WHERE user_id = '$user_id' LIMIT 1";
            $result_mhs = mysqli_query($conn, $query_mhs);
            
            if (mysqli_num_rows($result_mhs) > 0) {
                $mhs = mysqli_fetch_assoc($result_mhs);
                $_SESSION['mahasiswa_id'] = $mhs['id'];
            }
        }

        header("Location: ../index.php");
        exit();
    } else {
        header("Location: ../login.php?pesan=gagal");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>