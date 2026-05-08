<?php
session_start();
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT MaNguoiDung, TenNguoiDung, Email, MatKhau FROM NguoiDung WHERE Email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && $password == $user['MatKhau']) {
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']   = $user['MaNguoiDung'];
            $_SESSION['ho_ten']    = $user['TenNguoiDung'];
            $_SESSION['role_id']   = 4; 

            header("Location: ../nhakhoa.php");
            exit;
        } else {
            header("Location: dangnhap.php?error=Tài khoản hoặc mật khẩu không chính xác");
            exit;
        }
    } catch (Exception $e) {
        header("Location: ../dangnhap.php?error=Lỗi hệ thống");
        exit;
    }
}