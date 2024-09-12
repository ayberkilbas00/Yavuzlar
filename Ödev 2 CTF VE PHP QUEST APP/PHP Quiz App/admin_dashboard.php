<?php
include 'database.php';
session_start();

// Admin kontrolü
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Soruları çekme
$query = "SELECT * FROM questions ORDER BY question_number ASC";
$questions = $mysqli->query($query) or die($mysqli->error.__LINE__);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Paneli</h1>
            <p>Hoşgeldin, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Çıkış Yap</a></p>
        </div>
    </header>
    <main>
        <div class="container">
            <h2>Soru Yönetimi</h2>
            <a href="add.php" class="adminpanel">Soru Ekle</a>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Soru Numarası</th>
                    <th>Soru Metni</th>
                    <th>Puan</th>
                    <th>İşlemler</th>
                </tr>
                <?php while($row = $questions->fetch_assoc()): ?>
                    <tr class="question-row">
                        <td><?php echo $row['question_number']; ?></td>
                        <td><?php echo $row['text']; ?></td>
                        <td><?php echo $row['score']; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>">Düzenle</a> | 
                            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</body>
</html>
