<?php
session_start();
include 'db.php';

// Veritabanından menüleri çek
$sql = "SELECT d.id, d.name AS dish_name, d.price, d.description, d.image, r.name AS restaurant_name 
        FROM dishes d
        JOIN restaurants r ON d.restaurant_id = r.id";
$stmt = $pdo->query($sql);
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Yönetim Sistemi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .menu-card {
            margin-bottom: 20px;
        }
        .menu-card img {
            height: 200px;
            object-fit: cover;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Yemek Yönetim Sistemine Hoşgeldiniz!</h1>
        <p>Firmaların yayınladığı menüleri keşfedin ve favori restoranınızı seçin!</p>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Yayınlanan Menüler</h2>

        <div class="row">
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4">
                    <div class="card menu-card">
                        <!-- Menü görseli -->
                        <img src="<?php echo !empty($menu['image']) ? htmlspecialchars($menu['image']) : 'images/default.jpg'; ?>" class="card-img-top" alt="Yemek Görseli">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($menu['dish_name']); ?></h5>
                            <p class="card-text">Fiyat: <?php echo htmlspecialchars($menu['price']); ?> ₺</p>
                            <p class="card-text"><?php echo htmlspecialchars($menu['description']); ?></p>
                            <p class="card-text"><strong>Restoran:</strong> <?php echo htmlspecialchars($menu['restaurant_name']); ?></p>
                            <!-- Menüye tıklayınca giriş sayfasına yönlendirme -->
                            <a href="login.php" class="btn btn-primary">Sipariş Ver</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container text-center mt-5">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
            <!-- Kullanıcı oturum açmışsa ek seçenekler -->
            <p>Hoşgeldin, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <a href="logout.php" class="btn btn-danger">Çıkış Yap</a>
        <?php else: ?>
            <!-- Oturum açmamış kullanıcılar için -->
            <p>Menülere erişim sağlamak için lütfen giriş yapın.</p>
            <a href="login.php" class="btn btn-primary">Giriş Yap</a>
            <a href="register.php" class="btn btn-success">Kayıt Ol</a>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
