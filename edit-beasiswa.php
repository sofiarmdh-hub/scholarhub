<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/beasiswa-model.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: data-beasiswa.php");
    exit();
}

$id_beasiswa = $_GET['id'];
$beasiswa = get_beasiswa_by_id($conn, $id_beasiswa);

if (!$beasiswa) {
    $_SESSION['error'] = "Data beasiswa tidak ditemukan.";
    header("Location: data-beasiswa.php");
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

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $_SESSION['error'];
                                                                                unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="page-heading mb-4">
                    <div class="page-heading-copy">
                        <span class="page-icon"><i class="bi bi-trophy-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Manajemen Informasi</p>
                            <h1 class="h3 mb-1">Edit Program Beasiswa</h1>
                            <p class="text-muted mb-0">Perbarui rincian informasi data program beasiswa ScholarHub.</p>
                        </div>
                    </div>
                    <div class="page-heading-actions">
                        <a href="data-beasiswa.php" class="btn btn-light btn-sm fw-semibold border">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <section class="panel p-4" style="background: #fff; border-radius: 16px; border: 1px solid rgba(0, 0, 0, 0.08); box-shadow: 0 4px 24px rgba(0,0,0,0.02);">
                    <form action="../aksi/edit-beasiswa-aksi.php" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id" value="<?= $beasiswa['id']; ?>">

                        <div class="row">
                            <div class="col-md-6 pe-md-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-trophy text-primary me-1"></i> Nama Beasiswa</label>
                                    <input type="text" class="form-control form-control-md" name="nama_beasiswa" value="<?= htmlspecialchars($beasiswa['nama_beasiswa']); ?>" placeholder="Masukkan nama beasiswa..." style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-tags text-primary me-1"></i> Jenis Beasiswa</label>
                                    <input type="text" class="form-control form-control-md" name="jenis_beasiswa" value="<?= htmlspecialchars($beasiswa['jenis_beasiswa']); ?>" placeholder="e.g. Penuh, Parsial, Bantuan Dana" style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-calendar-check text-primary me-1"></i> Deadline Pendaftaran</label>
                                    <input type="date" class="form-control form-control-md" name="deadline_pendaftaran" value="<?= $beasiswa['deadline_pendaftaran']; ?>" style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4 border-start-md">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-building text-primary me-1"></i> Penyelenggara</label>
                                    <input type="text" class="form-control form-control-md" name="penyelenggara" value="<?= htmlspecialchars($beasiswa['penyelenggara']); ?>" placeholder="Nama instansi/universitas penyelenggara..." style="border-radius: 8px; padding: 10px 14px;" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-toggle-on text-primary me-1"></i> Status Registrasi</label>
                                    <select class="form-select form-control-md" name="status" style="border-radius: 8px; padding: 10px 14px;" required>
                                        <option value="buka" <?= $beasiswa['status'] === 'buka' ? 'selected' : ''; ?>>Dibuka</option>
                                        <option value="tutup" <?= $beasiswa['status'] === 'tutup' ? 'selected' : ''; ?>>Ditutup</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-gift text-primary me-1"></i> Benefit</label>
                                    <textarea class="form-control" name="benefit" placeholder="Sertifikat, uang tunai, UKT, dana bulanan, dll..." style="border-radius: 8px; padding: 12px; height: 80px; resize: none;" required><?= htmlspecialchars($beasiswa['benefit']); ?></textarea>
                                </div>

                            </div>

                            <div class="col-12 mt-2">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-list-check text-primary me-1"></i> Persyaratan Utama</label>
                                    <textarea class="form-control" name="persyaratan" placeholder="Tulis kriteria pendaftaran dan persyaratan dokumen di sini..." style="border-radius: 8px; padding: 12px; height: 110px; resize: none;" required><?= htmlspecialchars($beasiswa['persyaratan']); ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-file-text text-primary me-1"></i> Deskripsi Beasiswa</label>
                                    <textarea class="form-control" name="deskripsi" placeholder="Tulis rincian deskripsi ketentuan beasiswa di sini..." style="border-radius: 8px; padding: 12px; height: 140px; resize: none;" required><?= htmlspecialchars($beasiswa['deskripsi']); ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark mb-2"><i class="bi bi-file-earmark-pdf text-danger me-1"></i> Dokumen Panduan (PDF)</label>
                                    <input type="file" class="form-control" name="file_panduan" accept=".pdf" style="border-radius: 8px; padding: 10px 14px;">
                                    <?php if (!empty($beasiswa['file_panduan'])): ?>
                                        <div class="form-text text-muted mt-2 d-flex align-items-center gap-1">
                                            <i class="bi bi-file-earmark-check text-success"></i>
                                            File saat ini: <span class="fw-semibold text-dark"><?= htmlspecialchars($beasiswa['file_panduan']); ?></span> <span class="text-secondary">(Biarkan kosong jika tidak ingin mengubah file)</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="text-muted opacity-10 mb-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="data-beasiswa.php" class="btn btn-light fw-semibold px-4" style="border-radius: 8px; padding: 10px 20px; border: 1px solid #ddd;">Batal</a>
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