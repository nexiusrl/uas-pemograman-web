<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama_unit = trim($_POST["nama_unit"] ?? "");
  $tipe = trim($_POST["tipe"] ?? "");
  $harga = trim($_POST["harga"] ?? "");
  $stok = trim($_POST["stok"] ?? "");
  $deskripsi = trim($_POST["deskripsi"] ?? "");

  // Cek input
  if (
    empty($nama_unit) ||
    empty($tipe) ||
    empty($harga) ||
    $stok === "" ||
    empty($deskripsi)
  ) {
    $error_message = "Semua kolom data unit wajib diisi.";
  } elseif (!is_numeric($harga) || $harga <= 0) {
    $error_message = "Harga harus berupa angka positif.";
  } elseif (!is_numeric($stok) || $stok < 0) {
    $error_message = "Stok harus berupa angka minimal 0.";
  } else {
    // Cek gambar
    if (
      !isset($_FILES["gambar"]) ||
      $_FILES["gambar"]["error"] !== UPLOAD_ERR_OK
    ) {
      $error_message = "Gambar unit wajib diunggah.";
    } else {
      $file = $_FILES["gambar"];
      $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
      $allowed_exts = ["jpg", "jpeg", "png"];
      $image_info = @getimagesize($file["tmp_name"]);

      if (!in_array($ext, $allowed_exts) || !$image_info) {
        $error_message =
          "Format gambar tidak valid. Gunakan file JPG, JPEG, atau PNG.";
      } elseif ($file["size"] > 2 * 1024 * 1024) {
        $error_message = "Ukuran gambar maksimal 2MB.";
      } else {
        // Pindahkan file ke folder img
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $new_filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        $upload_path = "../img/" . $new_filename;

        if (move_uploaded_file($file["tmp_name"], $upload_path)) {
          $stok_val = intval($stok);
          $status_val = $stok_val > 0 ? "Tersedia" : "Habis";

          $query =
            "INSERT INTO housing_units (nama_unit, tipe, harga, stok, status, gambar, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)";
          $stmt = mysqli_prepare($conn, $query);
          if ($stmt) {
            mysqli_stmt_bind_param(
              $stmt,
              "ssdisss",
              $nama_unit,
              $tipe,
              $harga,
              $stok_val,
              $status_val,
              $new_filename,
              $deskripsi,
            );
            if (mysqli_stmt_execute($stmt)) {
              mysqli_stmt_close($stmt);
              header("Location: dashboard.php?status=added");
              exit();
            } else {
              $error_message =
                "Gagal menyimpan ke database. Eror: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
          } else {
            $error_message = "Gagal menyiapkan query database.";
          }
        } else {
          $error_message = "Gagal mengunggah gambar ke server.";
        }
      }
    }
  }
}
?>
<!doctype html>
<html lang="id">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Tambah Unit Baru | Dashboard Admin</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
<!-- Custom CSS -->
<link rel="stylesheet" href="../css/style.css" />
</head>

<body>
<div class="container-fluid">
<div class="row">
<!-- Sidebar -->
<nav class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse" style="padding-top: 20px;">
<div class="position-sticky">
<h5 class="text-center mb-4 px-3 text-white" style="font-family: 'Outfit', sans-serif; font-weight: 800; letter-spacing: 1px;">
  SUMMARECON<br /><span style="font-weight:300; font-size: 0.9rem; color: var(--accent-color);">ADMIN PANEL</span>
</h5>
<ul class="nav flex-column mt-4">
  <li class="nav-item mb-2">
  <a class="nav-link active" href="dashboard.php">
  <i class="bi bi-house-door me-2"></i> Data Unit
  </a>
  </li>
  <li class="nav-item mb-2">
  <a class="nav-link" href="messages.php">
  <i class="bi bi-envelope me-2"></i> Pesan Masuk
  </a>
  </li>
  <li class="nav-item mb-2">
  <a class="nav-link" href="../index.php" target="_blank">
  <i class="bi bi-globe me-2"></i> Lihat Website
  </a>
  </li>
  <li class="nav-item mt-5">
  <a class="nav-link text-danger" href="../logout.php" style="color: #ff6b6b !important;">
  <i class="bi bi-box-arrow-left me-2"></i> Keluar (Logout)
  </a>
  </li>
</ul>
</div>
</nav>

<!-- Main Content -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4" style="background-color: var(--bg-gray); min-height: 100vh;">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom border-secondary-subtle">
<h1 class="h2" style="font-weight: 700;">Tambah Unit Properti</h1>
<a href="dashboard.php" class="btn btn-outline-dark py-2 px-4 rounded-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
  <i class="bi bi-arrow-left me-1"></i> Kembali ke List
</a>
</div>

<?php if (!empty($error_message)): ?>
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
  <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars(
    $error_message,
  ); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Form Section -->
<div class="row">
<div class="col-lg-8">
  <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
  <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4">
  <h5 class="mb-0" style="font-weight: 600;">Form Detail Unit</h5>
  </div>
  <div class="card-body px-4 pb-4">
  <form id="addUnitForm" action="add.php" method="POST" enctype="multipart/form-data">
  <div class="row">
  <div class="col-md-6 mb-4">
    <label for="nama_unit" class="form-label small fw-bold text-uppercase text-muted">Nama Unit</label>
    <input type="text" class="form-control rounded-3" id="nama_unit" name="nama_unit" placeholder="Contoh: Onyx Residence" style="padding: 10px 15px;" />
    <div class="invalid-feedback">Nama unit harus diisi.</div>
  </div>
  <div class="col-md-6 mb-4">
    <label for="tipe" class="form-label small fw-bold text-uppercase text-muted">Tipe / Style</label>
    <input type="text" class="form-control rounded-3" id="tipe" name="tipe" placeholder="Contoh: Contemporary Tropical" style="padding: 10px 15px;" />
    <div class="invalid-feedback">Tipe unit harus diisi.</div>
  </div>
  </div>
  <div class="row">
  <div class="col-md-6 mb-4">
    <label for="harga" class="form-label small fw-bold text-uppercase text-muted">Harga (Rp)</label>
    <input type="number" class="form-control rounded-3" id="harga" name="harga" placeholder="Masukkan harga angka" style="padding: 10px 15px;" />
    <div class="invalid-feedback">Harga harus berupa angka positif.</div>
  </div>
  <div class="col-md-6 mb-4">
    <label for="stok" class="form-label small fw-bold text-uppercase text-muted">Stok Unit</label>
    <input type="number" class="form-control rounded-3" id="stok" name="stok" placeholder="Masukkan jumlah stok" min="0" value="0" style="padding: 10px 15px;" />
    <div class="invalid-feedback">Stok harus diisi dengan angka minimal 0.</div>
  </div>
  </div>
  <div class="mb-4">
  <label for="gambar" class="form-label small fw-bold text-uppercase text-muted">Gambar Unit</label>
  <input type="file" class="form-control rounded-3" id="gambar" name="gambar" accept="image/png, image/jpeg, image/jpg" style="padding: 10px 15px;" />
  <div class="invalid-feedback">Silakan pilih file gambar (JPG/PNG).</div>
  <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">* File minimal tipe JPG, JPEG, atau PNG. Maksimal ukuran 2MB.</small>
  </div>
  <div class="mb-4">
  <label for="deskripsi" class="form-label small fw-bold text-uppercase text-muted">Deskripsi / Spesifikasi</label>
  <textarea class="form-control rounded-3" id="deskripsi" name="deskripsi" rows="4" placeholder="Tuliskan spesifikasi unit secara lengkap" style="padding: 10px 15px;"></textarea>
  <div class="invalid-feedback">Deskripsi unit harus diisi.</div>
  </div>
  <button type="submit" class="btn btn-dark-minimal py-3 px-5 rounded-3 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">
  Simpan Unit Baru
  </button>
  </form>
  </div>
  </div>
</div>

<!-- Helper sidebar info -->
<div class="col-lg-4">
  <div class="card shadow-sm border-0" style="border-radius: 12px; border-top: 4px solid var(--accent-color) !important;">
  <div class="card-body p-4">
  <h6 class="fw-bold mb-3" style="font-family: 'Outfit', sans-serif;">Panduan Input Gambar</h6>
  <p class="small text-muted mb-3">
  Pastikan gambar unit memiliki resolusi lanskap yang seimbang (direkomendasikan rasio 16:9 atau 4:3) untuk hasil tampilan visual galeri landing page terbaik.
  </p>
  <p class="small text-muted mb-0">
  Semua gambar akan diletakkan di direktori utama `img/` dan namanya akan diubah secara otomatis oleh sistem agar tidak terjadi bentrokan file.
  </p>
  </div>
  </div>
</div>
</div>
</main>
</div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="../js/main.js"></script>
</body>

</html>
