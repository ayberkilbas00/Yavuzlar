<?php
session_start();
include 'db.php';

// Kullanıcı giriş yapmış mı kontrol edelim
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kullanıcı ID'sini al
$user_id = $_SESSION['user_id'];

// Kullanıcının mevcut bakiyesini veritabanından çekelim
$sql = "SELECT balance FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Bakiye yükleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];  // Yüklenecek miktar

    if ($amount > 0) {
        // Kullanıcının bakiyesini güncelle
        $sql = "UPDATE users SET balance = balance + :amount WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':amount' => $amount,
            ':user_id' => $user_id
        ]);

        // İşlem başarılıysa sayfayı yenileyelim
        header("Location: balance.php");
        exit();
    } else {
        $error_message = "Lütfen geçerli bir miktar giriniz.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakiye Yükle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Bakiye Yükle</h2>

        <p>Mevcut Bakiyeniz: <strong><?php echo number_format($user['balance'], 2); ?> ₺</strong></p>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="balance.php" method="POST">
            <div class="form-group">
                <label for="amount">Yüklemek İstediğiniz Miktar (₺):</label>
                <input type="number" class="form-control" id="amount" name="amount" required min="1" step="0.01">
            </div>
            <button type="submit" class="btn btn-success">Bakiye Yükle</button>
        </form>

        <a href="customer_dashboard.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
