<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'firma') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dish_id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "UPDATE dishes SET name = :name, price = :price, description = :description WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':description' => $description,
        ':id' => $dish_id
    ]);

    echo "<div class='alert alert-success' role='alert'>Yemek güncellendi!</div>";
}

// Yemek bilgilerini çek
$dish_id = $_GET['id'];
$sql = "SELECT * FROM dishes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $dish_id]);
$dish = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Düzenle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Yemek Düzenle</h2>
        <form action="menu_edit.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($dish['id']); ?>">
            <div class="form-group">
                <label for="name">Yemek Adı:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($dish['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Fiyat:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($dish['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($dish['description']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="menu_management.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
