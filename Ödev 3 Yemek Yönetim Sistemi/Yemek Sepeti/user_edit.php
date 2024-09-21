<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST işleminde kullanıcı ID'sini doğru şekilde alın
    if (isset($_POST['id'])) {
        $user_id = $_POST['id'];
    } else {
        echo "<div class='alert alert-danger'>Kullanıcı ID'si bulunamadı!</div>";
        exit();
    }
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':id' => $user_id
        ]);
        echo "<div class='alert alert-success' role='alert'>Kullanıcı bilgileri başarıyla güncellendi!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Güncelleme hatası: " . $e->getMessage() . "</div>";
    }
}

// GET ile kullanıcı bilgilerini al
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    echo "<div class='alert alert-danger' role='alert'>Kullanıcı bulunamadı!</div>";
    exit();
}

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='alert alert-danger' role='alert'>Kullanıcı bulunamadı!</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-top: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Kullanıcı Düzenle
            </div>
            <div class="card-body">
                <form action="user_edit.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <div class="form-group">
                        <label for="name">Ad Soyad:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rol:</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="firma" <?php echo $user['role'] === 'firma' ? 'selected' : ''; ?>>Firma</option>
                            <option value="müşteri" <?php echo $user['role'] === 'müşteri' ? 'selected' : ''; ?>>Müşteri</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                    <a href="manage_users.php" class="btn btn-secondary">Geri Dön</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
