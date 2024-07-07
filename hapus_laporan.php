<?php
require_once "./include/koneksi.php";

// Periksa apakah ada parameter ID laporan yang dikirimkan melalui URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitasi ID laporan yang dikirimkan melalui URL
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk menghapus laporan berdasarkan ID
    $deleteQuery = "DELETE FROM consultation_results WHERE id = $id";
    $deleteResult = mysqli_query($koneksi, $deleteQuery);

    if ($deleteResult) {
        // Jika penghapusan berhasil, redirect ke halaman laporan dengan pesan sukses
        header("Location: ./admin/laporan.php?success=delete");
        exit();
    } else {
        // Jika terjadi kesalahan saat menghapus laporan, tampilkan pesan kesalahan
        echo "Error: " . $deleteQuery . "<br>" . mysqli_error($koneksi);
    }
} else {
    // Jika tidak ada parameter ID laporan yang dikirimkan melalui URL, redirect ke halaman laporan
    header("Location: ./admin/laporan.php");
    exit();
}

// Tutup koneksi ke database
mysqli_close($koneksi);
