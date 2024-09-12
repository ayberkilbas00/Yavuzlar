<?php
include 'database.php';
session_start();

// Öğrenci kontrolü
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Toplam puanı hesaplamak için
$query = "
    SELECT 
        COALESCE(SUM(q.score), 0) AS total_score
    FROM 
        submissions s
    INNER JOIN 
        questions q 
    ON 
        s.question_id = q.id
    WHERE 
        s.user_id = ? AND s.correct = 1
";
$stmt = $mysqli->prepare($query);

if ($stmt === false) {
    die('Prepare Error: ' . $mysqli->error);
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die('Execute Error: ' . $mysqli->error);
}

$row = $result->fetch_assoc();
$total_score = $row['total_score'] ?? 0;

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
            <p>Hoşgeldin, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php">Çıkış Yap</a></p>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Tebrikler!</h2>
            <p>Testi başarıyla tamamladınız.</p>
            <p>Skorunuz: <?php echo htmlspecialchars($total_score); ?></p>
            <a href="index.php" class="start">Tekrar Çöz</a>
        </div>
    </main>
</body>
</html>
