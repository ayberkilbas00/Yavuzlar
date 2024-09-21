<?php
$host = 'mysql_db';  // Docker Compose'taki veritabanı servisi ismi olabilir
$dbname = 'restaurant_management';
$username = 'user';
$password = 'user_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8';");
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
    exit();
}
