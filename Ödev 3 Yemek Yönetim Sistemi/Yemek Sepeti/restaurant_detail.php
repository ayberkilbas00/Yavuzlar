<?php
session_start();
include 'db.php';

// Restoran ID'sini alıyoruz
if (!isset($_GET['id'])) {
    echo "Restoran ID'si bulunamadı.";
    exit();
}

$restaurant_id = $_GET['id'];

// Restoran bilgilerini çekiyoruz
$sql = "SELECT * FROM restaurants WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $restaurant_id]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    echo "Restoran bulunamadı.";
    exit();
}

// Ortalama puanı hesaplıyoruz
$sql = "SELECT AVG(rating) as avg_rating, COUNT(id) as review_count FROM reviews WHERE restaurant_id = :restaurant_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':restaurant_id' => $restaurant_id]);
$review_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Yorumları çekiyoruz
$sql = "SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.restaurant_id = :restaurant_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':restaurant_id' => $restaurant_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Detayları</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4"><?php echo htmlspecialchars($restaurant['name']); ?></h2>
        <p><?php echo htmlspecialchars($restaurant['description']); ?></p>

        <!-- Ortalama puanı göster -->
        <h4>Ortalama Puan: 
            <?php echo $review_data['review_count'] > 0 ? round($review_data['avg_rating'], 1) : 'Henüz puanlanmamış'; ?>
            (<?php echo $review_data['review_count']; ?> Yorum)
        </h4>

        <h4 class="mt-4">Yorumlar</h4>
        <ul class="list-group">
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $review): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($review['name']); ?></strong> - 
                        <span><?php echo htmlspecialchars($review['rating']); ?>/5</span>
                        <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">Henüz yorum yapılmamış.</li>
            <?php endif; ?>
        </ul>

        <!-- Yorum yapma formu -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <h4 class="mt-4">Yorum Yap ve Puan Ver</h4>
            <form action="submit_review.php" method="POST">
                <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>">
                <div class="form-group">
                    <label for="rating">Puan:</label>
                    <select name="rating" id="rating" class="form-control" required>
                        <option value="1">1 Yıldız</option>
                        <option value="2">2 Yıldız</option>
                        <option value="3">3 Yıldız</option>
                        <option value="4">4 Yıldız</option>
                        <option value="5">5 Yıldız</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">Yorum:</label>
                    <textarea name="comment" id="comment" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Yorum Gönder</button>
            </form>
        <?php else: ?>
            <p>Yorum yapmak için <a href="login.php">giriş yapmalısınız</a>.</p>
        <?php endif; ?>

        <a href="customer_dashboard.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
