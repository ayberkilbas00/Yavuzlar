<?php
session_start();
include 'db.php';  // Veritabanı bağlantısı

// Eğer oturumda sepet yoksa, kullanıcıyı sepetin boş olduğu mesajını göstermek üzere yönlendir.
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Sepetiniz boş, ödeme işlemi yapılamaz.";
    exit();
}

// Oturumdaki sepeti al
$cart = $_SESSION['cart'];
$total = 0;  // Toplam fiyatı hesaplamak için

// Toplam tutarı hesapla ve her bir öğede ID kontrolü yap
foreach ($cart as $item) {
    if (!isset($item['id'])) {
        echo "Sepette ürün ID'si bulunamadı.";  // Hata mesajını yakalayalım
        exit();
    }
    $total += $item['price'] * $item['quantity'];
}

// Kullanıcının bakiyesini kontrol et
$user_id = $_SESSION['user_id'];
$sql = "SELECT balance FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['balance'] < $total) {
    // Eğer bakiye yetersizse hata mesajı göster ve işlemi durdur
    echo "<div class='alert alert-danger'>Yetersiz bakiye! Lütfen bakiyenizi yükseltin.</div>";
    exit();
}

// Eğer ödeme işlemi yapılacaksa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // İlk olarak sepetteki ilk yemeğin ID'sini kontrol et ve işlemi başlat
        $first_dish_key = array_key_first($cart);  // Dizinin ilk anahtarını al
        if (!isset($cart[$first_dish_key]['id'])) {
            throw new Exception("Sepette ürün ID'si bulunamadı.");
        }

        $first_dish_id = $cart[$first_dish_key]['id'];  // Sepetteki ilk yemeğin ID'si
        $sql = "SELECT restaurant_id FROM dishes WHERE id = :dish_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':dish_id' => $first_dish_id]);
        $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$restaurant) {
            throw new Exception("Restoran bulunamadı.");
        }

        $restaurant_id = $restaurant['restaurant_id'];  // Restoran ID'sini al

        // Siparişi kaydet
        $sql = "INSERT INTO orders (user_id, restaurant_id, total_price, created_at) VALUES (:user_id, :restaurant_id, :total_price, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':restaurant_id' => $restaurant_id,
            ':total_price' => $total
        ]);

        $order_id = $pdo->lastInsertId();

        // Sipariş detaylarını kaydet
        foreach ($cart as $item) {
            $sql = "INSERT INTO order_items (order_id, dish_id, quantity, price) VALUES (:order_id, :dish_id, :quantity, :price)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':order_id' => $order_id,
                ':dish_id' => $item['id'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price']
            ]);
        }

        // Kullanıcının bakiyesini güncelle
        $new_balance = $user['balance'] - $total;
        $sql = "UPDATE users SET balance = :new_balance WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':new_balance' => $new_balance,
            ':user_id' => $user_id
        ]);

        unset($_SESSION['cart']);

        // Başarıyla ödeme yapılınca müşteri paneline yönlendir
        header("Location: customer_dashboard.php");
        exit();
    } catch (Exception $e) {
        echo "Ödeme hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Yap</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Ödeme Yap</h2>
        <p>Toplam Tutar: <strong><?php echo $total; ?> ₺</strong></p>

        <form action="checkout.php" method="POST">
            <button type="submit" class="btn btn-success">Ödemeyi Onayla</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
