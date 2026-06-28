<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$id = $_GET['id'] ?? '';

if (!empty($id)) {
    try {
        // Ambil info unit terlebih dahulu untuk hapus gambar
        $stmt = $pdo->prepare("SELECT gambar FROM housing_units WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $unit = $stmt->fetch();

        if ($unit) {
            // Hapus file gambar di server jika bukan gambar seed bawaan
            $image_file = '../img/' . $unit['gambar'];
            $default_images = ['onyx.jpg', 'green-crystal.jpg', 'the-morizen.jpg'];
            if (file_exists($image_file) && !in_array($unit['gambar'], $default_images)) {
                @unlink($image_file);
            }

            // Hapus dari database
            $stmt = $pdo->prepare("DELETE FROM housing_units WHERE id = :id");
            $stmt->execute([':id' => $id]);

            header("Location: dashboard.php?status=deleted");
            exit();
        } else {
            header("Location: dashboard.php?status=error");
            exit();
        }
    } catch (\PDOException $e) {
        header("Location: dashboard.php?status=error");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
