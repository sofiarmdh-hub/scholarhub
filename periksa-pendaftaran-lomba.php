<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/lomba-model.php');

$id_pendaftaran = isset($_GET['id']) ? intval($_GET['id']) : 0;
$detail = get_detail_pendaftaran_lomba($conn, $id_pendaftaran);

if (!$detail) {
    echo "<script>alert('Data pendaftaran tidak ditemukan!'); window.location='data-pendaftaran.php';</script>";
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

                <div class="mb-3">
                    <a href="data-pendaftaran.php" class="text-decoration-none text-secondary small fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Pendaftaran
                    </a>
                </div>

                <div class="page-heading mb-4">
                    <div class="page-heading-copy">
                        <span class="page-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Validasi Berkas Kompetisi</p>
                            <h1 class="h3 mb-1">Periksa Pendaftaran Lomba</h1>
                            <p class="text-muted mb-0">Review dokumen pendaftaran yang diajukan oleh mahasiswa.</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-3 text-dark">Profil Pendaftar</h5>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block">Nama Lengkap</label>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($detail['nama_mahasiswa']); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block">NPM / NIM</label>
                                        <span class="fw-semibold text-dark"><?= htmlspecialchars($detail['npm']); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block">Program Studi</label>
                                        <span class="text-dark"><?= htmlspecialchars($detail['prodi']); ?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block">Lomba yang Diikuti</label>
                                        <span class="fw-bold text-primary"><?= htmlspecialchars($detail['judul']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-3 text-dark">Dokumen Persyaratan</h5>
                                
                                <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-3 me-3"></i>
                                        <div>
                                            <strong class="d-block text-dark">File Berkas Pendaftaran</strong>
                                            <small class="text-muted">Format PDF / ZIP berkas syarat</small>
                                        </div>
                                    </div>
                                    <?php if (!empty($detail['file_berkas'])): ?>
                                        <a href="../uploads/berkas_lomba/<?= $detail['file_berkas']; ?>" target="_blank" class="btn btn-sm btn-outline-primary fw-bold px-3" style="border-radius: 8px;">
                                            <i class="bi bi-eye me-1"></i> Lihat File
                                        </a>
                                    <?php else: ?>
                                        <span class="text-danger small fw-bold">Tidak ada file</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-3 text-dark">Aksi Kelayakan</h5>
                                
                                <form action="../aksi/verifikasi-lomba-aksi.php" method="POST">
                                    <input type="hidden" name="id_pendaftaran" value="<?= $detail['id']; ?>">

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Ubah Status Berkas</label>
                                        <select class="form-select" name="status" style="border-radius: 8px; padding: 10px;" required>
                                            <option value="diajukan" <?= $detail['status'] === 'diajukan' ? 'selected' : ''; ?>>Diajukan (Antrian)</option>
                                            <option value="diproses" <?= $detail['status'] === 'diproses' ? 'selected' : ''; ?>>Diproses (Pemeriksaan)</option>
                                            <option value="diterima" <?= $detail['status'] === 'diterima' ? 'selected' : ''; ?>>Diterima / Lolos</option>
                                            <option value="ditolak" <?= $detail['status'] === 'ditolak' ? 'selected' : ''; ?>>Ditolak / Gugur</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">Catatan Admin</label>
                                        <textarea class="form-control" name="catatan" rows="4" placeholder="Tulis alasan jika berkas ditolak atau informasi tambahan di sini..." style="border-radius: 8px; font-size: 0.9rem;"><?= htmlspecialchars($detail['catatan'] ?? ''); ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5" style="border-radius: 8px; background: #1a56db; border: none;">
                                        <i class="bi bi-save me-1"></i> Simpan Verifikasi
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <?php include($base_path . 'includes/footer.php'); ?>
    </div>
</div>