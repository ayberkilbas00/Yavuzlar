<?php
session_start();
include 'db.php';  // Veritabanı bağlantısı

// Müşteri rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'müşteri') {
    header("Location: login.php");
    exit();
}

// Eğer arama yapılmışsa sorguyu al
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Restoranların menülerini çek
$sql = "SELECT d.id, d.name AS dish_name, d.price, d.description, d.image, r.name AS restaurant_name, r.id AS restaurant_id 
        FROM dishes d
        JOIN restaurants r ON d.restaurant_id = r.id";

// Eğer bir arama sorgusu varsa, restoran adına göre filtreleme yap
if (!empty($search_query)) {
    $sql .= " WHERE r.name LIKE :search_query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search_query' => '%' . $search_query . '%']);
} else {
    $stmt = $pdo->query($sql);
}

$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteri Paneli</title>
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
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            background-color: #f8f9fa;
        }
        .card-title {
            font-weight: bold;
            color: #343a40;
        }
        .menu-section {
            padding-top: 40px;
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
                    <a class="nav-link" href="order_history.php">Sipariş Geçmişi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Sepetim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="balance.php">Bakiyem</a>
                </li> <!-- Bakiye Yükleme Butonu -->
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="logout.php">Çıkış Yap</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <div class="container menu-section">
        <h2 class="text-center mb-5">Restoranların Menüleri</h2>

        <!-- Restoran Arama Formu -->
        <form method="GET" action="customer_dashboard.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Restoran ara..." value="<?php echo htmlspecialchars($search_query); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Ara</button>
                </div>
            </div>
        </form>

        <div class="row">
            <?php if (empty($menus)): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">Menü bulunamadı.</div>
                </div>
            <?php else: ?>
                <?php foreach ($menus as $menu): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if ($menu['image']): ?>
                                <img src="<?php echo htmlspecialchars($menu['image']); ?>" class="card-img-top" alt="Yemek Görseli">
                            <?php else: ?>
                                <img src="images/default.jpg" class="card-img-top" alt="Varsayılan Görsel">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($menu['dish_name']); ?></h5>
                                <p class="card-text">
                                    <strong>Restoran:</strong> 
                                    <?php echo htmlspecialchars($menu['restaurant_name']); ?>
                                </p>
                                <p class="card-text"><strong>Fiyat:</strong> <?php echo htmlspecialchars($menu['price']); ?> ₺</p>
                                <p class="card-text"><?php echo htmlspecialchars($menu['description']); ?></p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="cart_add.php?dish_id=<?php echo $menu['id']; ?>" class="btn btn-primary">Sepete Ekle</a>
                                <a href="restaurant_detail.php?id=<?php echo $menu['restaurant_id']; ?>" class="btn btn-secondary">Restoran Detayları</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer class="text-center py-4">
        <p class="mb-0">&copy; 2024 Yemek Yönetim Sistemi. Tüm Hakları Saklıdır.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
