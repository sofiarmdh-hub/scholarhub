<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/lomba-model.php');

$id_lomba = isset($_GET['id']) ? intval($_GET['id']) : 0;
$lomba = get_detail_lomba($conn, $id_lomba);

if (!$lomba) {
    header("Location: daftar-lomba.php");
    exit();
}

include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="admin-main">
    <?php include($base_path . 'includes/navbar.php'); ?>

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="mb-3">
                <a href="daftar-lomba.php" class="btn btn-light btn-sm rounded-pill px-3 text-secondary fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Lomba
                </a>
            </div>

            <div class="panel mb-4 border-0 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <div class="d-flex gap-2 mb-2 flex-wrap">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                                <i class="bi bi-tag-fill me-1"></i><?= htmlspecialchars($lomba['kategori'] ?? 'General'); ?>
                            </span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 small">
                                <i class="bi bi-globe me-1"></i>Tingkat <?= htmlspecialchars($lomba['tingkat'] ?? 'Nasional'); ?>
                            </span>
                        </div>
                        <h1 class="h2 fw-bold text-dark mb-1"><?= htmlspecialchars($lomba['judul']); ?></h1>
                        <p class="text-muted mb-0"><i class="bi bi-building me-1"></i> Penyelenggara: <span class="fw-semibold text-secondary"><?= htmlspecialchars($lomba['penyelenggara']); ?></span></p>
                    </div>

                    <div>
                        <?php if (strtolower($lomba['status']) === 'buka'): ?>
                            <?php if (isset($lomba['jenis_lomba']) && strtolower($lomba['jenis_lomba']) === 'internal'): ?>
                                <a href="form-daftar-lomba.php?id=<?= $lomba['id']; ?>" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold shadow-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Daftar Kompetisi
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-semibold" disabled>
                                    <i class="bi bi-info-circle me-1"></i> Pendaftaran Mandiri
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-secondary px-4 py-2 rounded-pill fw-semibold" disabled>
                                Pendaftaran Ditutup
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="panel border-0 shadow-sm mb-4">
                        <h2 class="h5 fw-bold text-dark pb-2 border-bottom mb-3">Deskripsi Kompetisi</h2>
                        <div class="text-secondary small" style="line-height: 1.6; white-space: pre-line;">
                            <?= htmlspecialchars($lomba['deskripsi']); ?>
                        </div>
                    </div>

                    <div class="panel border-0 shadow-sm">
                        <h2 class="h5 fw-bold text-dark pb-2 border-bottom mb-3">Benefit yang Didapat</h2>
                        <div class="text-secondary small" style="line-height: 1.6; white-space: pre-line;">
                            <?= htmlspecialchars($lomba['benefit'] ?? 'Insentif pendanaan & Sertifikat Penghargaan.'); ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="panel border-0 shadow-sm mb-4 bg-light-subtle">
                        <h2 class="h5 fw-bold text-dark pb-2 border-bottom mb-3">Informasi Penting</h2>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-danger-subtle text-danger rounded p-2 text-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-calendar-x fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Batas Pendaftaran</small>
                                <span class="fw-bold text-dark small">
                                    <?= !empty($lomba['deadline_pendaftaran']) ? date('d F Y', strtotime($lomba['deadline_pendaftaran'])) : 'N/A'; ?>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning-subtle text-warning rounded p-2 text-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Status Akses</small>
                                <span class="badge bg-success-subtle text-success border border-success-subtle small px-2">
                                    Status: <?= ucfirst($lomba['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="panel border-0 shadow-sm">
                        <h2 class="h5 fw-bold text-dark pb-2 border-bottom mb-3">Dokumen Panduan</h2>
                        <p class="text-muted small mb-3">Unduh buku panduan atau TOR resmi untuk mempermudah proses penyusunan berkas kelompok / individu.</p>

                        <?php if (!empty($lomba['file_panduan'])): ?>
                            <a href="../file-panduan/<?= htmlspecialchars($lomba['file_panduan']); ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill py-2 fw-medium" target="_blank" download>
                                <i class="bi bi-download me-1"></i> Unduh Buku Panduan (PDF)
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary btn-sm w-100 rounded-pill py-2" disabled>
                                <i class="bi bi-file-earmark-lock me-1"></i> Panduan Tidak Tersedia
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include($base_path . 'includes/footer.php'); ?>
</div>