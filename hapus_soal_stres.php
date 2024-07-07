<?php
require_once "./include/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_soal = $_GET['id'];

    // Delete the question from the database
    $query = "DELETE FROM questions_s WHERE id_soal = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_soal);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: ./admin/soal_stres.php?success=delete");
            exit();
        } else {
            echo "Error: Failed to delete the question!";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    echo "Invalid request!";
}
