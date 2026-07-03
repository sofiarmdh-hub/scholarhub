<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$base_path = "../";
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
                    <a href="pengumuman.php" class="text-decoration-none text-secondary small fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Papan Pengumuman
                    </a>
                </div>

                <div class="page-heading mb-4">
                    <div class="page-heading-copy">
                        <span class="page-icon"><i class="bi bi-megaphone-fill" aria-hidden="true"></i></span>
                        <div>
                            <p class="eyebrow mb-1">Manajemen Informasi</p>
                            <h1 class="h3 mb-1">Buat Pengumuman Baru</h1>
                            <p class="text-muted mb-0">Publikasikan informasi seputar kompetisi, beasiswa, atau info umum ke mahasiswa.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                
                                <form action="../aksi/tambah-pengumuman-aksi.php" method="POST">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Judul Pengumuman</label>
                                        <input type="text" class="form-control" name="judul" placeholder="Contoh: Pendaftaran Beasiswa Dikti Semester Genap Telah Dibuka!" style="border-radius: 8px; padding: 10px 12px;" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-secondary">Kategori Pengumuman</label>
                                        <select class="form-select" name="kategori" style="border-radius: 8px; padding: 10px 12px;" required>
                                            <option value="" disabled selected>-- Pilih Kategori --</option>
                                            <option value="lomba">Info Lomba</option>
                                            <option value="beasiswa">Info Beasiswa</option>
                                            <option value="umum">Umum</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-secondary">Isi / Deskripsi Pengumuman</label>
                                        <textarea class="form-control" name="isi" rows="8" placeholder="Tulis rincian informasi, syarat, link eksternal, atau detail pengumuman di sini..." style="border-radius: 8px; font-size: 0.95rem;" required></textarea>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="pengumuman.php" class="btn btn-light fw-bold px-4" style="border-radius: 8px;">Batal</a>
                                        <button type="submit" class="btn btn-primary fw-bold px-4" style="border-radius: 8px; background: #1a56db; border: none;">
                                            <i class="bi bi-send me-1"></i> Terbitkan Pengumuman
                                        </button>
                                    </div>

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