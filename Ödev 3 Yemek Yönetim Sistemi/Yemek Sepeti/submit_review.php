<?php
session_start();
include 'db.php';  // Veritabanı bağlantısı

// Kullanıcı giriş yapmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];  // Giriş yapmış kullanıcı ID'si
    $restaurant_id = $_POST['restaurant_id'];  // Yorum yapılacak restoran ID'si
    $rating = $_POST['rating'];  // Kullanıcının verdiği puan
    $comment = $_POST['comment'];  // Kullanıcının yazdığı yorum

    // Yorum ve puanlama veritabanına kaydedilir
    $sql = "INSERT INTO reviews (user_id, restaurant_id, rating, comment) VALUES (:user_id, :restaurant_id, :rating, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':restaurant_id' => $restaurant_id,
        ':rating' => $rating,
        ':comment' => $comment
    ]);

    // Yorum yapıldıktan sonra restoran detay sayfasına geri yönlendir
    header("Location: restaurant_detail.php?id=" . $restaurant_id);
    exit();
}
?>
