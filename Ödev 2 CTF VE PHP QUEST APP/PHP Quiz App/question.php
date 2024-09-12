<?php
include 'database.php';
session_start();

// Öğrenci kontrolü
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Student'){
    header("Location: login.php");
    exit();
}

$number = (int) $_GET['n'];

$query = "SELECT COUNT(*) as total FROM questions";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
$row = $result->fetch_assoc();
$total = $row['total'];

// Öğrencinin daha önce çözdüğü soruları al
$user_id = $_SESSION['user_id'];
$query = "SELECT question_id FROM submissions WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$submissions_result = $stmt->get_result();
$solved_questions = [];
while($row = $submissions_result->fetch_assoc()){
    $solved_questions[] = $row['question_id'];
}

// tüm soruları çözdüyse final sayfasına yönlendir
if(count($solved_questions) == $total){
    header("Location: final.php");
    exit();
}

if(isset($_GET['n'])){
    $number = (int) $_GET['n'];
} else {
    $query = "SELECT id, question_number, text FROM questions WHERE id NOT IN (".implode(',', $solved_questions).") ORDER BY question_number ASC LIMIT 1";
    $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
    if($result->num_rows == 0){
        header("Location: final.php");
        exit();
    }
    $question = $result->fetch_assoc();
    header("Location: question.php?n=".$question['question_number']);
    exit();
}

$query = "SELECT * FROM questions WHERE question_number = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $number);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

if(!$question){
    header("Location: final.php");
    exit();
}

$query = "SELECT * FROM choices WHERE question_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $question['id']);
$stmt->execute();
$choices = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Quiz - Soru <?php echo $number; ?></title>
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
            <div class="current">Soru <?php echo $question['question_number']; ?> of <?php echo $total; ?></div>
            <p class="question"><?php echo $question['text']; ?></p>
            <form method="post" action="process.php">
                <ul class="choices">
                    <?php while($row = $choices->fetch_assoc()): ?>
                        <li>
                            <input name="choice" type="radio" value="<?php echo $row['id']; ?>" required>
                            <?php echo $row['text']; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <input type="submit" value="Gönder">
                <input type="hidden" name="number" value="<?php echo $number; ?>">
            </form>
        </div>
    </main>
</body>
</html>
