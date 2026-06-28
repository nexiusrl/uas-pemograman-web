<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Ambil semua unit dari database
try {
    $stmt = $pdo->query("SELECT * FROM housing_units ORDER BY id DESC");
    $units = $stmt->fetchAll();
} catch (\PDOException $e) {
    die("Gagal memuat data: " . htmlspecialchars($e->getMessage()));
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin | Summarecon Mutiara Makassar</title>
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
          <h1 class="h2" style="font-weight: 700;">Manajemen Unit Perumahan</h1>
          <a href="add.php" class="btn btn-dark-minimal py-2 px-4 rounded-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
            <i class="bi bi-plus-lg me-1"></i> Tambah Unit Baru
          </a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'added'): ?>
          <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Unit perumahan berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
          <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Unit perumahan berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
          <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Unit perumahan berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
          <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi kesalahan dalam memproses data.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- Table Section -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4">
            <h5 class="mb-0" style="font-weight: 600;">Daftar Inventaris Unit</h5>
          </div>
          <div class="card-body px-4 pb-4">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead style="background-color: var(--primary-color); color: white;">
                  <tr>
                    <th class="py-3 px-3 rounded-start border-0">No</th>
                    <th class="py-3 border-0">Gambar</th>
                    <th class="py-3 border-0">Nama Unit</th>
                    <th class="py-3 border-0">Tipe / Style</th>
                    <th class="py-3 border-0">Harga</th>
                    <th class="py-3 border-0">Status</th>
                    <th class="py-3 rounded-end border-0">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($units)): ?>
                    <tr>
                      <td colspan="7" class="text-center py-4 text-muted">Belum ada unit terdaftar.</td>
                    </tr>
                  <?php else: ?>
                    <?php $no = 1; foreach ($units as $unit): ?>
                      <tr>
                        <td class="px-3 border-bottom"><?php echo $no++; ?></td>
                        <td class="border-bottom">
                          <img src="../img/<?php echo htmlspecialchars($unit['gambar']); ?>" alt="Unit" class="rounded-2 object-fit-cover shadow-sm" style="width: 80px; height: 50px;" />
                        </td>
                        <td class="fw-bold border-bottom"><?php echo htmlspecialchars($unit['nama_unit']); ?></td>
                        <td class="border-bottom"><?php echo htmlspecialchars($unit['tipe']); ?></td>
                        <td class="border-bottom">Rp <?php echo number_format($unit['harga'], 0, ',', '.'); ?></td>
                        <td class="border-bottom">
                          <span class="badge <?php echo $unit['status'] === 'Tersedia' ? 'bg-success' : 'bg-danger'; ?> bg-opacity-25 <?php echo $unit['status'] === 'Tersedia' ? 'text-success' : 'text-danger'; ?> border <?php echo $unit['status'] === 'Tersedia' ? 'border-success' : 'border-danger'; ?> border-opacity-50 px-3 py-2 rounded-pill">
                            <?php echo htmlspecialchars($unit['status']); ?>
                          </span>
                        </td>
                        <td class="border-bottom">
                          <div class="d-flex gap-2">
                            <a href="edit.php?id=<?php echo $unit['id']; ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                              <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button onclick="confirmDelete(<?php echo $unit['id']; ?>)" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                              <i class="bi bi-trash"></i> Hapus
                            </button>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Delete Confirmation Modal (Bootstrap) -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 shadow-lg">
        <div class="modal-header border-bottom-0 pt-4 px-4">
          <h5 class="modal-title fw-bold" id="deleteModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 text-muted">
          Apakah Anda yakin ingin menghapus data unit ini secara permanen? Aksi ini tidak dapat dibatalkan.
        </div>
        <div class="modal-footer border-top-0 pb-4 px-4">
          <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-bold text-uppercase small" data-bs-dismiss="modal" style="font-size: 0.75rem;">Batal</button>
          <a id="confirmDeleteBtn" href="#" class="btn btn-danger px-4 py-2 rounded-3 fw-bold text-uppercase text-white small" style="font-size: 0.75rem;">Hapus</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="../js/main.js"></script>
</body>

</html>
