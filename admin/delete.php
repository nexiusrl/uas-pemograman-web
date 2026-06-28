<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$id = $_GET['id'] ?? '';

if (!empty($id)) {
    // Ambil info unit terlebih dahulu untuk hapus gambar
    $query = "SELECT gambar FROM housing_units WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $unit = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($unit) {
            // Hapus file gambar di server jika bukan gambar seed bawaan
            $image_file = '../img/' . $unit['gambar'];
            $default_images = ['onyx.jpg', 'green-crystal.jpg', 'the-morizen.jpg'];
            if (file_exists($image_file) && !in_array($unit['gambar'], $default_images)) {
                @unlink($image_file);
            }

            // Hapus dari database
            $query_del = "DELETE FROM housing_units WHERE id = ?";
            $stmt_del = mysqli_prepare($conn, $query_del);
            if ($stmt_del) {
                mysqli_stmt_bind_param($stmt_del, "i", $id);
                mysqli_stmt_execute($stmt_del);
                mysqli_stmt_close($stmt_del);
                header("Location: dashboard.php?status=deleted");
                exit();
            }
        }
    }
    header("Location: dashboard.php?status=error");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
