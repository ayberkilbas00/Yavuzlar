<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$review_id = $_GET['id'];

// Yorum silme işlemi (soft delete olarak işaretleme)
$sql = "UPDATE reviews SET deleted_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $review_id]);

header("Location: review_management.php");
exit();
?>
