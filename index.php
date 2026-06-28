<?php
require_once "includes/db.php";
require_once "includes/header.php";

// Ambil data unit dari database
$query = "SELECT * FROM housing_units ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$units = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $units[] = $row;
  }
}
?>

<!-- Hero Section -->
<header class="hero-section">
<div class="container">
<div class="hero-content">
<span class="text-uppercase mb-3 d-block small fw-bold" style="letter-spacing: 2px;">Selamat Datang di
Kemewahan</span>
<h1 class="display-2 mb-4">Definisi Baru<br />Hunian Modern</h1>
<p class="lead mb-5 mx-auto" style="max-width: 600px; opacity: 0.9; font-weight: 300">
Gabungan sempurna antara arsitektur kontemporer dan kenyamanan tak tertandingi di lokasi paling prestisius
Makassar.
</p>
<div class="d-flex justify-content-center gap-3">
<a href="#galeri" class="btn btn-light-minimal btn-minimal">Eksplorasi Unit</a>
<a href="#deskripsi" class="btn btn-outline-light btn-minimal">Tentang Kami</a>
</div>
</div>
</div>
</header>

<!-- Description Section -->
<section id="deskripsi">
<div class="container text-center">
<div class="row justify-content-center">
<div class="col-lg-8 mb-5">
<h2 class="section-title text-center">Filosofi Desain Kami</h2>
<p class="mb-5 mx-auto" style="max-width: 700px">
Di Summarecon Mutiara Makassar, kami tidak sekadar membangun rumah. Kami menciptakan ruang di mana setiap
sudut bercerita tentang kualitas, kenyamanan, dan elegansi.
</p>
<div class="row g-4 mt-2 justify-content-center">
<div class="col-md-5">
  <div class="feature-box">
  <i class="bi bi-gem feature-icon"></i>
  <h4 class="h6 fw-bold text-uppercase mt-3 mb-2">Material Premium</h4>
  <p class="small text-muted mb-0">
  Pemilihan material terbaik untuk daya tahan dan estetika jangka panjang.
  </p>
  </div>
</div>
<div class="col-md-5">
  <div class="feature-box">
  <i class="bi bi-geo-alt feature-icon"></i>
  <h4 class="h6 fw-bold text-uppercase mt-3 mb-2">Lokasi Strategis</h4>
  <p class="small text-muted mb-0">
  Berlokasi strategis di antara Bandara Sultan Hasanuddin dan Makassar New Port.
  </p>
  </div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Gallery Section -->
<section id="galeri" class="bg-white">
<div class="container">
<div class="text-center mb-5">
<h2 class="section-title text-center">Koleksi Cluster Eksklusif</h2>
<p class="text-muted mx-auto" style="max-width: 700px">
Jelajahi berbagai pilihan hunian dengan konsep arsitektur unik yang dirancang untuk kenyamanan keluarga modern
di Summarecon Mutiara Makassar.
</p>
</div>
<div class="row g-4">
<?php if (empty($units)): ?>
<div class="col-12 text-center py-5">
<p class="text-muted">Belum ada unit yang tersedia.</p>
</div>
<?php else: ?>
<?php foreach ($units as $unit): ?>
<div class="col-md-4">
  <div class="card card-hover border-0 shadow-sm rounded-4 h-100 overflow-hidden">
  <div class="position-relative">
  <span
  class="badge bg-white text-dark position-absolute m-3 px-3 py-2 rounded-pill shadow-sm text-uppercase"
  style="top: 0; left: 0; z-index: 2; letter-spacing: 1px;"><?php echo htmlspecialchars(
    $unit["tipe"],
  ); ?></span>
  <img src="img/<?php echo htmlspecialchars(
    $unit["gambar"],
  ); ?>" class="card-img-top object-fit-cover" alt="<?php echo htmlspecialchars(
  $unit["nama_unit"],
); ?>" style="height: 280px;" />
  </div>
  <div class="card-body p-4">
  <h5 class="card-title h6 fw-bold text-uppercase mb-2"><?php echo htmlspecialchars(
    $unit["nama_unit"],
  ); ?></h5>
  <p class="small text-muted mb-2">Rp. <?php echo number_format(
    $unit["harga"],
    0,
    ",",
    ".",
  ); ?></p>
  <span class="badge <?php echo $unit["stok"] > 0
    ? "bg-success"
    : "bg-danger"; ?> text-white mb-3" style="font-size: 0.7rem; font-weight: 500;"><?php echo $unit[
   "stok"
 ] > 0
   ? "Tersedia: " . htmlspecialchars($unit["stok"]) . " Unit"
   : "Habis"; ?></span>
  <hr class="my-3 opacity-10" />
  <div class="d-flex small text-muted">
  <span><?php echo htmlspecialchars($unit["deskripsi"]); ?></span>
  </div>
  </div>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
</section>

<!-- Contact Form Section -->
<section id="kontak" class="bg-light">
<div class="container" style="max-width: 700px;">
<div class="text-center mb-5">
<h2 class="section-title text-center">Hubungi Kami</h2>
<p class="text-muted">Kirim pesan kepada agen pemasaran kami untuk info unit selengkapnya.</p>
</div>

<?php if (isset($_GET["status"]) && $_GET["status"] === "success"): ?>
<div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
<i class="bi bi-check-circle-fill me-2"></i> Pesan Anda berhasil dikirim! Agen kami akan segera menghubungi Anda.
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php elseif (isset($_GET["status"]) && $_GET["status"] === "error"): ?>
<div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
<i class="bi-exclamation-triangle-fill me-2"></i> Gagal mengirim pesan. Silakan coba lagi.
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="bg-white p-5 rounded-4 shadow-sm border">
<form id="contactForm" action="send_message.php" method="POST">
<div class="mb-3">
<label for="nama" class="form-label fw-bold small text-uppercase">Nama Lengkap</label>
<input type="text" class="form-control rounded-3" id="nama" name="nama" placeholder="Masukkan nama Anda">
<div class="invalid-feedback">Nama harus diisi.</div>
</div>
<div class="mb-3">
<label for="email" class="form-label fw-bold small text-uppercase">Alamat Email</label>
<input type="email" class="form-control rounded-3" id="email" name="email" placeholder="nama@email.com">
<div class="invalid-feedback">Format email tidak valid.</div>
</div>
<div class="mb-3">
<label for="telepon" class="form-label fw-bold small text-uppercase">Nomor Telepon / WA</label>
<input type="text" class="form-control rounded-3" id="telepon" name="telepon" placeholder="Contoh: 08123456789">
<div class="invalid-feedback">Nomor telepon harus diisi.</div>
</div>
<div class="mb-4">
<label for="pesan" class="form-label fw-bold small text-uppercase">Pesan</label>
<textarea class="form-control rounded-3" id="pesan" name="pesan" rows="4" placeholder="Tuliskan detail pertanyaan atau unit yang diminati"></textarea>
<div class="invalid-feedback">Pesan harus diisi.</div>
</div>
<button type="submit" class="btn btn-dark-minimal w-100 py-3 rounded-3 text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">Kirim Pesan</button>
</form>
</div>
</div>
</section>

<?php require_once "includes/footer.php"; ?>
