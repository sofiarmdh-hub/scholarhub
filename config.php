<?php
$host = "localhost";
$username = "root";
$password = "";
$namaDB = "scholarhub";

$conn = new mysqli($host, $username, $password, $namaDB);

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
} else {
    // echo "Berhasil Connect";
}

$conn->set_charset("utf8mb4"); 
?>