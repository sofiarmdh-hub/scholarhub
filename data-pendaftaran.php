<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');

include($base_path . 'models/beasiswa-model.php');
include($base_path . 'models/lomba-model.php');

$pendaftaran_beasiswa = get_all_pendaftaran_beasiswa($conn);
$pendaftaran_lomba = get_all_pendaftaran_lomba($conn);

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
                        <span class="page-icon"><i class="bi bi-clipboard-check-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Manajemen Informasi</p>
                            <h1 class="h3 mb-1">Data Pendaftaran Mahasiswa</h1>
                            <p class="text-muted mb-0">Kelola, periksa, dan verifikasi berkas masuk untuk program Beasiswa dan Kompetisi.</p>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-4 border-bottom" id="pendaftaranTabs" role="tablist" style="gap: 8px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold px-4 py-2.5 text-primary" id="lomba-tab" data-bs-toggle="tab" data-bs-target="#lomba-panel" type="button" role="tab" aria-controls="lomba-panel" aria-selected="true" style="border-radius: 8px 8px 0 0;">
                            <i class="bi bi-trophy me-2"></i>Pendaftaran Lomba
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4 py-2.5 text-secondary" id="beasiswa-tab" data-bs-toggle="tab" data-bs-target="#beasiswa-panel" type="button" role="tab" aria-controls="beasiswa-panel" aria-selected="false" style="border-radius: 8px 8px 0 0;">
                            <i class="bi bi-award me-2"></i>Pendaftaran Beasiswa
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pendaftaranTabsContent">

                    <div class="tab-pane fade show active" id="lomba-panel" role="tabpanel" aria-labelledby="lomba-tab">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-secondary" style="border-bottom: 2px solid rgba(0,0,0,0.04);">
                                        <tr>
                                            <th class="ps-4 py-3" style="width: 70px;">No</th>
                                            <th class="py-3">Mahasiswa / Pendaftar</th>
                                            <th class="py-3">Kompetisi / Lomba</th>
                                            <th class="py-3">Tanggal Apply</th>
                                            <th class="py-3">Status Berkas</th>
                                            <th class="text-end pe-4 py-3" style="width: 140px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($pendaftaran_lomba)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>Belum ada pendaftaran lomba masuk.
                                                </td>
                                            </tr>
                                        <?php else: $no = 1; foreach ($pendaftaran_lomba as $row): ?>
                                            <tr style="border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                <td class="ps-4 fw-semibold text-secondary"><?= $no++; ?></td>
                                                <td>
                                                    <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($row['nama_mahasiswa']); ?></div>
                                                    <small class="text-muted d-block mt-0.5"><?= htmlspecialchars($row['npm']); ?> • <?= htmlspecialchars($row['prodi']); ?></small>
                                                </td>
                                                <td><span class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_lomba'] ?? $row['judul']); ?></span></td>
                                                <td><small class="text-secondary"><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($row['tanggal_daftar'])); ?></small></td>
                                                <td>
                                                    <?php
                                                    $status = strtolower($row['status'] ?? 'diajukan');
                                                    if ($status === 'diterima' || $status === 'valid') {
                                                        echo '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-check-circle-fill me-1"></i> Diterima</span>';
                                                    } elseif ($status === 'ditolak') {
                                                        echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-x-circle-fill me-1"></i> Ditolak</span>';
                                                    } elseif ($status === 'diproses') {
                                                        echo '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-hourglass-split me-1"></i> Diproses</span>';
                                                    } else {
                                                        echo '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-file-earmark-arrow-up-fill me-1"></i> Diajukan</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="periksa-pendaftaran-lomba.php?id=<?= $row['id']; ?>" class="btn btn-light btn-sm fw-bold border" style="border-radius: 8px; padding: 6px 12px; font-size: 0.85rem;">
                                                        <i class="bi bi-shield-check me-1 text-primary"></i> Periksa
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="beasiswa-panel" role="tabpanel" aria-labelledby="beasiswa-tab">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-secondary" style="border-bottom: 2px solid rgba(0,0,0,0.04);">
                                        <tr>
                                            <th class="ps-4 py-3" style="width: 70px;">No</th>
                                            <th class="py-3">Mahasiswa / Pendaftar</th>
                                            <th class="py-3">Program Beasiswa</th>
                                            <th class="py-3">Tanggal Apply</th>
                                            <th class="py-3">Status Berkas</th>
                                            <th class="text-end pe-4 py-3" style="width: 140px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($pendaftaran_beasiswa)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>Belum ada pendaftaran beasiswa masuk.
                                                </td>
                                            </tr>
                                        <?php else: $no = 1; foreach ($pendaftaran_beasiswa as $row): ?>
                                            <tr style="border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                <td class="ps-4 fw-semibold text-secondary"><?= $no++; ?></td>
                                                <td>
                                                    <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($row['nama_mahasiswa']); ?></div>
                                                    <small class="text-muted d-block mt-0.5"><?= htmlspecialchars($row['npm']); ?> • <?= htmlspecialchars($row['prodi']); ?></small>
                                                </td>
                                                <td><span class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_beasiswa']); ?></span></td>
                                                <td><small class="text-secondary"><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($row['tanggal_daftar'])); ?></small></td>
                                                <td>
                                                    <?php
                                                    $status = strtolower($row['status'] ?? 'diajukan');
                                                    if ($status === 'diterima' || $status === 'valid') {
                                                        echo '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-check-circle-fill me-1"></i> Diterima</span>';
                                                    } elseif ($status === 'ditolak') {
                                                        echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-x-circle-fill me-1"></i> Ditolak</span>';
                                                    } elseif ($status === 'diproses') {
                                                        echo '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-hourglass-split me-1"></i> Diproses</span>';
                                                    } else {
                                                        echo '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius:6px; font-size: 0.825rem;"><i class="bi bi-file-earmark-arrow-up-fill me-1"></i> Diajukan</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="periksa-pendaftaran-beasiswa.php?id=<?= $row['id']; ?>" class="btn btn-light btn-sm fw-bold border" style="border-radius: 8px; padding: 6px 12px; font-size: 0.85rem;">
                                                        <i class="bi bi-shield-check me-1 text-primary"></i> Periksa
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </main>

        <?php include($base_path . 'includes/footer.php'); ?>
    </div>
</div>

<script>
    document.querySelectorAll('#pendaftaranTabs button').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('#pendaftaranTabs button').forEach(btn => {
                btn.classList.remove('text-primary', 'active');
                btn.classList.add('text-secondary');
            });
            this.classList.remove('text-secondary');
            this.classList.add('text-primary', 'active');
        });
    });
</script>