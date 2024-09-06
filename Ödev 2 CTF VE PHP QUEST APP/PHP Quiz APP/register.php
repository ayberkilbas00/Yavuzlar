<?php
include 'database.php';
session_start();

if(isset($_POST['register'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role']; // Admin veya öğrenci

    // Şifreyi hashleme
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Kullanıcı adı kontrolü
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
        $error = "Bu kullanıcı adı zaten alınmış.";
    } else {
        // Kullanıcıyı ekleme
        $stmt = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $hashed_password, $role);
        if($stmt->execute()){
            $_SESSION['message'] = "Kayıt başarılı. Giriş yapabilirsiniz.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Kayıt başarısız.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Kayıt Ol</h2>
        <?php if(isset($error)) echo '<p style="color:red;">'.$error.'</p>'; ?>
        <form method="post" action="register.php">
            <p>
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" required>
            </p>
            <p>
                <label>Şifre:</label>
                <input type="password" name="password" required>
            </p>
            <p>
                <label>Rol:</label>
                <select name="role" required>
                    <option value="Student">Öğrenci</option>
                    <option value="Admin">Admin</option>
                </select>
            </p>
            <input type="submit" name="register" value="Kayıt Ol">
        </form>
        <p>Hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
    </div>
</body>
</html>
