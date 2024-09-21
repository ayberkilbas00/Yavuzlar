<?php
session_start();
include 'db.php';

// Admin rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Kullanıcıları listeleme sorgusu
$sql = "SELECT * FROM users WHERE deleted_at IS NULL";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .user-management {
            margin-top: 50px;
        }
        .user-management h2 {
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
    <div class="container user-management">
        <div class="text-center">
            <h2>Kullanıcı Yönetimi</h2>
            <p>Sistem kullanıcılarını görüntüleyin, düzenleyin veya silin.</p>
        </div>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Ad Soyad</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                        <td>
                            <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                            <a href="user_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">Sil</a>
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
