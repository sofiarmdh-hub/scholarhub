<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
include($base_path . 'models/lomba-model.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query_lomba = get_all_lomba_aktif_mhs($conn);

include($base_path . 'includes/header.php');
include($base_path . 'includes/sidebar.php');
?>

<div class="admin-main">
    <?php include($base_path . 'includes/navbar.php'); ?>

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                <div class="page-heading">
                    <div class="d-flex align-items-center gap-3">
                        <span class="page-icon"><i class="bi bi-trophy-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Eksplorasi Program</p>
                            <h1 class="h3 mb-1">Daftar Kompetisi & Lomba</h1>
                            <p class="text-muted mb-0">Temukan dan ikuti berbagai kompetisi aktif untuk mengembangkan potensimu.</p>
                        </div>
                    </div>
                </div>
                <div class="search-wrapper col-12 col-md-4">
                    <form action="" method="GET" class="input-group">
                        <input type="text" name="search" id="searchLomba" class="form-control rounded-start-pill ps-3" placeholder="Cari kompetisi..." value="<?= htmlspecialchars($search); ?>"> <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-3" id="lombaContainer">
                <?php if (empty($query_lomba)): ?>
                    <div class="col-12">
                        <div class="panel p-5 text-center text-muted">
                            <i class="bi bi-archive h2 mb-2 d-block text-secondary"></i>
                            <p class="mb-0">Saat ini belum ada kompetisi atau lomba yang dibuka.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($query_lomba as $row): ?>
                        <div class="col-12 col-md-6 col-xl-4 lomba-item" data-judul="<?= strtolower($row['judul']); ?>" data-kategori="<?= strtolower($row['kategori'] ?? ''); ?>">
                            <div class="panel h-100 d-flex flex-column justify-content-between border-0 shadow-sm">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small">
                                            <i class="bi bi-tag-fill me-1"></i><?= htmlspecialchars($row['kategori'] ?? 'Umum'); ?>
                                        </span>
                                    </div>

                                    <h3 class="h5 mb-2 fw-semibold text-dark"><?= htmlspecialchars($row['judul']); ?></h3>
                                    <p class="text-muted small mb-4">
                                        <?= htmlspecialchars(mb_strimwidth($row['deskripsi'], 0, 140, "...")); ?>
                                    </p>
                                </div>

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                    <span class="text-muted fw-medium small">
                                        <i class="bi bi-calendar-event me-1"></i>Deadline: <?= !empty($row['deadline_pendaftaran']) ? date('d M Y', strtotime($row['deadline_pendaftaran'])) : 'N/A'; ?>
                                    </span>
                                    <a href="detail-lomba.php?id=<?= $row['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium">
                                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php include($base_path . 'includes/footer.php'); ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchLomba');
        const items = document.querySelectorAll('.lomba-item');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();

                items.forEach(item => {
                    const judul = item.getAttribute('data-judul') || "";
                    
                    if (judul.includes(searchText)) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
            });
        }
    });
</script>