<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "UPDATE orders SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':id' => $order_id
    ]);

    echo "<div class='alert alert-success' role='alert'>Sipariş durumu güncellendi!</div>";
}

// Sipariş bilgilerini çek
$order_id = $_GET['id'];
$sql = "SELECT * FROM orders WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Durumunu Güncelle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sipariş Durumunu Güncelle</h2>
        <form action="order_update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($order['id']); ?>">
            <div class="form-group">
                <label for="status">Durum:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Hazırlanıyor" <?php echo $order['status'] === 'Hazırlanıyor' ? 'selected' : ''; ?>>Hazırlanıyor</option>
                    <option value="Yola Çıktı" <?php echo $order['status'] === 'Yola Çıktı' ? 'selected' : ''; ?>>Yola Çıktı</option>
                    <option value="Teslim Edildi" <?php echo $order['status'] === 'Teslim Edildi' ? 'selected' : ''; ?>>Teslim Edildi</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="order_management.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
