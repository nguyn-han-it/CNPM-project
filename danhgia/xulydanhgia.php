<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ten = $_POST['ten'];
    $sao = $_POST['sao'];
    $noidung = $_POST['noidung'];

    $stmt = $pdo->prepare("
        INSERT INTO danh_gia(ten, so_sao, noi_dung)
        VALUES (:ten, :sao, :noidung)
    ");

    $stmt->execute([
        'ten' => $ten,
        'sao' => $sao,
        'noidung' => $noidung
    ]);

    header("Location: nhakhoa.php");
    exit;
}
?>