<?php
include 'database.php';
session_start();

// Admin kontrolü
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_number = $_POST['question_number'];
    $question_text = $_POST['question_text'];
    $score = $_POST['score'];
    $correct_choice = $_POST['correct_choice'];

    // Seçenekler
    $choices = array(
        1 => $_POST['choice1'],
        2 => $_POST['choice2'],
        3 => $_POST['choice3'],
        4 => $_POST['choice4']
    );

    $query = "INSERT INTO questions (question_number, text, score) VALUES (?, ?, ?)"; // Skoru ekle
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('isi', $question_number, $question_text, $score);
    $insert_question = $stmt->execute();

    if ($insert_question) {
        $question_id = $mysqli->insert_id; // Yeni eklenen sorunun ID'si

        // Seçenekleri ekle
        foreach ($choices as $index => $value) {
            if (!empty($value)) {
                $is_correct = ($correct_choice == $index) ? 1 : 0;
                $query = "INSERT INTO choices (question_id, is_correct, text) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('iis', $question_id, $is_correct, $value);
                $stmt->execute();
            }
        }
        
        $msg = 'Soru başarıyla eklendi.';
    } else {
        $msg = 'Soru eklenirken bir hata oluştu: ' . $mysqli->error;
    }
} else {
    $msg = 'Geçersiz istek.';
}

header("Location: add.php?msg=" . urlencode($msg));
exit();
?>
