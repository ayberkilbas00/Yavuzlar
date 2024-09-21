<?php
session_start();
include 'db.php';

// Firma rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'firma') {
    header("Location: login.php");
    exit();
}

// Firma sahibinin restoranına gelen siparişleri al
$sql = "SELECT o.id, o.total_price, o.created_at, o.status, u.name AS customer_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        JOIN restaurants r ON o.restaurant_id = r.id
        WHERE r.owner_id = :owner_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':owner_id' => $_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sipariş durumunu güncelle
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $sql = "UPDATE orders SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => $new_status, ':id' => $order_id]);

    header("Location: manage_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sipariş Yönetimi</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sipariş ID</th>
                    <th>Müşteri</th>
                    <th>Toplam Fiyat</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?> ₺</td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>
                            <form action="manage_orders.php" method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="form-control">
                                    <option value="Hazırlanıyor" <?php if ($order['status'] == 'Hazırlanıyor') echo 'selected'; ?>>Hazırlanıyor</option>
                                    <option value="Teslim Edildi" <?php if ($order['status'] == 'Teslim Edildi') echo 'selected'; ?>>Teslim Edildi</option>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Güncelle</button>
                            </form>
                        </td>
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
