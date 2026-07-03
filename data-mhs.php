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
include($base_path . 'models/mahasiswa-model.php');

$list_mahasiswa = get_all_mahasiswa($conn);

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
                        <span class="page-icon"><i class="bi bi-people-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Akademik</p>
                            <h1 class="h3 mb-1">Data Mahasiswa</h1>
                            <p class="text-muted mb-0">Kelola informasi data akademik dan akun mahasiswa ScholarHub.</p>
                        </div>
                    </div>
                </div>

                <section class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>Daftar Mahasiswa</span></h2>
                            <p class="text-muted mb-0">Gunakan fitur cari untuk menyaring records data mahasiswa.</p>
                        </div>
                        <input class="form-control form-control-sm table-search" type="search" placeholder="Cari mahasiswa..." data-table-search="mahasiswaTable" aria-label="Cari mahasiswa">
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="mahasiswaTable" data-searchable-table>
                            <thead class="bg-light text-secondary" style="font-size: 13px; font-weight: 600;">
                                <tr>
                                    <th class="text-center py-3" style="width: 15%;">NPM</th>
                                    <th class="text-center py-3" style="width: 30%;">Nama Mahasiswa</th>
                                    <th class="text-center py-3" style="width: 25%;">Prodi / Fakultas</th>
                                    <th class="text-center py-3" style="width: 10%;">Semester</th>
                                    <th class="text-center py-3" style="width: 10%;">Status Akun</th>
                                    <th class="text-end pe-4 py-3" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <?php if (empty($list_mahasiswa)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">Belum ada data mahasiswa terdaftar.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($list_mahasiswa as $mhs): ?>
                                        <tr>
                                            <td class="text-center fw-semibold text-dark"><?= htmlspecialchars($mhs['npm']); ?></td>

                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 11px; flex-shrink: 0;">
                                                        <?= strtoupper(substr($mhs['nama'], 0, 2)); ?>
                                                    </div>
                                                    <div class="text-start">
                                                        <div class="fw-bold text-dark mb-0" style="line-height: 1.2;"><?= htmlspecialchars($mhs['nama']); ?></div>
                                                        <small class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($mhs['email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-center text-secondary">
                                                <?= htmlspecialchars($mhs['prodi']); ?> <span class="text-muted small">/ <?= htmlspecialchars($mhs['fakultas']); ?></span>
                                            </td>

                                            <td class="text-center text-secondary">Semester <?= htmlspecialchars($mhs['semester']); ?></td>

                                            <td class="text-center">
                                                <?php if (isset($mhs['status']) && $mhs['status'] === 'nonaktif'): ?>
                                                    <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px;">Nonaktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success px-2.5 py-1.5 fw-semibold" style="font-size: 11px; border-radius: 6px;">Aktif</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-end pe-4">
                                                <?php if (isset($mhs['status']) && $mhs['status'] === 'nonaktif'): ?>
                                                    <a href="../aksi/hapus-mhs-aksi.php?id=<?= $mhs['id']; ?>&status=nonaktif" class="btn btn-outline-danger btn-sm fw-semibold" style="font-size: 12px; padding: 4px 12px; border-radius: 6px;" onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini secara permanen?')">
                                                        <i class="bi bi-trash me-1"></i> Hapus
                                                    </a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm fw-semibold" style="font-size: 12px; padding: 4px 12px; border-radius: 6px; opacity: 0.4; cursor: not-allowed;" onclick="alert('Peringatan: Akun mahasiswa yang masih AKTIF tidak dapat dihapus! Silakan nonaktifkan akun terlebih dahulu.')">
                                                        <i class="bi bi-trash me-1"></i> Hapus
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
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