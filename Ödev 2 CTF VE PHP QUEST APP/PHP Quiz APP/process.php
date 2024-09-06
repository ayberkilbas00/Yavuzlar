<?php
include 'database.php';
session_start();

// Check if score is set
if(!isset($_SESSION['score'])){
    $_SESSION['score'] = 0;
}

if ($_POST) {
    $number = $_POST['number'];
    $selected_choice = $_POST['choice'];

    // Get question ID for the current question number
    $query = "SELECT id FROM questions WHERE question_number = ?";
    $stmt = $mysqli->prepare($query);

    if($stmt === false) {
        die('Error preparing statement: ' . $mysqli->error);
    }

    $stmt->bind_param('i', $number);
    $stmt->execute();
    $result = $stmt->get_result();
    $question_row = $result->fetch_assoc();
    $question_id = $question_row['id'];

    // Get correct choice
    $query = "SELECT id FROM choices WHERE question_id = ? AND is_correct = 1";
    $stmt = $mysqli->prepare($query);

    if($stmt === false) {
        die('Error preparing statement: ' . $mysqli->error);
    }

    $stmt->bind_param('i', $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $choice_row = $result->fetch_assoc();
    $correct_choice = $choice_row['id'];

    // Compare selected choice with correct choice
    if ($correct_choice == $selected_choice) {
        $_SESSION['score']++;
    }

    // Get total questions
    $query = "SELECT COUNT(*) as total FROM questions";
    $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
    $row = $result->fetch_assoc();
    $total = $row['total'];

    // Check if last question
    if ($number >= $total) {
        header("Location: final.php");
        exit();
    } else {
        $next = $number + 1;
        header("Location: question.php?n=" . $next);
        exit();
    }
}
?>
