<?php
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
    <title>Restoran Listesi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Restoranlar</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Restoran Adı</th>
                    <th>Açıklama</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($restaurants as $restaurant): ?>
                    <tr>
                        <td><?php echo $restaurant['name']; ?></td>
                        <td><?php echo $restaurant['description']; ?></td>
                        <td>
                            <a href="restaurant_edit.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                            <a href="restaurant_delete.php?id=<?php echo $restaurant['id']; ?>" class="btn btn-sm btn-danger">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Geri Dön</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
