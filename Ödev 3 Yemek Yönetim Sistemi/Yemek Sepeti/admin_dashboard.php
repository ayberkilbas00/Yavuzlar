<?php
session_start();
include 'db.php';

// Kullanıcı rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-panel {
            margin-top: 50px;
        }
        .admin-panel h2 {
            color: #343a40;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container admin-panel">
        <div class="text-center">
            <h2>Admin Paneli</h2>
            <p>Sistem yönetimi için gerekli işlemleri buradan gerçekleştirebilirsiniz.</p>
        </div>

        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Kullanıcı Yönetimi
                    </div>
                    <div class="card-body">
                        <p class="card-text">Kullanıcı hesaplarını yönetebilir, yeni kullanıcılar ekleyebilir veya mevcut kullanıcıları düzenleyebilirsiniz.</p>
                        <a href="manage_users.php" class="btn btn-primary">Kullanıcıları Yönet</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Restoran Yönetimi
                    </div>
                    <div class="card-body">
                        <p class="card-text">Restoranların bilgilerini görüntüleyebilir, yeni restoranlar ekleyebilir ve var olan restoranları yönetebilirsiniz.</p>
                        <a href="manage_restaurants.php" class="btn btn-secondary">Restoranları Yönet</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Kupon Yönetimi
                    </div>
                    <div class="card-body">
                        <p class="card-text">Kupon kodlarını oluşturabilir, düzenleyebilir veya mevcut kuponları silebilirsiniz.</p>
                        <a href="manage_coupons.php" class="btn btn-info">Kuponları Yönet</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="logout.php" class="btn btn-danger">Çıkış Yap</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
