<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/beasiswa-model.php'); 

$data_beasiswa = get_all_beasiswa($conn);

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
                        <span class="page-icon"><i class="bi bi-award-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Manajemen Informasi</p>
                            <h1 class="h3 mb-1">Data Program Beasiswa</h1>
                            <p class="text-muted mb-0">Kelola informasi beasiswa, instansi penyedia, dan berkas persyaratan ScholarHub.</p>
                        </div>
                    </div>
                    <div class="page-heading-actions">
                        <a href="tambah-beasiswa.php" class="btn btn-primary btn-sm px-3 fw-semibold">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Beasiswa
                        </a>
                    </div>
                </div>

                <section class="panel">
                    <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h2 class="h5 mb-1 section-title"><span>Daftar Informasi Beasiswa</span></h2>
                            <p class="text-muted mb-0">Gunakan fitur cari untuk menyaring records data program beasiswa.</p>
                        </div>
                        <input class="form-control form-control-sm table-search" type="search" placeholder="Cari beasiswa..." data-table-search="beasiswaTable" style="max-width: 250px;">
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="beasiswaTable" data-searchable-table>
                            <thead class="bg-light text-secondary" style="font-size: 13px; font-weight: 600;">
                                <tr>
                                    <th class="py-3 ps-4" style="width: 30%;">Nama Beasiswa</th>
                                    <th class="text-center py-3" style="width: 15%;">Jenis Beasiswa</th>
                                    <th class="text-center py-3" style="width: 20%;">Deadline Pendaftaran</th>
                                    <th class="text-center py-3" style="width: 15%;">Status</th>
                                    <th class="text-end pe-4 py-3" style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <?php if (empty($data_beasiswa)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">Belum ada data program beasiswa terdaftar.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data_beasiswa as $beasiswa): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark mb-0" style="line-height: 1.3;">
                                                    <?= htmlspecialchars($beasiswa['nama_beasiswa']); ?>
                                                </div>
                                                <small class="text-muted" style="font-size: 11px;">Oleh: <?= htmlspecialchars($beasiswa['penyelenggara']); ?></small>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 12px; font-weight: 500; text-transform: capitalize;">
                                                    <?= htmlspecialchars($beasiswa['jenis_beasiswa']); ?>
                                                </span>
                                            </td>

                                            <td class="text-center text-secondary fw-semibold">
                                                <i class="bi bi-calendar-event me-1 text-primary"></i>
                                                <?= date('d M Y', strtotime($beasiswa['deadline_pendaftaran'])); ?>
                                            </td>

                                            <td class="text-center">
                                                <?php if (isset($beasiswa['status']) && $beasiswa['status'] === 'tutup'): ?>
                                                    <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px;">Ditutup</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px;">Dibuka</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-end pe-4">
                                                <div class="d-inline-flex gap-1">
                                                    <button type="button" class="btn btn-outline-primary btn-sm fw-semibold" style="font-size: 12px; padding: 4px 10px; border-radius: 6px;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detailModalBeasiswa<?= $beasiswa['id']; ?>">
                                                        <i class="bi bi-eye me-1"></i> Detail
                                                    </button>

                                                    <a href="edit-beasiswa.php?id=<?= $beasiswa['id']; ?>" class="btn btn-light btn-sm fw-semibold" style="font-size: 12px; padding: 4px 10px; border-radius: 6px;">
                                                        Edit
                                                    </a>

                                                    <?php if (isset($beasiswa['status']) && $beasiswa['status'] === 'tutup'): ?>
                                                        <a href="../aksi/hapus-beasiswa-aksi.php?id=<?= $beasiswa['id']; ?>&status=tutup" class="btn btn-outline-danger btn-sm fw-semibold" style="font-size: 12px; padding: 4px 10px; border-radius: 6px;" onclick="return confirm('Apakah Anda yakin ingin menghapus informasi beasiswa ini?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm fw-semibold" style="font-size: 12px; padding: 4px 10px; border-radius: 6px; opacity: 0.4; cursor: not-allowed;" onclick="alert('Peringatan: Informasi beasiswa yang status registrasinya masih DIBUKA tidak dapat dihapus!')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="detailModalBeasiswa<?= $beasiswa['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                                            <span class="p-2 bg-primary-subtle text-primary rounded-3" style="font-size: 14px;"><i class="bi bi-info-circle-fill"></i></span>
                                                            Detail Informasi Beasiswa
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="row g-4">
                                                            <div class="col-12 bg-light p-3 rounded-3 mb-2">
                                                                <div class="d-flex flex-wrap gap-1 mb-1">
                                                                    <span class="badge bg-success text-uppercase" style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px;"><?= htmlspecialchars($beasiswa['jenis_beasiswa']); ?></span>
                                                                </div>
                                                                <h4 class="fw-bold text-dark mb-2"><?= htmlspecialchars($beasiswa['nama_beasiswa']); ?></h4>

                                                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                                                    <span><i class="bi bi-building me-1 text-secondary"></i> Penyelenggara: <strong><?= htmlspecialchars($beasiswa['penyelenggara']); ?></strong></span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-7">
                                                                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-file-text me-1 text-primary"></i> Deskripsi Beasiswa</h6>
                                                                <p class="text-secondary small mb-3" style="line-height: 1.6; text-align: justify;">
                                                                    <?= nl2br(htmlspecialchars($beasiswa['deskripsi'])); ?>
                                                                </p>
                                                                
                                                                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-gift me-1 text-primary"></i> Benefit</h6>
                                                                <p class="text-secondary small" style="line-height: 1.6; text-align: justify;">
                                                                    <?= nl2br(htmlspecialchars($beasiswa['benefit'])); ?>
                                                                </p>
                                                            </div>

                                                            <div class="col-md-5 border-start-md">
                                                                <div class="mb-4">
                                                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-list-check me-1 text-primary"></i> Persyaratan Utama</h6>
                                                                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                                                                        <?= nl2br(htmlspecialchars($beasiswa['persyaratan'])); ?>
                                                                    </p>
                                                                </div>

                                                                <div>
                                                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-download me-1 text-primary"></i> Dokumen Panduan</h6>
                                                                    <?php if (!empty($beasiswa['file_panduan'])): ?>
                                                                        <a href="../uploads/beasiswa/<?= $beasiswa['file_panduan']; ?>" class="btn btn-light border btn-sm w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold text-dark" download>
                                                                            <i class="bi bi-file-earmark-pdf text-danger fs-5"></i> Unduh Rulebook.pdf
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <div class="alert alert-light border text-center py-2 small text-muted mb-0">
                                                                            Tidak ada file panduan tersedia.
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-light btn-sm px-4 fw-semibold" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>
        </main>

        <?php include($base_path . 'includes/footer.php'); ?>
    </div>
</div>