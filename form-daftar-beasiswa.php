<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/beasiswa-model.php');

$id_beasiswa = isset($_GET['id']) ? intval($_GET['id']) : 0;
$beasiswa = get_detail_beasiswa($conn, $id_beasiswa); 

if (!$beasiswa || strtolower($beasiswa['status']) !== 'buka') {
    header("Location: daftar-beasiswa.php");
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
                <a href="detail-beasiswa.php?id=<?= $beasiswa['id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 text-secondary fw-medium">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
                </a>
            </div>

            <div class="page-heading mb-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="page-icon bg-info-subtle text-info"><i class="bi bi-wallet2" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Formulir Pendaftaran</p>
                        <h1 class="h3 mb-1">Program Beasiswa</h1>
                        <p class="text-muted mb-0">Program: <span class="fw-semibold text-dark"><?= htmlspecialchars($beasiswa['nama_beasiswa']); ?></span></p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="panel border-0 shadow-sm p-4">
                        <form action="#" method="POST" enctype="multipart/form-data" id="formPendaftaran">
                            <input type="hidden" name="beasiswa_id" value="<?= $beasiswa['id']; ?>">

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-medium text-secondary small">Nama Beasiswa</label>
                                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($beasiswa['nama_beasiswa']); ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-secondary small">Jenis Beasiswa</label>
                                    <input type="text" class="form-control bg-light text-capitalize" value="<?= htmlspecialchars($beasiswa['jenis_beasiswa']); ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-medium text-secondary small">Nama Mahasiswa</label>
                                    <input type="text" class="form-control bg-light text-primary fw-medium" value="<?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?>" readonly>
                                </div>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <div class="mb-4">
                                <label for="file_dokumen" class="form-label fw-semibold text-dark mb-1">Unggah Semua Dokumen Persyaratan <span class="text-danger">*</span></label>
                                <p class="text-muted small mb-2">Pilih semua file berkas sekaligus (seperti Proposal, KTM, Transkrip, dll). Format wajib <strong>.pdf</strong>, maksimal 10 file.</p>
                                <input type="file" id="file_dokumen" name="dokumen[]" class="form-control" accept=".pdf" multiple required>
                                <div id="fileErrorFeedback" class="invalid-feedback fw-medium mt-2"></div>
                            </div>

                            <div class="form-check mb-4 p-3 bg-light rounded border">
                                <input class="form-check-input ms-1 me-2" type="checkbox" id="konfirmasi" required>
                                <label class="form-check-label text-secondary small" for="konfirmasi">
                                    Saya menyatakan bahwa dokumen yang saya unggah adalah benar dan sah.
                                </label>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="detail-beasiswa.php?id=<?= $beasiswa['id']; ?>" class="btn btn-light rounded-pill px-4">Batal</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Kirim Pendaftaran</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="panel border-0 shadow-sm p-4 bg-light-subtle">
                        <h2 class="h5 fw-bold text-dark pb-2 border-bottom mb-3">Ketentuan Pendaftaran</h2>
                        <ul class="text-secondary small ps-3 mb-0" style="line-height: 1.8;">
                            <li class="mb-2">Gunakan penamaan file yang rapi (Contoh: <code>Proposal_ScholarHub.pdf</code>).</li>
                            <li class="mb-2">Status berkas pendaftaran otomatis akan diset menjadi <strong>"diajukan"</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>