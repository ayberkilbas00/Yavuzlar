<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$sql = "SELECT r.*, u.name AS user_name, res.name AS restaurant_name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id
        JOIN restaurants res ON r.restaurant_id = res.id";
$stmt = $pdo->query($sql);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorum Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Yorumlar</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Yorum ID</th>
                    <th>Kullanıcı</th>
                    <th>Restoran</th>
                    <th>Puan</th>
                    <th>Yorum</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['id']); ?></td>
                        <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['restaurant_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['rating']); ?>/10</td>
                        <td><?php echo htmlspecialchars($review['comment']); ?></td>
                        <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                        <td>
                            <a href="review_delete.php?id=<?php echo $review['id']; ?>" class="btn btn-sm btn-danger">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-secondary">Geri Dön</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
