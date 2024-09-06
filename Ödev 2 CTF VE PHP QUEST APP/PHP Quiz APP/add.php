<?php
include 'database.php';
session_start();

// Admin kontrolü
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Son soru numarasını al
$query = "SELECT MAX(question_number) AS max_number FROM questions";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$next_question_number = $row['max_number'] + 1; // Bir sonraki soru numarası
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli - Soru Ekle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Paneli - Soru Ekle</h1>
            <p>Hoşgeldin, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Çıkış Yap</a></p>
        </div>
    </header>
    <main>
        <div class="container">
            <?php
            if (isset($_GET['msg'])) {
                echo '<p style="color: green;">' . htmlspecialchars($_GET['msg']) . '</p>';
            }
            ?>
            <form method="post" action="add_process.php">
                <p>
                    <label>Soru Numarası:</label>
                    <input type="number" name="question_number" value="<?php echo $next_question_number; ?>" readonly>
                </p>
                <p>
                    <label>Soru Metni:</label>
                    <input type="text" name="question_text" required>
                </p>
                <p>
                    <label>Seçenek 1:</label>
                    <input type="text" name="choice1" required>
                </p>
                <p>
                    <label>Seçenek 2:</label>
                    <input type="text" name="choice2" required>
                </p>
                <p>
                    <label>Seçenek 3:</label>
                    <input type="text" name="choice3">
                </p>
                <p>
                    <label>Seçenek 4:</label>
                    <input type="text" name="choice4">
                </p>
                <p>
                    <label>Doğru Seçenek Numarası:</label>
                    <input type="number" name="correct_choice" required>
                </p>
                <div class="button-group">
                    <input type="submit" value="Soruyu Ekle" id="btn">
                    <a href="admin_dashboard.php" id="btn">Admin Paneli</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
