<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Restoranları listeleme sorgusu
$sql = "SELECT * FROM restaurants WHERE deleted_at IS NULL";
$stmt = $pdo->query($sql);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .management-container {
            margin-top: 50px;
        }
        .management-container h2 {
            color: #343a40;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .btn-warning {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container management-container">
        <div class="text-center mb-4">
            <h2>Restoran Yönetimi</h2>
            <p>Sistemde kayıtlı restoranları görüntüleyin, düzenleyin veya silin.</p>
        </div>

        <div class="text-right mb-3">
            <a href="restaurant_add.php" class="btn btn-primary">Yeni Restoran Ekle</a>
        </div>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad</th>
                    <th>Açıklama</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($restaurants as $restaurant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($restaurant['id']); ?></td>
                        <td><?php echo htmlspecialchars($restaurant['name']); ?></td>
                        <td><?php echo htmlspecialchars($restaurant['description']); ?></td>
                        <td>
                            <a href="restaurant_edit.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                            <a href="restaurant_delete.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu restoranı silmek istediğinize emin misiniz?')">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Geri Dön</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
