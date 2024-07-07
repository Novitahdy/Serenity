<?php
require_once "./include/koneksi.php";

// Periksa apakah ada parameter ID artikel yang dikirimkan melalui URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitasi ID artikel yang dikirimkan melalui URL
    $article_id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Query untuk menghapus artikel berdasarkan ID
    $deleteQuery = "DELETE FROM articles WHERE id = $article_id";
    $deleteResult = mysqli_query($koneksi, $deleteQuery);

    if ($deleteResult) {
        // Jika penghapusan berhasil, redirect ke halaman artikel dengan pesan sukses
        header("Location: ./admin/artikel.php?success=delete");
        exit();
    } else {
        // Jika terjadi kesalahan saat menghapus artikel, tampilkan pesan kesalahan
        echo "Error: " . $deleteQuery . "<br>" . mysqli_error($koneksi);
    }
} else {
    // Jika tidak ada parameter ID artikel yang dikirimkan melalui URL, redirect ke halaman artikel
    header("Location: ./admin/artikel.php");
    exit();
}

// Tutup koneksi ke database
mysqli_close($koneksi);
