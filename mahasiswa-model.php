<?php
function get_all_mahasiswa($conn)
{
    $query = "SELECT m.id AS mahasiswa_id, m.npm, m.prodi, m.fakultas, m.semester, u.nama, u.email
            FROM mahasiswa m
            JOIN users u ON m.user_id = u.id
            ORDER BY m.npm ASC";

    $result = mysqli_query($conn, $query);
    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}
function get_mahasiswa_by_id($conn, $id) {
    $sql = "SELECT * FROM mahasiswa WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}
function delete_mahasiswa($conn, $id) {
    $sql = "DELETE FROM mahasiswa WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
?>