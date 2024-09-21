<?php
session_start();
include 'db.php';  // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $user_id = $_SESSION['user_id'];

    // Dosya yükleme işlemi
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Yüklenen dosyanın resim olup olmadığını kontrol et
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check !== false) {
        // Dosyanın uzantısına göre kontrol
        if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
            // Dosyayı yükle
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                // Veritabanında profil resmini güncelle
                $sql = "UPDATE users SET profile_pic = :profile_pic WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':profile_pic' => basename($_FILES["profile_pic"]["name"]), ':user_id' => $user_id]);

                // Başarılı bir şekilde yüklendiğinde kullanıcıyı profil sayfasına yönlendir
                header("Location: profile.php");
                exit();
            } else {
                echo "Dosya yüklenirken hata oluştu.";
            }
        } else {
            echo "Yalnızca JPG, JPEG, PNG ve GIF dosyaları kabul edilir.";
        }
    } else {
        echo "Yüklenen dosya bir resim değil.";
    }
}
?>
