<?php
session_start();

// Öğrenci kontrolü
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Student'){
    header("Location: login.php");
    exit();
}

$score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;

unset($_SESSION['score']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Quiz Tamamlandı</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>PHP Quiz</h1>
            <p>Hoşgeldin, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Çıkış Yap</a></p>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Tebrikler!</h2>
            <p>Testi başarıyla tamamladınız.</p>
            <p>Skorunuz: <?php echo $score; ?></p>
            <a href="index.php" class="start">Tekrar Çöz</a>
        </div>
    </main>
</body>
</html>
