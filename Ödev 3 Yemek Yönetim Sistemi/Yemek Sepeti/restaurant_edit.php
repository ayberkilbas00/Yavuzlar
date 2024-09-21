<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $restaurant_id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    try {
        // Restoran bilgilerini güncelleme sorgusu
        $sql = "UPDATE restaurants SET name = :name, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':id' => $restaurant_id
        ]);
        $success_message = "Restoran bilgileri başarıyla güncellendi!";
    } catch (Exception $e) {
        $error_message = "Güncelleme sırasında bir hata oluştu: " . $e->getMessage();
    }
}

// Restoran bilgilerini çek
$restaurant_id = $_GET['id'];
$sql = "SELECT * FROM restaurants WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $restaurant_id]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Düzenle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .edit-restaurant-container {
            margin-top: 50px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #343a40;
            margin-bottom: 20px;
        }
        .btn-primary {
            margin-right: 10px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container edit-restaurant-container">
        <h2 class="text-center">Restoran Düzenle</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="restaurant_edit.php?id=<?php echo htmlspecialchars($restaurant['id']); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($restaurant['id']); ?>">
            <div class="form-group">
                <label for="name">Restoran Adı:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($restaurant['description']); ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="manage_restaurants.php" class="btn btn-secondary">Geri Dön</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
