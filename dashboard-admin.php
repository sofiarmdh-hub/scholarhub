<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/dashboard-model.php');

$data_card = get_overview_cards($conn);
$paruh_satu = get_chart_totals($conn, 1, 6);
$paruh_dua  = get_chart_totals($conn, 7, 12);

include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="admin-main">
        <?php include($base_path . 'includes/navbar.php'); ?>

        <main class="dashboard-content">
            <div class="container-fluid px-3 px-lg-4 py-4">

                <div class="page-heading">
                    <div class="page-heading-copy">
                        <span class="page-icon"><i class="bi bi-house-door-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Overview</p>
                            <h1 class="h3 mb-1">Dashboard Admin</h1>
                            <p class="text-muted mb-0">Monitor statistik program, pengumuman, dan validasi pendaftaran mahasiswa.</p>
                        </div>
                    </div>
                </div>

                <section class="row g-3 mt-1" aria-label="Dashboard metrics">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-primary">
                            <div class="metric-top">
                                <span class="metric-label">Total Mahasiswa</span>
                                <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value"><?= $data_card['mahasiswa']; ?></div>
                            <div class="metric-meta">
                                <span>terdaftar di ScholarHub</span>
                            </div>
                        </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-success">
                            <div class="metric-top">
                                <span class="metric-label">Pendaftaran Masuk</span>
                                <span class="metric-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value"><?= $data_card['berkas_masuk']; ?></div>
                            <div class="metric-meta">
                                <span class="text-warning fw-semibold">Perlu validasi segera</span>
                            </div>
                        </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-warning">
                            <div class="metric-top">
                                <span class="metric-label">Lomba Aktif</span>
                                <span class="metric-icon"><i class="bi bi-trophy" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value"><?= $data_card['lomba']; ?></div>
                            <div class="metric-meta">
                                <span class="text-muted">Pendaftaran masih dibuka</span>
                            </div>
                        </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-danger">
                            <div class="metric-top">
                                <span class="metric-label">Beasiswa Aktif</span>
                                <span class="metric-icon"><i class="bi bi-award" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value"><?= $data_card['beasiswa']; ?></div>
                            <div class="metric-meta">
                                <span class="text-muted">Pendaftaran masih dibuka</span>
                            </div>
                        </article>
                    </div>
                </section>

                <section class="row g-3 mt-1">
                    <div class="col-12 col-xl-8">
                        <div class="panel h-100">
                            <div class="panel-header">
                                <div>
                                    <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Analisis Konversi Pendaftaran</span></h2>
                                    <p class="text-muted mb-0">Perbandingan akumulasi data pendaftaran paruh pertama dan paruh kedua.</p>
                                </div>
                            </div>

                            <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
                                <canvas id="scholarhubAnalisisChart"
                                    data-p1-pendaftar="<?= $paruh_satu['pendaftar']; ?>"
                                    data-p1-diterima="<?= $paruh_satu['diterima']; ?>"
                                    data-p1-ditolak="<?= $paruh_satu['ditolak']; ?>"
                                    data-p2-pendaftar="<?= $paruh_dua['pendaftar']; ?>"
                                    data-p2-diterima="<?= $paruh_dua['diterima']; ?>"
                                    data-p2-ditolak="<?= $paruh_dua['ditolak']; ?>">
                                </canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="panel h-100">
                            <div class="panel-header">
                                <div>
                                    <h2 class="h5 mb-1 section-title">
                                        <i class="bi bi-megaphone" aria-hidden="true"></i>
                                        <span>Pengumuman Terakhir</span>
                                    </h2>
                                    <p class="text-muted mb-0">Informasi internal broadcast.</p>
                                </div>
                            </div>
                            <div class="activity-list">
                                <?php
                                $query_dashboard_pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY created_at DESC LIMIT 3");

                                if (mysqli_num_rows($query_dashboard_pengumuman) == 0):
                                ?>
                                    <div class="p-4 text-center text-muted small">
                                        <i class="bi bi-chat-left-dots d-block mb-1 fs-5"></i> Belum ada pengumuman.
                                    </div>
                                    <?php
                                else:
                                    while ($dash_p = mysqli_fetch_assoc($query_dashboard_pengumuman)):
                                        // Menentukan warna titik indikator berdasarkan data kategori di database
                                        $dot_color = 'bg-secondary'; // default umum
                                        if (strtolower($dash_p['kategori']) === 'lomba') {
                                            $dot_color = 'bg-primary';
                                        } elseif (strtolower($dash_p['kategori']) === 'beasiswa') {
                                            $dot_color = 'bg-success';
                                        }
                                    ?>
                                        <div class="activity-item">
                                            <span class="activity-dot <?= $dot_color; ?>"></span>
                                            <div>
                                                <p class="mb-1 fw-semibold text-dark"><?= htmlspecialchars($dash_p['judul']); ?></p>
                                                <p class="text-muted small mb-0">
                                                    <?= htmlspecialchars(mb_strimwidth($dash_p['isi'], 0, 80, "...")); ?>
                                                </p>
                                            </div>
                                        </div>
                                <?php
                                        unset($dot_color);
                                    endwhile;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="<?php echo $base_path; ?>assets/js/dashboard-chart.js"></script>

        <?php include($base_path . 'includes/footer.php'); ?>
    </div>
</div>