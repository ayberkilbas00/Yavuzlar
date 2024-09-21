<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $coupon_id = $_POST['id'];
    $code = $_POST['code'];
    $discount = $_POST['discount'];
    $expires_at = $_POST['expires_at'];

    // Kuponu güncelleme sorgusu
    $sql = "UPDATE coupons SET code = :code, discount = :discount, expires_at = :expires_at WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':code' => $code,
        ':discount' => $discount,
        ':expires_at' => $expires_at,
        ':id' => $coupon_id
    ]);

    echo "<div class='alert alert-success' role='alert'>Kupon güncellendi!</div>";
}

// Kupon bilgilerini çek
$coupon_id = $_GET['id'];
$sql = "SELECT * FROM coupons WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $coupon_id]);
$coupon = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kupon Düzenle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Kupon Düzenle</h2>
        <form action="coupon_edit.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($coupon['id']); ?>">
            <div class="form-group">
                <label for="code">Kupon Kodu:</label>
                <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($coupon['code']); ?>" required>
            </div>
            <div class="form-group">
                <label for="discount">İndirim Yüzdesi (%):</label>
                <input type="number" class="form-control" id="discount" name="discount" step="0.01" value="<?php echo htmlspecialchars($coupon['discount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="expires_at">Son Kullanma Tarihi:</label>
                <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($coupon['expires_at']))); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="manage_coupons.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
