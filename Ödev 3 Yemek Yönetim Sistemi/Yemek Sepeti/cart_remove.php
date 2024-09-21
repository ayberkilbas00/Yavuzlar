<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'müşteri') {
    header("Location: login.php");
    exit();
}

$index = $_GET['index'];
$cart = $_SESSION['cart'] ?? [];
unset($cart[$index]);
$_SESSION['cart'] = array_values($cart);

header("Location: cart.php");
exit();
?>
