<?php
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin/dashboard.php");
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error_message = 'Username dan password wajib diisi.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['username'] = $user['username'];
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error_message = 'Username atau password salah.';
            }
        } catch (\PDOException $e) {
            $error_message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Staf | Summarecon Mutiara Makassar</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      background-color: var(--bg-light);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .login-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 0;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      border: none;
      border-radius: 0;
      padding: 30px;
    }
  </style>
</head>

<body>
  <!-- Simple Header -->
  <nav class="navbar navbar-light bg-white border-bottom">
    <div class="container justify-content-center">
      <a class="navbar-brand" href="index.php">Summarecon Mutiara Makassar</a>
    </div>
  </nav>

  <!-- Login Form Section -->
  <main class="login-container">
    <div class="card login-card shadow-sm border">
      <div class="card-body">
        <h3 class="text-center mb-4">Akses Staf</h3>
        <p class="text-center text-muted small mb-4">
          Silakan masukkan kredensial Anda untuk mengelola inventaris.
        </p>

        <?php if (!empty($error_message)): ?>
          <div class="alert alert-danger alert-dismissible fade show rounded-3 small border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form id="loginForm" action="login.php" method="POST">
          <div class="mb-3">
            <label for="username" class="form-label small text-uppercase fw-bold">Username</label>
            <input type="text" class="form-control rounded-3" id="username" name="username"
              placeholder="Masukkan username">
            <div class="invalid-feedback">Username harus diisi.</div>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label small text-uppercase fw-bold">Password</label>
            <input type="password" class="form-control rounded-3" id="password" name="password"
              placeholder="Masukkan password">
            <div class="invalid-feedback">Password harus diisi.</div>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-dark-minimal w-100 py-3 rounded-3 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">
              Masuk
            </button>
          </div>
        </form>

        <div class="mt-4 text-center">
          <a href="index.php" class="text-decoration-none text-muted small">&larr; Kembali ke Beranda</a>
        </div>
      </div>
    </div>
  </main>

  <!-- Simple Footer -->
  <footer class="py-3 mt-auto" style="background-color: var(--primary-color);">
    <div class="container text-center">
      <p class="small text-secondary mb-0">
        &copy; 2026 Summarecon Mutiara Makassar.
      </p>
    </div>
  </footer>
  
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="js/main.js"></script>
</body>

</html>
