<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/lomba-model.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: data-lomba.php");
    exit();
}

$lomba_id = $_GET['id'];
$lomba = get_lomba_by_id($conn, $lomba_id);

if (!$lomba) {
    $_SESSION['error'] = "Data kompetisi tidak ditemukan!";
    header("Location: data-lomba.php");
    exit();
}

include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>
    <div class="admin-main">
        <?php include($base_path . 'includes/navbar.php'); ?>

        <main class="dashboard-content">
            <div class="container-fluid px-3 px-lg-4 py-4">

                <div class="page-heading mb-4">
                    <div class="page-heading-copy">
                        <span class="page-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Manajemen Informasi</p>
                            <h1 class="h3 mb-1">Edit Data Lomba</h1>
                            <p class="text-muted mb-0">Perbarui rincian informasi dan berkas kompetisi ScholarHub.</p>
                        </div>
                    </div>
                    <div class="page-heading-actions">
                        <a href="data-lomba.php" class="btn btn-light btn-sm fw-semibold border">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <section class="panel p-4" style="background: #fff; border-radius: 16px; border: 1px solid rgba(0, 0, 0, 0.08); box-shadow: 0 4px 24px rgba(0,0,0,0.02);">
                    <form action="../aksi/edit-lomba-aksi.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $lomba['id']; ?>">

                        <div class="row">
                            <div class="col-md-6 pe-md-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-trophy text-primary me-1"></i> Nama Kompetisi / Lomba</label>
                                    <input type="text" class="form-control form-control-md" name="judul" value="<?= htmlspecialchars($lomba['judul']); ?>" style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-tags text-primary me-1"></i> Kategori</label>
                                    <input type="text" class="form-control form-control-md" name="kategori" value="<?= htmlspecialchars($lomba['kategori']); ?>" style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-calendar-check text-primary me-1"></i> Deadline Pendaftaran</label>
                                    <input type="date" class="form-control form-control-md" name="deadline_pendaftaran" value="<?= $lomba['deadline_pendaftaran']; ?>" style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4 border-start-md">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-building text-primary me-1"></i> Penyelenggara</label>
                                    <input type="text" class="form-control form-control-md" name="penyelenggara" value="<?= htmlspecialchars($lomba['penyelenggara']); ?>" style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-globe text-primary me-1"></i> Tingkat Kompetisi</label>
                                    <select class="form-select form-control-md" name="tingkat" style="border-radius: 8px; padding: 10px 14px;" required>
                                        <option value="Universitas" <?= strcasecmp($lomba['tingkat'], 'Universitas') === 0 ? 'selected' : ''; ?>>Universitas</option>
                                        <option value="Regional" <?= strcasecmp($lomba['tingkat'], 'Regional') === 0 ? 'selected' : ''; ?>>Regional</option>
                                        <option value="Nasional" <?= strcasecmp($lomba['tingkat'], 'Nasional') === 0 ? 'selected' : ''; ?>>Nasional</option>
                                        <option value="Internasional" <?= strcasecmp($lomba['tingkat'], 'Internasional') === 0 ? 'selected' : ''; ?>>Internasional</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-toggle-on text-primary me-1"></i> Status Registrasi</label>
                                    <select class="form-select form-control-md" name="status" style="border-radius: 8px; padding: 10px 14px;" required>
                                        <option value="buka" <?= $lomba['status'] === 'buka' ? 'selected' : ''; ?>>Dibuka</option>
                                        <option value="tutup" <?= $lomba['status'] === 'tutup' ? 'selected' : ''; ?>>Ditutup</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-gift text-primary me-1"></i> Benefit / Hadiah</label>
                                    <textarea class="form-control" name="benefit" style="border-radius: 8px; padding: 12px; height: 80px; resize: none;" required><?= htmlspecialchars($lomba['benefit']); ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-file-text text-primary me-1"></i> Deskripsi Lomba</label>
                                    <textarea class="form-control" name="deskripsi" style="border-radius: 8px; padding: 12px; height: 140px; resize: none;" required><?= htmlspecialchars($lomba['deskripsi']); ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-file-earmark-pdf text-danger me-1"></i> Dokumen Panduan (PDF)</label>
                                    <input type="file" class="form-control" name="file_panduan" accept=".pdf" style="border-radius: 8px; padding: 10px 14px;">
                                    <?php if (!empty($lomba['file_panduan'])): ?>
                                        <div class="mt-2 d-flex align-items-center gap-1 text-secondary" style="font-size: 12px;">
                                            <i class="bi bi-file-earmark-check-fill text-success fs-6"></i>
                                            <span>Berkas aktif: <strong class="text-dark"><?= htmlspecialchars($lomba['file_panduan']); ?></strong></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="text-muted opacity-10 mb-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="data-lomba.php" class="btn btn-light fw-semibold px-4" style="border-radius: 8px; padding: 10px 20px; border: 1px solid #ddd;">Batal</a>
                                    <button type="submit" name="update" class="btn btn-primary fw-semibold px-4" style="border-radius: 8px; padding: 10px 24px; background: #1a56db; border: none;">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

            </div>
        </main>

        <?php include($base_path . 'includes/footer.php'); ?>
    </div>
</div>