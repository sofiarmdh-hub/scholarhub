<?php
session_start();
if (isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ScholarHub authentication page">
    <title>Login | ScholarHub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="auth-body">
    <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
    </button>
    <main class="auth-page">
        <section class="auth-card">

            <a class="auth-brand"><span class="brand-icon";"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i></span><span><strong>ScholarHub</strong></span></a>

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') : ?>
                <div class="alert alert-danger text-center py-2 small" role="alert">
                    Email atau Password salah!
                </div>
            <?php endif; ?>

            <form action="aksi/proses-login.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-4">
                    <p class="eyebrow mb-1">Secure Access</p>
                    <h1 class="h3 mb-1">Login</h1>
                    <p class="text-muted mb-0">Connect to ScholarHub.</p>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="loginEmail">Email address</label>
                    <input class="form-control" id="loginEmail" type="email" name="email" placeholder="name@example.com" required>
                    <div class="invalid-feedback">Enter a valid email.</div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="loginPassword">Password</label>
                    </div>
                    <input class="form-control" id="loginPassword" type="password" name="password" minlength="6" placeholder="••••••••" required>
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>
                <button class="btn btn-primary w-100" type="submit" name="submit"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Sign In</button>
            </form>
        </section>
    </main>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>