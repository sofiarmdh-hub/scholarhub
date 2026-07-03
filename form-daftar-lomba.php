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
$lomba = get_lomba_by_id($conn, $id_lomba);
if (!$lomba || (isset($lomba['jenis_lomba']) && strtolower($lomba['jenis_lomba']) !== 'internal')) {
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
                <a href="detail-lomba.php?id=<?= $lomba['id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 text-secondary fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
                </a>
            </div>

            <!-- HEADING -->
            <div class="page-heading mb-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="page-icon bg-primary-subtle text-primary"><i class="bi bi-cloud-upload" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Formulir Unggah Berkas</p>
                        <h1 class="h3 mb-1">Pendaftaran Kompetisi</h1>
                        <p class="text-muted mb-0">Lomba: <span class="fw-semibold text-dark"><?= htmlspecialchars($lomba['judul']); ?></span></p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="panel border-0 shadow-sm p-4">

                        <form action="#" method="POST" enctype="multipart/form-data" id="formPendaftaran">
                            <input type="hidden" name="lomba_id" value="<?= $lomba['id']; ?>">
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-medium text-secondary small">Nama Kompetisi</label>
                                    <input type="text" class="form-control bg-light text-dark fw-semibold" value="<?= htmlspecialchars($lomba['judul']); ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-secondary small">Tingkat Kompetisi</label>
                                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($lomba['tingkat'] ?? 'Nasional'); ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-secondary small">Mengajukan Atas Nama (Anda)</label>
                                    <input type="text" class="form-control bg-light text-primary fw-medium" value="<?= htmlspecialchars($_SESSION['nama'] ?? 'Mahasiswa ScholarHub'); ?>" readonly>
                                </div>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <div class="mb-4">
                                <label for="file_dokumen" class="form-label fw-semibold text-dark mb-1">Unggah Semua Dokumen Persyaratan <span class="text-danger">*</span></label>
                                <p class="text-muted small mb-2">Pilih semua file berkas sekaligus (seperti Proposal, KTM, Transkrip, dll). Format wajib <strong>.pdf</strong>, maksimal 10 file.</p>
                                <input type="file" id="file_dokumen" name="dokumen[]" class="form-control" accept=".pdf" multiple required>
                                <div id="fileErrorFeedback" class="invalid-feedback fw-medium mt-2"></div>
                            </div>

                            <div class="form-check mb-4 p-3 bg-light rounded border ms-0">
                                <input class="form-check-input ms-1 me-2" type="checkbox" id="konfirmasi" required>
                                <label class="form-check-label text-secondary small" for="konfirmasi">
                                    Saya menjamin seluruh dokumen (.pdf) yang diunggah adalah sah dan benar milik tim/peserta bersangkutan.
                                </label>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="detail-lomba.php?id=<?= $lomba['id']; ?>" class="btn btn-light rounded-pill px-4 fw-medium">Batal</a>
                                <button type="submit" name="submit_pendaftaran" id="btnSubmit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                    Kirim Berkas <i class="bi bi-cloud-arrow-up ms-1"></i>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="panel border-0 shadow-sm p-4 bg-light-subtle">
                        <h2 class="h5 fw-bold text-dark pb-2 border-bottom mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Ketentuan File</h2>
                        <ul class="text-secondary small ps-3 mb-0" style="line-height: 1.8;">
                            <li class="mb-2">Gunakan penamaan file yang rapi (Contoh: <code>Proposal_ScholarHub.pdf</code>).</li>
                            <li class="mb-2">Status berkas pendaftaran otomatis akan diset menjadi <strong>"diajukan"</strong>.</li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include($base_path . 'includes/footer.php'); ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('file_dokumen');
        const form = document.getElementById('formPendaftaran');
        const errorFeedback = document.getElementById('fileErrorFeedback');
        const btnSubmit = document.getElementById('btnSubmit');

        fileInput.addEventListener('change', function() {
            if (this.files.length > 10) {
                fileInput.classList.add('is-invalid');
                errorFeedback.textContent = `Waduh, kamu memilih ${this.files.length} file. Maksimal pengunggahan hanya boleh 10 file ya!`;
                btnSubmit.disabled = true;
            } else {
                fileInput.classList.remove('is-invalid');
                errorFeedback.textContent = '';
                btnSubmit.disabled = false;
            }
        });
    });
</script>