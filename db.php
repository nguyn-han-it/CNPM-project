<?php
// db.php
$host = 'localhost';
$dbname = 'nhakhoadb';
$user = 'root';
$pass = '';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user,$pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>