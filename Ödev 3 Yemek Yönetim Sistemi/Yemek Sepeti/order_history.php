<?php
session_start();
include 'db.php';

// Kullanıcı rolünü kontrol et (müşteri, firma veya admin olmalı)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['müşteri', 'firma', 'admin'])) {
    header("Location: login.php");
    exit();
}

// Siparişleri çekme sorgusu
if ($_SESSION['role'] == 'müşteri') {
    // Müşteri sadece kendi siparişlerini görsün
    $sql = "SELECT o.id, o.total_price, o.created_at, o.status 
            FROM orders o 
            WHERE o.user_id = :user_id 
            ORDER BY o.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
} else {
    // Firma ve admin tüm siparişleri görsün
    $sql = "SELECT o.id, o.total_price, o.created_at, o.status, r.name AS restaurant_name
            FROM orders o
            JOIN restaurants r ON o.restaurant_id = r.id
            ORDER BY o.created_at DESC";
    $stmt = $pdo->query($sql);
}
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Geçmişi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sipariş Geçmişi</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sipariş ID</th>
                    <th>Toplam Fiyat</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <?php if ($_SESSION['role'] != 'müşteri'): // Firma ve admin için restoran adı göster ?>
                        <th>Restoran Adı</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?> ₺</td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <?php if ($_SESSION['role'] != 'müşteri'): ?>
                            <td><?php echo htmlspecialchars($order['restaurant_name']); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="<?php echo ($_SESSION['role'] == 'firma') ? 'firma_dashboard.php' : 'customer_dashboard.php'; ?>" class="btn btn-secondary">Geri Dön</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
