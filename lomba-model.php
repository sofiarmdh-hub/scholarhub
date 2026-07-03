<?php
function get_all_lomba($conn) {
    $sql = "SELECT * FROM lomba ORDER BY deadline_pendaftaran ASC";
    $result = mysqli_query($conn, $sql);
    
    $list_lomba = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $list_lomba[] = $row;
    }
    return $list_lomba;
}

function get_lomba_by_id($conn, $id) {
    $sql = "SELECT * FROM lomba WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function insert_lomba($conn, $judul, $penyelenggara, $kategori, $tingkat, $deadline_pendaftaran, $status, $deskripsi, $benefit, $file_panduan) {
    $sql = "INSERT INTO lomba (judul, penyelenggara, kategori, tingkat, deadline_pendaftaran, status, deskripsi, benefit, file_panduan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $judul, $penyelenggara, $kategori, $tingkat, $deadline_pendaftaran, $status, $deskripsi, $benefit, $file_panduan);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    return false;
}

function update_lomba($conn, $id, $judul, $penyelenggara, $kategori, $tingkat, $deadline_pendaftaran, $status, $deskripsi, $benefit, $file_panduan) {
    $sql = "UPDATE lomba SET judul = ?, penyelenggara = ?, kategori = ?, tingkat = ?, deadline_pendaftaran = ?, status = ?, deskripsi = ?, benefit = ?, file_panduan = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssi", $judul, $penyelenggara, $kategori, $tingkat, $deadline_pendaftaran, $status, $deskripsi, $benefit, $file_panduan, $id);
    return mysqli_stmt_execute($stmt);
}

function delete_lomba($conn, $id) {
    $sql = "DELETE FROM lomba WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

function get_all_pendaftaran_lomba($conn) {
    $sql = "SELECT pl.*, u.nama AS nama_mahasiswa, m.npm, m.prodi, l.judul 
            FROM pendaftaran_lomba pl
            JOIN mahasiswa m ON pl.mahasiswa_id = m.id
            JOIN users u ON m.user_id = u.id
            JOIN lomba l ON pl.lomba_id = l.id 
            ORDER BY pl.id DESC";
            
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function get_detail_pendaftaran_lomba($conn, $id) {
    $sql = "SELECT pl.*, u.nama AS nama_mahasiswa, m.npm, m.prodi, l.judul 
            FROM pendaftaran_lomba pl
            JOIN mahasiswa m ON pl.mahasiswa_id = m.id
            JOIN users u ON m.user_id = u.id
            JOIN lomba l ON pl.lomba_id = l.id 
            WHERE pl.id = ?";
            
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

function get_all_lomba_aktif_mhs($conn) {
    $sql = "SELECT * FROM lomba WHERE status = 'buka' ORDER BY deadline_pendaftaran ASC";
    $result = mysqli_query($conn, $sql);
    
    $list_lomba = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $list_lomba[] = $row;
        }
    }
    return $list_lomba;
}

function get_detail_lomba($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM lomba WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}
?>