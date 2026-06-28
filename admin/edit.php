<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$id = $_GET['id'] ?? '';
if (empty($id)) {
header("Location: dashboard.php");
exit();
}

// Ambil data unit berdasarkan ID
$query = "SELECT * FROM housing_units WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$unit = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$unit) {
  header("Location: dashboard.php");
  exit();
}
} else {
die("Kesalahan sistem: Gagal menyiapkan kueri.");
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$nama_unit = trim($_POST['nama_unit'] ?? '');
$tipe = trim($_POST['tipe'] ?? '');
$harga = trim($_POST['harga'] ?? '');
$status = trim($_POST['status'] ?? 'Tersedia');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$gambar_nama = $unit['gambar']; // default pakai gambar lama

// Cek input
if (empty($nama_unit) || empty($tipe) || empty($harga) || empty($deskripsi)) {
  $error_message = 'Semua kolom data unit wajib diisi.';
} elseif (!is_numeric($harga) || $harga <= 0) {
  $error_message = 'Harga harus berupa angka positif.';
} else {
  // Cek apakah ada unggahan gambar baru
  $gambar_diunggah = isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK;
  $upload_ok = true;

  if ($gambar_diunggah) {
    $file = $_FILES['gambar'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
    $error_message = 'Format gambar tidak valid. Gunakan file JPG, JPEG, atau PNG.';
    $upload_ok = false;
    } elseif ($file['size'] > 2 * 1024 * 1024) {
    $error_message = 'Ukuran gambar maksimal 2MB.';
    $upload_ok = false;
    } else {
    // Pindahkan file ke folder img
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $upload_path = '../img/' . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
    $gambar_nama = $new_filename;
    // Hapus gambar lama jika ada dan bukan gambar default seed
    $old_file = '../img/' . $unit['gambar'];
    if (file_exists($old_file) && !in_array($unit['gambar'], ['onyx.jpg', 'green-crystal.jpg', 'the-morizen.jpg'])) {
      @unlink($old_file);
    }
    } else {
    $error_message = 'Gagal mengunggah gambar ke server.';
    $upload_ok = false;
    }
    }
  }

  if ($upload_ok) {
    $query = "UPDATE housing_units SET nama_unit = ?, tipe = ?, harga = ?, status = ?, gambar = ?, deskripsi = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssdsssi", $nama_unit, $tipe, $harga, $status, $gambar_nama, $deskripsi, $id);
    if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: dashboard.php?status=updated");
    exit();
    } else {
    $error_message = 'Gagal memperbarui database. Eror: ' . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    } else {
    $error_message = 'Gagal menyiapkan query database.';
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
<title>Edit Unit | Dashboard Admin</title>
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
  <h1 class="h2" style="font-weight: 700;">Edit Unit Properti</h1>
  <a href="dashboard.php" class="btn btn-outline-dark py-2 px-4 rounded-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke List
  </a>
  </div>

  <?php if (!empty($error_message)): ?>
  <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error_message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <!-- Form Section -->
  <div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4">
    <h5 class="mb-0" style="font-weight: 600;">Edit Detail Unit</h5>
    </div>
    <div class="card-body px-4 pb-4">
    <form id="editUnitForm" action="edit.php?id=<?php echo $unit['id']; ?>" method="POST" enctype="multipart/form-data">
    <div class="row">
    <div class="col-md-6 mb-4">
      <label for="nama_unit" class="form-label small fw-bold text-uppercase text-muted">Nama Unit</label>
      <input type="text" class="form-control rounded-3" id="nama_unit" name="nama_unit" value="<?php echo htmlspecialchars($unit['nama_unit']); ?>" style="padding: 10px 15px;" />
      <div class="invalid-feedback">Nama unit harus diisi.</div>
    </div>
    <div class="col-md-6 mb-4">
      <label for="tipe" class="form-label small fw-bold text-uppercase text-muted">Tipe / Style</label>
      <input type="text" class="form-control rounded-3" id="tipe" name="tipe" value="<?php echo htmlspecialchars($unit['tipe']); ?>" style="padding: 10px 15px;" />
      <div class="invalid-feedback">Tipe unit harus diisi.</div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-6 mb-4">
      <label for="harga" class="form-label small fw-bold text-uppercase text-muted">Harga (Rp)</label>
      <input type="number" class="form-control rounded-3" id="harga" name="harga" value="<?php echo htmlspecialchars(intval($unit['harga'])); ?>" style="padding: 10px 15px;" />
      <div class="invalid-feedback">Harga harus berupa angka positif.</div>
    </div>
    <div class="col-md-6 mb-4">
      <label for="status" class="form-label small fw-bold text-uppercase text-muted">Status Unit</label>
      <select class="form-select rounded-3" id="status" name="status" style="padding: 10px 15px;">
      <option value="Tersedia" <?php echo $unit['status'] === 'Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
      <option value="Terjual" <?php echo $unit['status'] === 'Terjual' ? 'selected' : ''; ?>>Terjual</option>
      </select>
    </div>
    </div>
    <div class="mb-4">
    <label for="gambar" class="form-label small fw-bold text-uppercase text-muted">Ubah Gambar Unit (Opsional)</label>
    <input type="file" class="form-control rounded-3 mb-2" id="gambar" name="gambar" accept="image/png, image/jpeg, image/jpg" style="padding: 10px 15px;" />
    <div class="invalid-feedback">Silakan pilih file gambar (JPG/PNG) yang valid.</div>
    
    <div class="mt-3">
      <p class="small text-muted mb-1">Gambar saat ini:</p>
      <img src="../img/<?php echo htmlspecialchars($unit['gambar']); ?>" alt="Current Image" class="img-thumbnail rounded-2 shadow-sm" style="max-height: 120px;" />
    </div>
    </div>
    <div class="mb-4">
    <label for="deskripsi" class="form-label small fw-bold text-uppercase text-muted">Deskripsi / Spesifikasi</label>
    <textarea class="form-control rounded-3" id="deskripsi" name="deskripsi" rows="4" style="padding: 10px 15px;"><?php echo htmlspecialchars($unit['deskripsi']); ?></textarea>
    <div class="invalid-feedback">Deskripsi unit harus diisi.</div>
    </div>
    <button type="submit" class="btn btn-dark-minimal py-3 px-5 rounded-3 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">
    Simpan Perubahan
    </button>
    </form>
    </div>
    </div>
  </div>

  <!-- Side helper card -->
  <div class="col-lg-4">
    <div class="card shadow-sm border-0" style="border-radius: 12px; border-top: 4px solid var(--accent-color) !important;">
    <div class="card-body p-4">
    <h6 class="fw-bold mb-3" style="font-family: 'Outfit', sans-serif;">Info Aksi Edit</h6>
    <p class="small text-muted mb-0">
    Mengosongkan input file gambar akan tetap menggunakan gambar lama yang sudah terdaftar di server.
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
