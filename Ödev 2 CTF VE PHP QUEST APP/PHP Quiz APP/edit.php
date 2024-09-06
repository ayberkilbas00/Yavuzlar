<?php
include 'database.php';
session_start();

// Admin kontrolü
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin'){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: admin_dashboard.php");
    exit();
}

$question_id = $_GET['id'];

$query = "SELECT * FROM questions WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $question_id);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

if(!$question){
    header("Location: admin_dashboard.php");
    exit();
}

// get question
$query = "SELECT * FROM choices WHERE question_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $question_id);
$stmt->execute();
$choices_result = $stmt->get_result();
$choices = [];
while($row = $choices_result->fetch_assoc()){
    $choices[] = $row;
}

// update
if(isset($_POST['update'])){
    $question_number = $_POST['question_number'];
    $question_text = $_POST['question_text'];
    $correct_choice = $_POST['correct_choice'];
    $choice_texts = $_POST['choice'];

    // Soru güncelleme
    $query = "UPDATE questions SET question_number = ?, text = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('isi', $question_number, $question_text, $question_id);
    if($stmt->execute()){
        // Update questions
        foreach($choices as $index => $choice){
            $choice_id = $choice['id'];
            $text = $choice_texts[$index];
            $is_correct = ($correct_choice == $choice_id) ? 1 : 0;

            $query = "UPDATE choices SET text = ?, is_correct = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sii', $text, $is_correct, $choice_id);
            $stmt->execute();
        }
        $_SESSION['message'] = "Soru başarıyla güncellendi.";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Güncelleme başarısız.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Soru Düzenle</title>
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
            <h2>Soru Düzenle</h2>
            <?php if(isset($error)) echo '<p style="color:red;">'.$error.'</p>'; ?>
            <form method="post" action="edit.php?id=<?php echo $question_id; ?>">
                <p>
                    <label>Soru Numarası:</label>
                    <input type="number" name="question_number" value="<?php echo $question['question_number']; ?>" required>
                </p>
                <p>
                    <label>Soru Metni:</label>
                    <input type="text" name="question_text" value="<?php echo $question['text']; ?>" required>
                </p>
                <?php foreach($choices as $index => $choice): ?>
                    <p>
                        <label>Seçenek #<?php echo $index + 1; ?>:</label>
                        <input type="text" name="choice[]" value="<?php echo $choice['text']; ?>" required>
                    </p>
                <?php endforeach; ?>
                <p>
                    <label>Doğru Seçenek ID:</label>
                    <input type="number" name="correct_choice" value="<?php
                        foreach($choices as $choice){
                            if($choice['is_correct'] == 1){
                                echo $choice['id'];
                            }
                        }
                    ?>" required>
                </p>
                <input type="submit" name="update" value="Güncelle">
            </form>
        </div>
    </main>
</body>
</html>
