<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    // Server-side validation
    if (empty($nama) || empty($email) || empty($telepon) || empty($pesan) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?status=error#kontak");
        exit();
    }

    $query = "INSERT INTO contact_messages (nama, email, telepon, pesan) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $telepon, $pesan);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php?status=success#kontak");
            exit();
        }
    }
    header("Location: index.php?status=error#kontak");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
