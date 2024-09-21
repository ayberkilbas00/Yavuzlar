<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Eğer firma rolü seçilmişse firma adını al
    $company_name = ($role == 'firma') ? $_POST['company_name'] : null;

    // Şifreyi Argon2ID ile hashle
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

    try {
        // Kullanıcıyı veritabanına ekle
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashed_password,
            ':role' => $role
        ]);

        // Eğer rol firma ise restoranı veritabanına ekle
        if ($role == 'firma') {
            $user_id = $pdo->lastInsertId();  // Eklenen kullanıcının ID'sini al

            $sql = "INSERT INTO restaurants (name, owner_id) VALUES (:name, :owner_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $company_name,
                ':owner_id' => $user_id
            ]);
        }

        echo "<div class='alert alert-success' role='alert'>Kayıt başarılı! Giriş yapabilirsiniz.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Kayıt hatası: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Firma rolü seçildiğinde firma adı giriş alanını göster
        function toggleCompanyName() {
            var role = document.getElementById("role").value;
            var companyNameField = document.getElementById("company_name_field");
            if (role === "firma") {
                companyNameField.style.display = "block";
            } else {
                companyNameField.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Kayıt Ol</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="name">Ad Soyad:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Kullanıcı Rolü:</label>
                <select class="form-control" id="role" name="role" onchange="toggleCompanyName()" required>
                    <option value="firma">Firma</option>
                    <option value="müşteri">Müşteri</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <!-- Firma adı girişi sadece firma rolü seçildiğinde gösterilecek -->
            <div class="form-group" id="company_name_field" style="display:none;">
                <label for="company_name">Firma Adı:</label>
                <input type="text" class="form-control" id="company_name" name="company_name">
            </div>
            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
            <a href="login.php" class="btn btn-link">Zaten hesabınız var mı? Giriş Yapın</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
