<?php
require_once "./include/koneksi.php";

// Pastikan id pengguna tersedia dalam parameter URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk menghapus pengguna berdasarkan id
    $deleteQuery = "DELETE FROM users WHERE id = $userId";
    $deleteResult = mysqli_query($koneksi, $deleteQuery);

    // Periksa apakah pengguna berhasil dihapus
    if (mysqli_query($koneksi, $deleteQuery)) {
        // Redirect ke halaman pengguna.php dengan pesan sukses hapus
        header("Location: ./admin/pengguna.php?success=delete");
        exit();
    } else {
        // Handle the delete failure
        echo "Error deleting record: " . mysqli_error($koneksi);
    }
} else {
    header("Location: ./admin/pengguna.php");
    exit();
}
// Tutup koneksi ke database
mysqli_close($koneksi);
