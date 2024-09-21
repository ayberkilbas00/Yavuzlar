<?php
session_start();
include 'db.php';

// Firma rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'firma') {
    header("Location: login.php");
    exit();
}

// Firma ID'sini al
$firma_id = $_SESSION['user_id'];
$sqlfirma = "SELECT id FROM restaurants WHERE owner_id = :owner_id";
$stmte = $pdo->prepare($sqlfirma);
$stmte->execute([':owner_id' => $firma_id]);
$frm = $stmte->fetch(PDO::FETCH_ASSOC);

// Firma sahibinin restoranına ait menüleri al
$sql = "SELECT * FROM dishes WHERE restaurant_id = :restaurant_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':restaurant_id' => $frm["id"]]);
$dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Yemek silme işlemi
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Siparişlerde bu yemeği kontrol et
    $sql = "SELECT * FROM order_items WHERE dish_id = :dish_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':dish_id' => $delete_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($order_items)) {
        echo "<div class='alert alert-danger'>Bu yemek siparişlerde kullanılıyor ve silinemiyor.</div>";
        exit();
    }

    // Yemek silme işlemi
    $sql = "DELETE FROM dishes WHERE id = :id AND restaurant_id = :restaurant_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $delete_id, ':restaurant_id' => $frm['id']]);

    // Silme işleminden sonra sayfayı yenileyin
    header("Location: manage_menu.php");
    exit();
}

// Yemek düzenleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dish_id = $_POST['dish_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Eğer fotoğraf yüklendiyse
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $image_name = basename($image['name']);
        $image_path = 'uploads/' . $image_name;

        // Resmi yükleme
        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            echo "<div class='alert alert-danger'>Fotoğraf yüklenirken bir hata oluştu.</div>";
        } else {
            // Eğer resim başarılı şekilde yüklendiyse, veritabanında güncelle
            $sql = "UPDATE dishes SET name = :name, price = :price, description = :description, image = :image WHERE id = :id AND restaurant_id = :restaurant_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':description' => $description,
                ':image' => $image_name,
                ':id' => $dish_id,
                ':restaurant_id' => $frm['id']
            ]);
        }
    } else {
        // Resim yüklenmediyse sadece diğer alanları güncelle
        $sql = "UPDATE dishes SET name = :name, price = :price, description = :description WHERE id = :id AND restaurant_id = :restaurant_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':description' => $description,
            ':id' => $dish_id,
            ':restaurant_id' => $frm['id']
        ]);
    }

    header("Location: manage_menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menü Yönetimi</title>
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
            max-width: 900px;
        }
        .card-title {
            font-size: 1.25rem;
        }
        .btn-primary, .btn-success, .btn-danger {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="firma_dashboard.php">Firma Paneli</a>
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
        <h2 class="mb-4 text-center">Menü Yönetimi (Düzenle & Sil)</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Yemek Adı</th>
                    <th>Fiyat</th>
                    <th>Açıklama</th>
                    <th>Fotoğraf</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dishes as $dish): ?>
                    <tr>
                        <form action="manage_menu.php" method="POST" enctype="multipart/form-data">
                            <td>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($dish['name']); ?>" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="price" step="0.01" value="<?php echo htmlspecialchars($dish['price']); ?>" required>
                            </td>
                            <td>
                                <textarea class="form-control" name="description" required><?php echo htmlspecialchars($dish['description']); ?></textarea>
                            </td>
                            <td>
                                <input type="file" class="form-control" name="image">
                                <small class="form-text text-muted">Mevcut Görsel: <?php echo htmlspecialchars($dish['image']); ?></small>
                            </td>
                            <td>
                                <input type="hidden" name="dish_id" value="<?php echo $dish['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Güncelle</button>
                                <a href="manage_menu.php?delete_id=<?php echo $dish['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu yemeği silmek istediğinize emin misiniz?')">Sil</a>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="firma_dashboard.php" class="btn btn-secondary">Geri Dön</a>
    </div>

    <footer class="text-center py-4">
        <p>&copy; 2024 Firma Yönetim Sistemi. Tüm Hakları Saklıdır.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
