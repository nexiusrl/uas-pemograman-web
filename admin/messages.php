<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

// Ambil data semua pesan customer
$query = "SELECT * FROM contact_messages ORDER BY tanggal_kirim DESC";
$result = mysqli_query($conn, $query);
$messages = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
  }
} else {
  die("Gagal memuat pesan: " . mysqli_error($conn));
}
?>
<!doctype html>
<html lang="id">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Pesan Masuk | Dashboard Admin</title>
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
    <a class="nav-link" href="dashboard.php">
    <i class="bi bi-house-door me-2"></i> Data Unit
    </a>
    </li>
    <li class="nav-item mb-2">
    <a class="nav-link active" href="messages.php">
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
  <h1 class="h2" style="font-weight: 700;">Pesan Masuk Customer</h1>
  </div>

  <!-- Messages Table -->
  <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
  <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4">
    <h5 class="mb-0" style="font-weight: 600;">Daftar Pesan Masuk</h5>
  </div>
  <div class="card-body px-4 pb-4">
    <div class="table-responsive">
    <table class="table table-hover align-middle">
    <thead style="background-color: var(--primary-color); color: white;">
    <tr>
    <th class="py-3 px-3 rounded-start border-0">No</th>
    <th class="py-3 border-0">Nama</th>
    <th class="py-3 border-0">Kontak Info</th>
    <th class="py-3 border-0">Isi Pesan</th>
    <th class="py-3 rounded-end border-0">Tanggal Masuk</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($messages)): ?>
    <tr>
      <td colspan="5" class="text-center py-4 text-muted">Belum ada pesan masuk.</td>
    </tr>
    <?php else: ?>
    <?php
    $no = 1;
    foreach ($messages as $msg): ?>
      <tr>
      <td class="px-3 border-bottom align-top pt-3"><?php echo $no++; ?></td>
      <td class="fw-bold border-bottom align-top pt-3"><?php echo htmlspecialchars(
        $msg["nama"],
      ); ?></td>
      <td class="border-bottom align-top pt-3">
      <div class="small">
        <span class="d-block"><i class="bi bi-envelope me-1 text-muted"></i><?php echo htmlspecialchars(
          $msg["email"],
        ); ?></span>
        <span class="d-block mt-1"><i class="bi bi-telephone me-1 text-muted"></i><?php echo htmlspecialchars(
          $msg["telepon"],
        ); ?></span>
      </div>
      </td>
      <td class="border-bottom align-top pt-3 text-wrap" style="max-width: 400px;"><?php echo nl2br(
        htmlspecialchars($msg["pesan"]),
      ); ?></td>
      <td class="border-bottom align-top pt-3 text-muted small"><?php echo date(
        "d-m-Y H:i",
        strtotime($msg["tanggal_kirim"]),
      ); ?> WIB</td>
      </tr>
    <?php endforeach;
    ?>
    <?php endif; ?>
    </tbody>
    </table>
    </div>
  </div>
  </div>
  </main>
</div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
