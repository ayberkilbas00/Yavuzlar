<?php
session_start();
include 'db.php';  // Veritabanı bağlantısı

// Kullanıcı rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'firma') {
    header("Location: login.php");
    exit();
}

// Firma ID'sini al
$firma_id = $_SESSION['user_id'];

// Firma bilgilerini çekme sorgusu
$sql = "SELECT * FROM restaurants WHERE owner_id = :owner_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':owner_id' => $firma_id]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

// Firma bilgilerini güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_restaurant'])) {
    $restaurant_name = $_POST['restaurant_name'];
    $description = $_POST['description'];

    try {
        // Firma bilgilerini güncelleme sorgusu
        $sql_update = "UPDATE restaurants SET name = :name, description = :description WHERE owner_id = :owner_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':name' => $restaurant_name,
            ':description' => $description,
            ':owner_id' => $firma_id
        ]);

        // Güncelleme sonrası başarı mesajı
        echo "<div class='alert alert-success' role='alert'>Profil başarıyla güncellendi!</div>";

        // Güncellenen bilgileri tekrar al
        $sql = "SELECT * FROM restaurants WHERE owner_id = :owner_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':owner_id' => $firma_id]);
        $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Güncelleme hatası: " . $e->getMessage() . "</div>";
    }
}

// Menü ekleme işlemi (fotoğraf yükleme dahil)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_dish'])) {
    $dish_name = $_POST['dish_name'];
    $price = $_POST['price'];
    $description = $_POST['dish_description'];

    // Fotoğraf yükleme işlemi
    if (isset($_FILES['dish_image']) && $_FILES['dish_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['dish_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Dosya tipi kontrolü
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['dish_image']['tmp_name'], $target_file)) {
                $image_path = $target_file;  // Dosya başarıyla yüklendi
            } else {
                echo "<div class='alert alert-danger' role='alert'>Fotoğraf yüklenirken bir hata oluştu.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Sadece JPG, JPEG, PNG ve GIF dosya türleri kabul edilmektedir.</div>";
        }
    } else {
        $image_path = null;  // Görsel yoksa null olarak işaretlenir
    }

    try {
        // Yemek ekleme sorgusu
        $sql_add = "INSERT INTO dishes (restaurant_id, name, price, description, image) VALUES (:restaurant_id, :name, :price, :description, :image)";
        $stmt_add = $pdo->prepare($sql_add);
        $stmt_add->execute([
            ':restaurant_id' => $restaurant['id'],
            ':name' => $dish_name,
            ':price' => $price,
            ':description' => $description,
            ':image' => $image_path
        ]);

        echo "<div class='alert alert-success' role='alert'>Yemek başarıyla eklendi!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Yemek eklenirken bir hata oluştu: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Paneli</title>
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
        .container {
            max-width: 800px;
        }
        .card-title {
            font-size: 1.5rem;
        }
        .btn-primary, .btn-success, .btn-info, .btn-warning, .btn-danger {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Firma Yönetim Paneli</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="manage_menu.php">Menü Yönetimi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_coupons.php">Kupon Yönetimi</a>
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

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Firma Paneli</h2>

        <!-- Firma Bilgilerini Göster -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h4>
                <p class="card-text">Açıklama: <?php echo htmlspecialchars($restaurant['description']); ?></p>
            </div>
        </div>

        <!-- Firma Bilgilerini Düzenleme Formu -->
        <h4 class="mb-3">Firma Profilini Düzenle</h4>
        <form action="firma_dashboard.php" method="POST">
            <div class="form-group">
                <label for="restaurant_name">Firma Adı:</label>
                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($restaurant['description']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="update_restaurant">Güncelle</button>
        </form>

        <!-- Menü Ekleme Formu -->
        <h4 class="mt-5">Yeni Yemek Ekle</h4>
        <form action="firma_dashboard.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="dish_name">Yemek Adı:</label>
                <input type="text" class="form-control" id="dish_name" name="dish_name" required>
            </div>
            <div class="form-group">
                <label for="price">Fiyat:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="dish_description">Açıklama:</label>
                <textarea class="form-control" id="dish_description" name="dish_description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="dish_image">Yemek Görseli:</label>
                <input type="file" class="form-control-file" id="dish_image" name="dish_image">
            </div>
            <button type="submit" class="btn btn-success btn-block" name="add_dish">Yemek Ekle</button>
        </form>

        <!-- Firma İşlemleri -->
        <h4 class="mt-5">Firma İşlemleri</h4>
        <a href="manage_menu.php" class="btn btn-success btn-block">Menüyü Yönet</a>
        <a href="manage_coupons.php" class="btn btn-info btn-block">Kuponları Yönet</a>
        <a href="order_history.php" class="btn btn-warning btn-block">Sipariş Geçmişi</a>
        <a href="logout.php" class="btn btn-danger btn-block">Çıkış Yap</a>
    </div>

    <footer class="text-center py-4 mt-5">
        <p>&copy; 2024 Firma Yönetim Sistemi. Tüm Hakları Saklıdır.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
