<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/pengajuan-model.php'); 

$id_mahasiswa = $_SESSION['user_id'];

$pengajuan_lomba = get_history_lomba($conn, $id_mahasiswa);
$pengajuan_beasiswa = get_history_beasiswa($conn, $id_mahasiswa);
$total_lomba = get_total_pengajuan($conn, 'pendaftaran_lomba', $id_mahasiswa);
$total_beasiswa = get_total_pengajuan($conn, 'pendaftaran_beasiswa', $id_mahasiswa);

include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="admin-main">
    <?php include($base_path . 'includes/navbar.php'); ?>

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-dark mb-0">Riwayat Pengajuan Saya</h1>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary px-3 py-2">Total Lomba: <?= $total_lomba; ?></span>
                    <span class="badge bg-info px-3 py-2">Total Beasiswa: <?= $total_beasiswa; ?></span>
                </div>
            </div>

            <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#lomba">Kompetisi Lomba</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#beasiswa">Beasiswa</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="lomba">
                    <div class="panel shadow-sm border-0 p-0 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Nama Lomba</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($pengajuan_lomba) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($pengajuan_lomba)): ?>
                                    <tr>
                                        <td class="px-4 fw-semibold"><?= htmlspecialchars($row['judul']); ?></td>
                                        <td><?= date('d M Y', strtotime($row['tanggal_daftar'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?= ($row['status'] == 'diterima') ? 'success' : 'warning'; ?>">
                                                <?= ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada riwayat lomba.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="beasiswa">
                    <div class="panel shadow-sm border-0 p-0 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Nama Beasiswa</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($pengajuan_beasiswa) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($pengajuan_beasiswa)): ?>
                                    <tr>
                                        <td class="px-4 fw-semibold"><?= htmlspecialchars($row['nama_beasiswa']); ?></td>
                                        <td><?= date('d M Y', strtotime($row['tanggal_daftar'])); ?></td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada riwayat beasiswa.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include($base_path . 'includes/footer.php'); ?>
</div>