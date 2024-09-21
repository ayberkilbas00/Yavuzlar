<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$coupon_id = $_GET['id'];

// Kuponu silme işlemi
$sql = "UPDATE coupons SET deleted_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $coupon_id]);

header("Location: manage_coupons.php");
exit();
?>
