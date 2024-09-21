<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'müşteri') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $restaurant_id = $_POST['restaurant_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    // Yorum ekleme sorgusu
    $sql = "INSERT INTO reviews (restaurant_id, user_id, rating, comment) VALUES (:restaurant_id, :user_id, :rating, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':restaurant_id' => $restaurant_id,
        ':user_id' => $user_id,
        ':rating' => $rating,
        ':comment' => $comment
    ]);

    echo "<div class='alert alert-success' role='alert'>Yorumunuz başarıyla eklendi!</div>";
}

// Restoran ID'sini almak
$restaurant_id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorum Yap</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Restorana Yorum Yap</h2>
        <form action="restaurant_reviews.php" method="POST">
            <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($restaurant_id); ?>">
            <div class="form-group">
                <label for="rating">Puan (1-10):</label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label for="comment">Yorum:</label>
                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gönder</button>
        </form>
        <a href="customer_dashboard.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
