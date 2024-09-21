<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';  // Veritabanı bağlantısı

// Firma ID'sini al
$firma_id = $_SESSION['user_id'];

// Kuponları listeleme sorgusu
$sql = "SELECT * FROM coupons WHERE restaurant_id = :restaurant_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':restaurant_id' => $firma_id]);
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $discount = $_POST['discount'];
    $expires_at = $_POST['expires_at'];

    // Kupon ekleme sorgusu
    $sql = "INSERT INTO coupons (code, discount, restaurant_id, expires_at) VALUES (:code, :discount, :restaurant_id, :expires_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':code' => $code,
        ':discount' => $discount,
        ':restaurant_id' => $firma_id,
        ':expires_at' => $expires_at
    ]);

    echo "<div class='alert alert-success' role='alert'>Kupon başarıyla eklendi!</div>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kupon Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Kuponlar</h2>

        <form action="coupon_management.php" method="POST">
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
            <button type="submit" class="btn btn-primary">Ekle</button>
        </form>

        <h4 class="mt-4">Mevcut Kuponlar</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kod</th>
                    <th>İndirim (%)</th>
                    <th>Son Kullanma Tarihi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $coupon): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($coupon['code']); ?></td>
                        <td><?php echo htmlspecialchars($coupon['discount']); ?></td>
                        <td><?php echo htmlspecialchars($coupon['expires_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="firma_dashboard.php" class="btn btn-secondary">Geri Dön</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
