<?php
session_start();
include 'db.php';

// Firma veya admin rolünü kontrol et
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'firma' && $_SESSION['role'] != 'admin')) {
    header("Location: login.php");
    exit();
}

// Firma ID'sini al
if ($_SESSION['role'] == 'firma') {
    // Firma kendi restoranını yönetir
    $firma_id = $_SESSION['user_id'];
    // Restoran ID'sini almak için sorgu
    $sql_restaurant = "SELECT id FROM restaurants WHERE owner_id = :owner_id";
    $stmt_restaurant = $pdo->prepare($sql_restaurant);
    $stmt_restaurant->execute([':owner_id' => $firma_id]);
    $restaurant = $stmt_restaurant->fetch(PDO::FETCH_ASSOC);

    if (!$restaurant) {
        echo "<div class='alert alert-danger' role='alert'>Restoran bulunamadı.</div>";
        exit();
    }

    $restaurant_id = $restaurant['id'];  // Firma kullanıcıları için restoran ID
} else {
    $restaurant_id = null;
}

// Kuponları listeleme sorgusu
if ($_SESSION['role'] == 'firma') {
    // Eğer kullanıcı firma ise sadece kendi restoranının kuponlarını görsün
    $sql = "SELECT * FROM coupons WHERE restaurant_id = :restaurant_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':restaurant_id' => $restaurant_id]);
} else {
    // Eğer kullanıcı adminse tüm kuponları görsün
    $sql = "SELECT * FROM coupons";
    $stmt = $pdo->query($sql);
}
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kupon ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $discount = $_POST['discount'];
    $expires_at = $_POST['expires_at'];

    try {
        // Kupon kodunun zaten mevcut olup olmadığını kontrol et
        $sql = "SELECT COUNT(*) FROM coupons WHERE code = :code";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':code' => $code]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            throw new Exception("Bu kupon kodu zaten mevcut.");
        }

        // Eğer adminse, kupon eklerken bir restoran seçmesini sağla
        if ($_SESSION['role'] == 'admin') {
            $restaurant_id = $_POST['restaurant_id'];  // Admin restoran seçer
        }

        // Kupon ekleme sorgusu
        $sql = "INSERT INTO coupons (code, discount, restaurant_id, expires_at) VALUES (:code, :discount, :restaurant_id, :expires_at)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':code' => $code,
            ':discount' => $discount,
            ':restaurant_id' => $restaurant_id,
            ':expires_at' => $expires_at
        ]);

        echo "<div class='alert alert-success' role='alert'>Kupon başarıyla eklendi!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Kupon ekleme hatası: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kupon Yönetimi</title>
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
        .container {
            max-width: 900px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="firma_dashboard.php">Firma Paneli</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="manage_menu.php">Menü Yönetimi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_coupons.php">Kupon Yönetimi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order_history.php">Sipariş Geçmişi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Kupon Yönetimi</h2>

        <!-- Kupon Ekleme Formu -->
        <form action="manage_coupons.php" method="POST">
            <h4>Yeni Kupon Ekle</h4>
            <div class="form-group">
                <label for="code">Kupon Kodu:</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="form-group">
                <label for="discount">İndirim Yüzdesi (%):</label>
                <input type="number" class="form-control" id="discount" name="discount" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="expires_at">Son Kullanma Tarihi:</label>
                <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" required>
            </div>

            <!-- Admin kullanıcıları için restoran seçimi -->
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <div class="form-group">
                    <label for="restaurant_id">Restoran Seç:</label>
                    <select class="form-control" id="restaurant_id" name="restaurant_id" required>
                        <?php
                        $sql = "SELECT id, name FROM restaurants";
                        $stmt = $pdo->query($sql);
                        $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($restaurants as $restaurant) {
                            echo "<option value=\"" . $restaurant['id'] . "\">" . htmlspecialchars($restaurant['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Kupon Ekle</button>
        </form>

        <!-- Kupon Listesi -->
        <h4 class="mt-4">Mevcut Kuponlar</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kod</th>
                    <th>İndirim (%)</th>
                    <th>Son Kullanma Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $coupon): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($coupon['code']); ?></td>
                        <td><?php echo htmlspecialchars($coupon['discount']); ?></td>
                        <td><?php echo htmlspecialchars($coupon['expires_at']); ?></td>
                        <td>
                            <a href="coupon_edit.php?id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                            <a href="coupon_delete.php?id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-danger">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="firma_dashboard.php" class="btn btn-secondary">Geri Dön</a>
    </div>

    <footer class="text-center py-4">
        <p>&copy; 2024 Firma Yönetim Sistemi. Tüm Hakları Saklıdır.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
