<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$restaurant_id = $_GET['id'];

// Restoranı silme işlemi
$sql = "UPDATE restaurants SET deleted_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $restaurant_id]);

header("Location: manage_restaurants.php");
exit();
?>
