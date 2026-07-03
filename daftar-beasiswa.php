<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
include($base_path . 'config.php');
// Include file model beasiswa di sini
include($base_path . 'models/beasiswa-model.php');

$result = get_all_beasiswa_aktif_mhs($conn);

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
                        <span class="page-icon"><i class="bi bi-award-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Eksplorasi Program</p>
                            <h1 class="h3 mb-1">Daftar Beasiswa</h1>
                            <p class="text-muted mb-0">Temukan dan ikuti berbagai beasiswa untuk mengembangkan potensimu.</p>
                        </div>
                    </div>
                </div>

                <div class="search-wrapper col-12 col-md-4">
                    <form action="" method="GET" class="input-group" id="formSearchBeasiswa">
<input type="text" name="search" id="searchBeasiswa" class="form-control rounded-start-pill ps-3" placeholder="Cari beasiswa..." value="">                        <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-4" id="beasiswaContainer">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($beasiswa = mysqli_fetch_assoc($result)): ?>

                        <div class="col-12 col-md-6 col-xl-4 beasiswa-item"
                            data-nama="<?= strtolower($beasiswa['nama_beasiswa']); ?>"
                            data-penyelenggara="<?= strtolower($beasiswa['penyelenggara'] ?? ''); ?>">

                            <div class="card h-100 border-0 shadow-sm custom-card overflow-hidden">
                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="mb-3">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small text-capitalize">
                                                <i class="bi bi-wallet2 me-1"></i><?= htmlspecialchars($beasiswa['jenis_beasiswa'] ?? 'parsial'); ?>
                                            </span>
                                        </div>

                                        <h3 class="h5 card-title fw-bold text-dark mb-1 text-truncate-2">
                                            <?= htmlspecialchars($beasiswa['nama_beasiswa']); ?>
                                        </h3>
                                        <p class="text-secondary small mb-3">
                                            <i class="bi bi-building me-1"></i> <?= htmlspecialchars($beasiswa['penyelenggara'] ?? 'Universitas ScholarHub'); ?>
                                        </p>

                                        <p class="text-muted small mb-4 text-truncate-3">
                                            <?= htmlspecialchars($beasiswa['deskripsi'] ?? 'Tidak ada deskripsi.'); ?>
                                        </p>
                                    </div>

                                    <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                                        <small class="text-muted d-flex align-items-center gap-1">
                                            <i class="bi bi-calendar-event"></i>
                                            Tenggat: <?= !empty($beasiswa['deadline_pendaftaran']) ? date('d M Y', strtotime($beasiswa['deadline_pendaftaran'])) : 'N/A'; ?>
                                        </small>

                                        <a href="detail-beasiswa.php?id=<?= $beasiswa['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium">
                                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <span class="p-3 bg-light rounded-circle d-inline-block text-muted h2"><i class="bi bi-folder-x"></i></span>
                        </div>
                        <h3 class="h5 fw-bold text-secondary">Belum Ada Program Beasiswa</h3>
                        <p class="text-muted small">Saat ini tidak ada program beasiswa aktif yang sedang dibuka.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php include($base_path . 'includes/footer.php'); ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchForm = document.getElementById('formSearchBeasiswa');
    const searchInput = document.getElementById('searchBeasiswa');
    const beasiswaItems = document.querySelectorAll('.beasiswa-item');

    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();

            beasiswaItems.forEach(item => {
                const namaBeasiswa = item.getAttribute('data-nama') || "";
                const penyelenggara = item.getAttribute('data-penyelenggara') || "";
                
                // Filter real-time berdasarkan input
                if (namaBeasiswa.includes(searchText) || penyelenggara.includes(searchText)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        });
    }
});
</script>