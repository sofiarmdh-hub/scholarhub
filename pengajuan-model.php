<?php
function get_history_lomba($conn, $mhs_id) {
    return mysqli_query($conn, "SELECT pl.*, l.judul FROM pendaftaran_lomba pl JOIN lomba l ON pl.lomba_id = l.id WHERE pl.mahasiswa_id = '$mhs_id' ORDER BY pl.tanggal_daftar DESC");
}
function get_history_beasiswa($conn, $mhs_id) {
    return mysqli_query($conn, "SELECT pb.*, b.nama_beasiswa FROM pendaftaran_beasiswa pb JOIN beasiswa b ON pb.beasiswa_id = b.id WHERE pb.mahasiswa_id = '$mhs_id' ORDER BY pb.tanggal_daftar DESC");
}
function get_total_pengajuan($conn, $tabel, $mhs_id) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM $tabel WHERE mahasiswa_id = '$mhs_id'");
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
?>