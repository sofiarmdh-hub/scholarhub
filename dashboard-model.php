<?php
function get_overview_cards($conn) {
    $q_mhs = mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa");
    $total_mhs = mysqli_fetch_assoc($q_mhs)['total'];

    $q_pl = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE status = 'diajukan'");
    $q_pb = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE status = 'diajukan'");
    $total_masuk = mysqli_fetch_assoc($q_pl)['total'] + mysqli_fetch_assoc($q_pb)['total'];

    $q_lomba = mysqli_query($conn, "SELECT COUNT(*) as total FROM lomba WHERE status = 'buka'");
    $total_lomba = mysqli_fetch_assoc($q_lomba)['total'];

    $q_beasiswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM beasiswa WHERE status = 'buka'");
    $total_beasiswa = mysqli_fetch_assoc($q_beasiswa)['total'];

    return [
        'mahasiswa'    => $total_mhs,
        'berkas_masuk' => $total_masuk,
        'lomba'        => $total_lomba,
        'beasiswa'     => $total_beasiswa
    ];
}

function get_chart_totals($conn, $bulan_awal, $bulan_akhir, $tahun = 2026) {
    $q_l = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE YEAR(tanggal_daftar) = $tahun AND MONTH(tanggal_daftar) BETWEEN $bulan_awal AND $bulan_akhir");
    $q_b = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE YEAR(tanggal_daftar) = $tahun AND MONTH(tanggal_daftar) BETWEEN $bulan_awal AND $bulan_akhir");
    $pendaftar = mysqli_fetch_assoc($q_l)['total'] + mysqli_fetch_assoc($q_b)['total'];

    $q_tl = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE status = 'diterima' AND YEAR(tanggal_daftar) = $tahun AND MONTH(tanggal_daftar) BETWEEN $bulan_awal AND $bulan_akhir");
    $q_tb = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE status = 'diterima' AND YEAR(tanggal_daftar) = $tahun AND MONTH(tanggal_daftar) BETWEEN $bulan_awal AND $bulan_akhir");
    $diterima = mysqli_fetch_assoc($q_tl)['total'] + mysqli_fetch_assoc($q_tb)['total'];

    $q_j_l = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE status = 'ditolak' AND YEAR(tanggal_daftar) = $tahun AND MONTH(tanggal_daftar) BETWEEN $bulan_awal AND $bulan_akhir");
    $q_j_b = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE status = 'ditolak' AND YEAR(tanggal_daftar) = $tahun AND MONTH(tanggal_daftar) BETWEEN $bulan_awal AND $bulan_akhir");
    $ditolak = mysqli_fetch_assoc($q_j_l)['total'] + mysqli_fetch_assoc($q_j_b)['total'];

    return [
        'pendaftar' => $pendaftar,
        'diterima'  => $diterima,
        'ditolak'   => $ditolak
    ];
}
?>