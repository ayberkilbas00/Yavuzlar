<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_GET['id'];

// Kullanıcıyı silme işlemi (soft delete olarak işaretleme)
$sql = "UPDATE users SET deleted_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $user_id]);

header("Location: user_management.php");
exit();
?>
