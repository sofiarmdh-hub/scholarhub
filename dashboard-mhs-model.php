<?php
function get_mahasiswa_metrics($conn, $mahasiswa_id) {
    // 1. Hitung Total Lomba Aktif
    $q_lomba = mysqli_query($conn, "SELECT COUNT(*) as total FROM lomba WHERE status = 'buka'");
    $total_lomba = mysqli_fetch_assoc($q_lomba)['total'];

    // 2. Hitung Total Beasiswa Aktif
    $q_beasiswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM beasiswa WHERE status = 'buka'");
    $total_beasiswa = mysqli_fetch_assoc($q_beasiswa)['total'];

    // 3. PERBAIKAN: Hitung Jumlah Pengajuan Gabungan (Lomba + Beasiswa) Milik Mahasiswa Ini
    $q_pl = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE mahasiswa_id = $mahasiswa_id");
    $q_pb = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE mahasiswa_id = $mahasiswa_id");
    $total_pengajuan = mysqli_fetch_assoc($q_pl)['total'] + mysqli_fetch_assoc($q_pb)['total'];

    // 4. Hitung Total Pengumuman
    $q_pengumuman_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM pengumuman");
    $total_pengumuman = mysqli_fetch_assoc($q_pengumuman_count)['total'];

    return [
        'lomba'      => $total_lomba,
        'beasiswa'   => $total_beasiswa,
        'pengajuan'  => $total_pengajuan,
        'pengumuman' => $total_pengumuman
    ];
}

function get_riwayat_pendaftaran($conn, $mahasiswa_id) {
    return mysqli_query($conn, "
        SELECT pl.id, l.judul AS nama_program, 'lomba' AS kategori, pl.tanggal_daftar AS created_at, pl.status 
        FROM pendaftaran_lomba pl
        JOIN lomba l ON pl.lomba_id = l.id
        WHERE pl.mahasiswa_id = $mahasiswa_id
        
        UNION ALL
        
        SELECT pb.id, b.nama_beasiswa AS nama_program, 'beasiswa' AS kategori, pb.tanggal_daftar AS created_at, pb.status 
        FROM pendaftaran_beasiswa pb
        JOIN beasiswa b ON pb.beasiswa_id = b.id
        WHERE pb.mahasiswa_id = $mahasiswa_id
        
        ORDER BY created_at DESC LIMIT 5
    ");
}

function get_recent_pengumuman($conn) {
    return mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY created_at DESC LIMIT 3");
}

function get_mahasiswa_total_akumulasi($conn, $mahasiswa_id) {
    // 1. Total Seluruh Pengajuan (Lomba + Beasiswa)
    $q_pl = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE mahasiswa_id = $mahasiswa_id");
    $q_pb = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE mahasiswa_id = $mahasiswa_id");
    $total_pengajuan = mysqli_fetch_assoc($q_pl)['total'] + mysqli_fetch_assoc($q_pb)['total'];

    // 2. Total Seluruh yang Diterima
    $q_tl = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE mahasiswa_id = $mahasiswa_id AND (status = 'diterima' OR status = 'disetujui' OR status = 'lulus')");
    $q_tb = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE mahasiswa_id = $mahasiswa_id AND (status = 'diterima' OR status = 'disetujui' OR status = 'lulus')");
    $total_diterima = mysqli_fetch_assoc($q_tl)['total'] + mysqli_fetch_assoc($q_tb)['total'];

    // 3. Total Seluruh yang Ditolak
    $q_j_l = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_lomba WHERE mahasiswa_id = $mahasiswa_id AND status = 'ditolak'");
    $q_j_b = mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran_beasiswa WHERE mahasiswa_id = $mahasiswa_id AND status = 'ditolak'");
    $total_ditolak = mysqli_fetch_assoc($q_j_l)['total'] + mysqli_fetch_assoc($q_j_b)['total'];

    return [
        'pendaftar' => $total_pengajuan,
        'diterima'  => $total_diterima,
        'ditolak'   => $total_ditolak
    ];
}
?>