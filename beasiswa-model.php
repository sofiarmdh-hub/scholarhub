<?php
function get_all_beasiswa($conn)
{
    $sql = "SELECT * FROM beasiswa ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function get_beasiswa_by_id($conn, $id)
{
    $sql = "SELECT * FROM beasiswa WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $data;
    }
    return false;
}

function insert_beasiswa($conn, $nama_beasiswa, $penyelenggara, $deskripsi, $jenis_beasiswa, $benefit, $persyaratan, $deadline_pendaftaran, $status, $file_panduan)
{
    $sql = "INSERT INTO beasiswa (nama_beasiswa, penyelenggara, deskripsi, jenis_beasiswa, benefit, persyaratan, deadline_pendaftaran, status, file_panduan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $nama_beasiswa, $penyelenggara, $deskripsi, $jenis_beasiswa, $benefit, $persyaratan, $deadline_pendaftaran, $status, $file_panduan);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    return false;
}

function update_beasiswa($conn, $id, $nama_beasiswa, $penyelenggara, $deskripsi, $jenis_beasiswa, $benefit, $persyaratan, $deadline_pendaftaran, $status, $file_panduan)
{
    $sql = "UPDATE beasiswa SET nama_beasiswa = ?, penyelenggara = ?, deskripsi = ?, jenis_beasiswa = ?, benefit = ?, persyaratan = ?, deadline_pendaftaran = ?, status = ?, file_panduan = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssssi", $nama_beasiswa, $penyelenggara, $deskripsi, $jenis_beasiswa, $benefit, $persyaratan, $deadline_pendaftaran, $status, $file_panduan, $id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    return false;
}

function delete_beasiswa($conn, $id)
{
    $sql = "DELETE FROM beasiswa WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    return false;
}

function get_all_pendaftaran_beasiswa($conn) {
    $sql = "SELECT pb.*, u.nama AS nama_mahasiswa, m.npm, m.prodi, b.nama_beasiswa 
            FROM pendaftaran_beasiswa pb
            JOIN mahasiswa m ON pb.mahasiswa_id = m.id
            JOIN users u ON m.user_id = u.id
            JOIN beasiswa b ON pb.beasiswa_id = b.id 
            ORDER BY pb.id DESC";
            
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function get_detail_pendaftaran_beasiswa($conn, $id) {
    $sql = "SELECT pb.*, u.nama AS nama_mahasiswa, m.npm, m.prodi, b.nama_beasiswa 
            FROM pendaftaran_beasiswa pb
            JOIN mahasiswa m ON pb.mahasiswa_id = m.id
            JOIN users u ON m.user_id = u.id
            JOIN beasiswa b ON pb.beasiswa_id = b.id 
            WHERE pb.id = ?";
            
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $data;
    }
    return false;
}

function get_all_beasiswa_aktif_mhs($conn) {
    $query = "SELECT * FROM beasiswa WHERE status = 'buka' ORDER BY id DESC";
    return mysqli_query($conn, $query);
}

function get_detail_beasiswa($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM beasiswa WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}
?>