<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Sipariş ID'sini al
$order_id = $_GET['id'];

// Sipariş bilgilerini çek
$sql = "SELECT o.*, u.name AS user_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Sipariş kalemlerini çek
$sql = "SELECT * FROM order_items WHERE order_id = :order_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':order_id' => $order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Detayları</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sipariş Detayları</h2>
        <h4>Sipariş ID: <?php echo htmlspecialchars($order['id']); ?></h4>
        <p><strong>Kullanıcı:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
        <p><strong>Toplam Fiyat:</strong> <?php echo htmlspecialchars($order['total_price']); ?> ₺</p>
        <p><strong>Durum:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
        <p><strong>Tarih:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>

        <h4 class="mt-4">Sipariş Kalemleri</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Yemek Adı</th>
                    <th>Miktar</th>
                    <th>Fiyat</th>
                    <th>Toplam</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <?php
                        // Yemek bilgilerini al
                        $sql = "SELECT name, price FROM dishes WHERE id = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':id' => $item['dish_id']]);
                        $dish = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <td><?php echo htmlspecialchars($dish['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($dish['price']); ?> ₺</td>
                        <td><?php echo htmlspecialchars($item['quantity'] * $dish['price']); ?> ₺</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="manage_orders.php" class="btn btn-secondary">Geri Dön</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
