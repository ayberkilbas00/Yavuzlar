<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Restoran ekleme sorgusu
    try {
        $sql = "INSERT INTO restaurants (name, description) VALUES (:name, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
        $success_message = "Restoran başarıyla eklendi!";
    } catch (Exception $e) {
        $error_message = "Restoran eklenirken bir hata oluştu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Restoran Ekle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .add-restaurant-container {
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
        }
        .alert {
            margin-bottom: 20px;
        }
        .btn-primary {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container add-restaurant-container">
        <h2 class="mb-4 text-center">Yeni Restoran Ekle</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="restaurant_add.php" method="POST">
            <div class="form-group">
                <label for="name">Restoran Adı:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Ekle</button>
                <a href="manage_restaurants.php" class="btn btn-secondary">Geri Dön</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
