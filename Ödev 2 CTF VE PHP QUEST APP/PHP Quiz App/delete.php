<?php
include 'database.php';
session_start();

// Admin kontrolü
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin'){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){
    $question_id = $_GET['id'];

    $query = "DELETE FROM questions WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $question_id);
    if($stmt->execute()){
        $_SESSION['message'] = "Soru başarıyla silindi.";
    } else {
        $_SESSION['message'] = "Soru silinirken bir hata oluştu.";
    }
}

header("Location: admin_dashboard.php");
exit();
?>
