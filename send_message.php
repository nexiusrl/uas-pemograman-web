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

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (nama, email, telepon, pesan) VALUES (:nama, :email, :telepon, :pesan)");
        $stmt->execute([
            ':nama' => $nama,
            ':email' => $email,
            ':telepon' => $telepon,
            ':pesan' => $pesan
        ]);
        header("Location: index.php?status=success#kontak");
        exit();
    } catch (\PDOException $e) {
        header("Location: index.php?status=error#kontak");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
