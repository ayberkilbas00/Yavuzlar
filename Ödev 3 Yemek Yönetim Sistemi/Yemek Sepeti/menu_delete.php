<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$dish_id = $_GET['id'];

// Yemeği silme işlemi
$sql = "UPDATE dishes SET deleted_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $dish_id]);

header("Location: menu_management.php");
exit();
?>
