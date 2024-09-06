<?php
include 'database.php';
session_start();

// Admin kontrolü
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin'){
    header("Location: login.php");
    exit();
}

// Öğrencilerin toplam skorlarını hesaplama
$query = "
    SELECT u.username, COUNT(s.correct) as score
    FROM users u
    JOIN submissions s ON u.id = s.user_id
    WHERE s.correct = 1 AND u.role = 'Student'
    GROUP BY u.id
    ORDER BY score DESC
";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Scoreboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Paneli - Scoreboard</h1>
            <p>Hoşgeldin, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Çıkış Yap</a></p>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Scoreboard</h2>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Sıra</th>
                    <th>Öğrenci Adı</th>
                    <th>Skor</th>
                </tr>
                <?php
                $rank = 1;
                while($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['score']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</body>
</html>
