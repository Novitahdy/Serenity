<?php
session_start();
require_once "../include/koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'];

foreach ($data as $answer) {
    $question_text = $answer['question'];
    $answer_value = $answer['answer'];

    // Insert answer into database
    $stmt = $koneksi->prepare("INSERT INTO user_answers (user_id, question_text, answer_value) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $question_text, $answer_value);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error saving answers.']);
        exit();
    }
}

echo json_encode(['success' => true]);
