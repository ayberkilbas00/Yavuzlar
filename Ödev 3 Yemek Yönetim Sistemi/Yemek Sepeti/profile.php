<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT name, email, profile_pic FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Bilgileri güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Şifreyi kontrol et
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':id' => $_SESSION['user_id']
        ]);
    } else {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':id' => $_SESSION['user_id']
        ]);
    }

    // Profil resmi güncelleme
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Yüklenen dosyanın resim olup olmadığını kontrol et
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check !== false) {
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE users SET profile_pic = :profile_pic WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':profile_pic' => basename($_FILES["profile_pic"]["name"]), ':id' => $_SESSION['user_id']]);
                } else {
                    echo "Dosya yüklenirken bir hata oluştu.";
                }
            } else {
                echo "Yalnızca JPG, JPEG, PNG ve GIF dosyaları kabul edilir.";
            }
        } else {
            echo "Yüklenen dosya bir resim değil.";
        }
    }

    echo "<div class='alert alert-success'>Bilgileriniz başarıyla güncellendi!</div>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Bilgileri</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-brand:hover, .navbar-nav .nav-link:hover {
            color: #ffce45;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="customer_dashboard.php">Yemek Yönetim Sistemi</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="customer_dashboard.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Sepetim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order_history.php">Sipariş Geçmişi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profil Bilgileri -->
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Profil Bilgileriniz</h2>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="text-center mb-4">
                <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profil Resmi" class="profile-pic">
            </div>
            <div class="form-group">
                <label for="name">Ad Soyad:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Yeni Şifre (isteğe bağlı):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="profile_pic">Profil Resmi (isteğe bağlı):</label>
                <input type="file" class="form-control" id="profile_pic" name="profile_pic">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Güncelle</button>
        </form>

        <div class="text-center mt-4">
            <a href="customer_dashboard.php" class="btn btn-secondary">Geri Dön</a>
        </div>
    </div>

    <footer class="text-center py-4 mt-5">
        <p>&copy; 2024 Yemek Yönetim Sistemi. Tüm Hakları Saklıdır.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
