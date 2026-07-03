<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/dashboard-mhs-model.php');

$mahasiswa_id = intval($_SESSION['user_id']);
$metrics = get_mahasiswa_metrics($conn, $mahasiswa_id);

$query_riwayat = get_riwayat_pendaftaran($conn, $mahasiswa_id);
$query_mhs_pengumuman = get_recent_pengumuman($conn);

$akumulasi_chart = get_mahasiswa_total_akumulasi($conn, $mahasiswa_id);

include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="admin-main">
    <?php include($base_path . 'includes/navbar.php'); ?>

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-house-door-fill" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Overview</p>
                        <h1 class="h3 mb-1">Dashboard Mahasiswa</h1>
                        <p class="text-muted mb-0">Selamat datang kembali! Pantau status pengajuan beasiswa dan kompetisimu di sini.</p>
                    </div>
                </div>
            </div>

            <section class="row g-3 mt-1" aria-label="Dashboard metrics">
                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-primary">
                        <div class="metric-top">
                            <span class="metric-label">Lomba Aktif</span>
                            <span class="metric-icon"><i class="bi bi-trophy" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value"><?= $metrics['lomba']; ?></div>
                        <div class="metric-meta"><span>Kompetisi tersedia</span></div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-success">
                        <div class="metric-top">
                            <span class="metric-label">Info Beasiswa</span>
                            <span class="metric-icon"><i class="bi bi-award" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value"><?= $metrics['beasiswa']; ?></div>
                        <div class="metric-meta"><span>Program dibuka</span></div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-warning">
                        <div class="metric-top">
                            <span class="metric-label">Pengajuan Saya</span>
                            <span class="metric-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value"><?= $metrics['pengajuan']; ?></div>
                        <div class="metric-meta"><span>Berkas terkirim</span></div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-danger">
                        <div class="metric-top">
                            <span class="metric-label">Pengumuman</span>
                            <span class="metric-icon"><i class="bi bi-megaphone" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value"><?= $metrics['pengumuman']; ?></div>
                        <div class="metric-meta"><span>Informasi kampus</span></div>
                    </article>
                </div>
            </section>

            <section class="row g-3 mt-1">
                <div class="col-12 col-xl-8">
                    <div class="panel h-100">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Grafik Akumulasi Pengajuan Saya</span></h2>
                                <p class="text-muted mb-0">Total perbandingan seluruh berkas pendaftaran yang dikirim, diterima, dan ditolak.</p>
                            </div>
                        </div>

                        <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
                            <canvas id="scholarhubMhsAkumulasiChart"
                                data-total-pengajuan="<?= $akumulasi_chart['pendaftar']; ?>"
                                data-total-diterima="<?= $akumulasi_chart['diterima']; ?>"
                                data-total-ditolak="<?= $akumulasi_chart['ditolak']; ?>">
                            </canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="panel h-100">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-megaphone" aria-hidden="true"></i><span>Pengumuman Terbaru</span></h2>
                                <p class="text-muted mb-0">Broadcast penting dari admin.</p>
                            </div>
                        </div>
                        <div class="activity-list">
                            <?php if (mysqli_num_rows($query_mhs_pengumuman) == 0): ?>
                                <div class="p-4 text-center text-muted small">Belum ada info terbaru.</div>
                                <?php else: while ($p_row = mysqli_fetch_assoc($query_mhs_pengumuman)):
                                    $color = 'bg-secondary';
                                    if (strtolower($p_row['kategori']) === 'lomba') $color = 'bg-primary';
                                    if (strtolower($p_row['kategori']) === 'beasiswa') $color = 'bg-success';
                                ?>
                                    <div class="activity-item">
                                        <span class="activity-dot <?= $color; ?>"></span>
                                        <div>
                                            <p class="mb-1 fw-semibold text-dark"><?= htmlspecialchars($p_row['judul']); ?></p>
                                            <p class="text-muted small mb-0"><?= htmlspecialchars(mb_strimwidth($p_row['isi'], 0, 70, "...")); ?></p>
                                        </div>
                                    </div>
                            <?php endwhile;
                            endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo $base_path; ?>assets/js/dashboard-mhs-chart.js"></script>
    <?php include($base_path . 'includes/footer.php'); ?>
</div>