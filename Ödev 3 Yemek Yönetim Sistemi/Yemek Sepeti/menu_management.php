<?php
session_start();
include 'db.php';

// Firma rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'firma') {
    header("Location: login.php");
    exit();
}

// Firma sahibinin restoranını bul
$sql = "SELECT id FROM restaurants WHERE owner_id = :owner_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':owner_id' => $_SESSION['user_id']]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

// Eğer firma sahibinin bir restoranı yoksa hata göster
if (!$restaurant) {
    echo "Restoran bulunamadı!";
    exit();
}

// Firma ID'si (restaurant_id)
$restaurant_id = $restaurant['id'];

// Yemek ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Görsel yükleme işlemi
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

        // Geçerli dosya formatlarını kontrol et (jpg, jpeg, png)
        $valid_extensions = ['jpg', 'jpeg', 'png'];
        if (in_array($image_ext, $valid_extensions)) {
            $new_image_name = uniqid() . "." . $image_ext;  // Benzersiz dosya ismi oluştur
            move_uploaded_file($image_tmp, "images/" . $new_image_name);  // Görseli sunucuya kaydet
        } else {
            echo "Geçersiz dosya formatı!";
            exit();
        }
    } else {
        $new_image_name = null;  // Görsel yüklenmemişse null değer kullan
    }

    // Yemek ekleme sorgusu
    $sql = "INSERT INTO dishes (restaurant_id, name, price, description, image) VALUES (:restaurant_id, :name, :price, :description, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':restaurant_id' => $restaurant_id,
        ':name' => $name,
        ':price' => $price,
        ':description' => $description,
        ':image' => $new_image_name
    ]);

    // Form gönderildikten sonra sayfayı yeniden yükleyin
    header("Location: menu_management.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menüyü Yönet</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Menü Yönetimi</h2>

        <!-- Yemek Ekleme Formu -->
        <form action="menu_management.php" method="POST" enctype="multipart/form-data">
            <h4>Yeni Yemek Ekle</h4>
            <div class="form-group">
                <label for="name">Yemek Adı:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Fiyat:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Yemek Görseli:</label>
                <input type="file" class="form-control-file" id="image" name="image" accept=".jpg,.jpeg,.png">
            </div>
            <button type="submit" class="btn btn-primary">Ekle</button>
        </form>

        <a href="firma_dashboard.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
