<?php
session_start();
include 'db.php';

// Sepetten ürün çıkarma işlemi (1'er 1'er azalt)
if (isset($_GET['remove_id'])) {
    $remove_id = $_GET['remove_id'];

    // Sepetten ürünü bul
    if (isset($_SESSION['cart'][$remove_id])) {
        if ($_SESSION['cart'][$remove_id]['quantity'] > 1) {
            $_SESSION['cart'][$remove_id]['quantity']--;
        } else {
            unset($_SESSION['cart'][$remove_id]);
        }
    }

    if (empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }

    header("Location: cart.php");
    exit();
}

// Sepeti oturumdan al
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
$discount = 0;
$final_total = $total;

// Toplam tutarı hesapla
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Kupon kodu gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['coupon_code'])) {
    $coupon_code = $_POST['coupon_code'];

    // Kupon kodunu veritabanında kontrol et
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = :code");
    $stmt->execute([':code' => $coupon_code]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($coupon) {
        // Sabit bir indirim uygula
        $discount = $coupon['discount'];
        $final_total = $total - $discount;
        $_SESSION['discount'] = $discount; // İndirimi oturuma kaydet
        $_SESSION['final_total'] = $final_total; // İndirimi dahil eden toplam fiyatı kaydet
        echo "<div class='alert alert-success'>Kupon başarıyla uygulandı. İndirim: " . $discount . " ₺</div>";
    } else {
        echo "<div class='alert alert-danger'>Geçersiz kupon kodu.</div>";
    }
} else {
    $final_total = $total;
    $_SESSION['final_total'] = $final_total; // Toplam fiyatı oturuma kaydet
}
?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetiniz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-brand:hover, .navbar-nav .nav-link:hover {
            color: #ffce45;
        }
        .card-img-top {
            height: 150px;
            object-fit: cover;
        }
        .cart-item {
            margin-bottom: 20px;
        }
        .total-price {
            font-size: 1.5em;
            font-weight: bold;
        }
        .remove-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="customer_dashboard.php">Yemek Yönetim Sistemi</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="order_history.php">Sipariş Geçmişi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Sepetim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Sepetiniz</h2>
        <?php if (empty($cart)): ?>
            <div class="alert alert-warning text-center" role="alert">
                Sepetiniz boş.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($cart as $item_id => $item): ?>
                    <div class="col-md-4 cart-item">
                        <div class="card h-100">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="Yemek Görseli">
                            <?php else: ?>
                                <img src="images/menu_placeholder.jpg" class="card-img-top" alt="Varsayılan Görsel">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text">Miktar: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                <p class="card-text">Fiyat: <?php echo htmlspecialchars($item['price']); ?> ₺</p>
                                <p class="card-text">Toplam: <?php echo htmlspecialchars($item['price'] * $item['quantity']); ?> ₺</p>
                                <a href="cart.php?remove_id=<?php echo $item_id; ?>" class="btn btn-danger btn-sm remove-btn">Miktarı Azalt</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <!-- Kupon kodu girişi -->
                <form action="cart.php" method="POST" class="form-inline d-flex justify-content-center">
                    <input type="text" name="coupon_code" class="form-control mr-2" placeholder="Kupon Kodu" required>
                    <button type="submit" class="btn btn-primary">Kuponu Uygula</button>
                </form>
            </div>

            <div class="text-right mt-4">
                <p class="total-price">Toplam Tutar: <?php echo $total; ?> ₺</p>
                <p class="total-price">İndirim: <?php echo $discount; ?> ₺</p>
                <p class="total-price">Ödenecek Tutar: <?php echo $final_total; ?> ₺</p>
                <a href="checkout.php" class="btn btn-success btn-lg">Ödeme Yap</a>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="customer_dashboard.php" class="btn btn-secondary">Alışverişe Devam Et</a>
        </div>
    </div>

    <footer class="text-center py-4 mt-5">
        <p>&copy; 2024 Yemek Yönetim Sistemi. Tüm Hakları Saklıdır.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
