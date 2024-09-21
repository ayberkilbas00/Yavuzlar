<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Firma ID'sini al
$firma_id = $_SESSION['user_id'];

// Siparişleri listeleme sorgusu
$sql = "SELECT * FROM orders WHERE restaurant_id = :restaurant_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':restaurant_id' => $firma_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h2 class="mb-4">Siparişler</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı ID</th>
                    <th>Toplam Fiyat</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo $order['user_id']; ?></td>
                        <td><?php echo $order['total_price']; ?></td>
                        <td><?php echo $order['status']; ?></td>
                        <td>
                            <a href="order_update.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">Durumu Güncelle</a>
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
