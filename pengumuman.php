<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');

$query_pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY created_at DESC");

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
                    <div class="page-heading-copy d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
                        <div class="d-flex align-items-center">
                            <span class="page-icon"><i class="bi bi-megaphone-fill" aria-hidden="true"></i></span>
                            <div>
                                <p class="eyebrow mb-1">Informasi Kampus</p>
                                <h1 class="h3 mb-1">Papan Pengumuman</h1>
                                <p class="text-muted mb-0">Informasi terbaru mengenai program kompetisi, beasiswa, dan pengumuman umum akademis.</p>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="tambah-pengumuman.php" class="btn btn-primary fw-bold px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2 text-nowrap" style="border-radius: 10px; background: #1a56db; border: none;">
                                <i class="bi bi-plus-lg fs-6"></i> Tambah Pengumuman
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-4">
                    <?php if (mysqli_num_rows($query_pengumuman) == 0): ?>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm p-5 text-center text-muted" style="border-radius: 16px;">
                                <i class="bi bi-megaphone fs-2 mb-2 text-secondary"></i>
                                <p class="mb-0">Belum ada pengumuman resmi yang diterbitkan saat ini.</p>
                            </div>
                        </div>
                        <?php else: while ($row = mysqli_fetch_assoc($query_pengumuman)): ?>
                            <div class="col-12">
                                <div class="card border-0 shadow-sm text-dark" style="border-radius: 16px;">
                                    <div class="card-body p-4">

                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                            <div>
                                                <?php
                                                $kategori = strtolower($row['kategori']);
                                                if ($kategori === 'lomba') {
                                                    echo '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 fw-bold" style="border-radius: 8px;"><i class="bi bi-trophy-fill me-1"></i> Info Lomba</span>';
                                                } elseif ($kategori === 'beasiswa') {
                                                    echo '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fw-bold" style="border-radius: 8px;"><i class="bi bi-award-fill me-1"></i> Info Beasiswa</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1.5 fw-bold" style="border-radius: 8px;"><i class="bi bi-info-circle-fill me-1"></i> Umum</span>';
                                                }
                                                ?>
                                            </div>

                                            <div class="d-flex align-items-center gap-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y, H:i', strtotime($row['created_at'])); ?> WIB
                                                </small>

                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                                    <a href="../aksi/hapus-pengumuman-aksi.php?id=<?= $row['id']; ?>"
                                                        class="btn btn-link text-danger p-0 border-0"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');"
                                                        title="Hapus Pengumuman">
                                                        <i class="bi bi-trash3-fill fs-5"></i>
                                                    </a> <?php endif; ?>
                                            </div>
                                        </div>

                                        <h4 class="fw-bold mb-2 text-dark" style="letter-spacing: -0.3px;">
                                            <?= htmlspecialchars($row['judul']); ?>
                                        </h4>

                                        <hr class="my-3" style="opacity: 0.06;">

                                        <div class="text-secondary lh-base" style="font-size: 0.95rem; white-space: pre-line;">
                                            <?= htmlspecialchars($row['isi']); ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                    <?php endwhile;
                    endif; ?>
                </div>

            </div>
        </main>

        <?php include($base_path . 'includes/footer.php'); ?>
    </div>
</div>