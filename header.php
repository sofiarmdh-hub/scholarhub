<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="adminHMD professional admin dashboard template">
    <title>ScholarHub</title>

    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <script>
            window.adminHMDUser = {
                name: "<?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'User ScholarHub'); ?>",
                workspace: "<?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'Workspace Admin' : 'Portal Mahasiswa'; ?>",
            };
        </script>