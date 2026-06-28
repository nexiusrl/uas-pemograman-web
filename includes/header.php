<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Deteksi path prefix dinamis untuk subfolder (seperti folder admin)
$path_prefix = "";
$current_dir = basename(dirname($_SERVER["PHP_SELF"]));
if ($current_dir === "admin") {
  $path_prefix = "../";
}
?>
<!doctype html>
<html lang="id">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Summarecon Mutiara Makassar</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
<!-- Custom CSS -->
<link rel="stylesheet" href="<?php echo $path_prefix; ?>css/style.css" />
</head>

<body>
<!-- Header / Navigation -->
<nav class="navbar navbar-light bg-white sticky-top shadow-sm">
<div class="container position-relative d-flex flex-wrap align-items-center justify-content-between">

<a class="navbar-brand" href="<?php echo $path_prefix; ?>index.php">SUMMARECON<span>MUTIARAMAKASSAR</span></a>

<div class="justify-content-end" id="navbarNav">
<ul class="navbar-nav flex-row flex-wrap align-items-center m-0 p-0 w-100 justify-content-end"
style="list-style: none; gap: 10px;">
<li class="nav-item px-2">
  <a class="nav-link" href="<?php echo $path_prefix; ?>index.php">Beranda</a>
</li>
<li class="nav-item px-2">
  <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#deskripsi">Filosofi</a>
</li>
<li class="nav-item px-2">
  <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#galeri">Koleksi Unit</a>
</li>
<li class="nav-item px-2">
  <a class="nav-link" href="<?php echo $path_prefix; ?>index.php#kontak">Hubungi Kami</a>
</li>
</ul>
</div>
</div>
</nav>
