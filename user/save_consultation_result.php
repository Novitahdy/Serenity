<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    require_once "../include/koneksi.php";

    $user_id = $_POST['user_id'];
    $total_score = $_POST['total_score'];
    $result_category = $_POST['result_category'];

    $query = "INSERT INTO consultation_results (user_id, total_score, result_category) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $total_score, $result_category);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
