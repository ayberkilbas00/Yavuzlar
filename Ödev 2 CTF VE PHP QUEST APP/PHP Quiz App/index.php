<?php
include 'database.php';
session_start();

// Öğrencimi
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Student'){
    header("Location: login.php");
    exit();
}

$query = "SELECT COUNT(*) as total FROM questions";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$row = $result->fetch_assoc();
$total = $row['total'];

// Estimated time (question * 0.5 minutes)
$estimated_time = $total * 0.5;

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Quiz Başlangıcı</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header> 
        <div class="container">
            <h1>PHP Quizzer</h1>
            <p>Hoşgeldin, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Çıkış Yap</a></p>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>PHP Bilginizi Test Edin</h2>
            <p>Bu, PHP bilginizi test etmek için hazırlanmış çoktan seçmeli bir testtir.</p>
            <ul>
                <li><strong>Soru Sayısı: </strong><?php echo $total; ?></li>
                <li><strong>Tür: </strong>Çoktan Seçmeli</li>
                <li><strong>Tahmini Süre: </strong><?php echo $estimated_time; ?> dakika</li>
            </ul>
            <a href="question.php?n=1" class="start">Quiz Başla</a>
        </div>
    </main>
</body>
</html>
