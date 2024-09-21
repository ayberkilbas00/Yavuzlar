<?php
session_start();
include 'db.php';

// Sepete ürün ekleme işlemi
if (isset($_GET['dish_id'])) {
    $dish_id = $_GET['dish_id'];

    // Ürünü veritabanından çek
    $sql = "SELECT * FROM dishes WHERE id = :dish_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':dish_id' => $dish_id]);
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dish) {
        echo "Ürün bulunamadı.";
        exit();
    }

    // Sepette daha önce eklenmiş mi kontrol et
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Sepete ekleme işlemi
    $cart_item = [
        'id' => $dish['id'],  // Ürün ID'si burada düzgün bir şekilde kaydediliyor olmalı
        'name' => $dish['name'],
        'price' => $dish['price'],
        'quantity' => 1
    ];

    // Eğer ürün zaten sepette varsa miktarını arttır
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $dish_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    // Eğer ürün sepette değilse, yeni bir ürün olarak ekle
    if (!$found) {
        $_SESSION['cart'][] = $cart_item;
    }

    // Sepete ekleme işlemi sonrası yönlendirme
    header("Location: cart.php");
    exit();
}
?>
