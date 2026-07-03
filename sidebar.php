<?php
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
    <div class="sidebar-header">
        <a class="brand-mark" href="<?php echo $base_path; ?>index.php" aria-label="ScholarHub dashboard">
            <span class="brand-icon"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i></span>
            <span class="brand-copy">
                <span class="brand-title">ScholarHub</span>
                <span class="brand-subtitle">Portal</span>
            </span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <?php if ($role === 'mahasiswa') : ?>
            <a class="nav-link <?php echo ($current_page == 'dashboard-mhs.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/dashboard-mhs.php">
                <span class="nav-icon"><i class="bi bi-house-door" aria-hidden="true"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'daftar-lomba.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/daftar-lomba.php">
                <span class="nav-icon"><i class="bi bi-trophy" aria-hidden="true"></i></span>
                <span class="nav-text">Daftar Lomba</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'daftar-beasiswa.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/daftar-beasiswa.php">
                <span class="nav-icon"><i class="bi bi-award" aria-hidden="true"></i></span>
                <span class="nav-text">Daftar Beasiswa</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'pengajuan-mhs.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/pengajuan-mhs.php">
                <span class="nav-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
                <span class="nav-text">Pengajuan Saya</span>
            </a>

        <?php elseif ($role === 'admin') : ?>
            <a class="nav-link <?php echo ($current_page == 'dashboard-admin.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/dashboard-admin.php">
                <span class="nav-icon"><i class="bi bi-house-door" aria-hidden="true"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'data-mhs.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/data-mhs.php">
                <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                <span class="nav-text">Data Mahasiswa</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'data-lomba.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/data-lomba.php">
                <span class="nav-icon"><i class="bi bi-trophy" aria-hidden="true"></i></span>
                <span class="nav-text">Data Lomba</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'data-beasiswa.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/data-beasiswa.php">
                <span class="nav-icon"><i class="bi bi-award" aria-hidden="true"></i></span>
                <span class="nav-text">Data Beasiswa</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'data-pendaftaran.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/data-pendaftaran.php">
                <span class="nav-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
                <span class="nav-text">Data Pendaftaran</span>
            </a>
            <a class="nav-link <?php echo ($current_page == 'pengumuman.php' || $current_page == 'tambah-pengumuman.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>pages/pengumuman.php">
                <span class="nav-icon"><i class="bi bi-megaphone" aria-hidden="true"></i></span>
                <span class="nav-text">Pengumuman</span>
            </a>

        <?php else : ?>
            <a class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>login.php">
                <span class="nav-icon"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i></span>
                <span class="nav-text">Login</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>