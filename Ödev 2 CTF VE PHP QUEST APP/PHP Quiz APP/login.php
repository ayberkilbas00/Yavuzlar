<?php
include 'database.php';
session_start();

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $stmt = $mysqli->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows == 1){
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();
        if(password_verify($password, $hashed_password)){
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if($role == 'Admin'){
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Yanlış şifre.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Giriş Yap</h2>
        <?php 
        if(isset($_SESSION['message'])){
            echo '<p style="color:green;">'.$_SESSION['message'].'</p>';
            unset($_SESSION['message']);
        }
        if(isset($error)) echo '<p style="color:red;">'.$error.'</p>'; 
        ?>
        <form method="post" action="login.php">
            <p>
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" required>
            </p>
            <p>
                <label>Şifre:</label>
                <input type="password" name="password" required>
            </p>
            <input type="submit" name="login" value="Giriş Yap">
        </form>
        <p>Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
    </div>
</body>
</html>
