<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);
    $so_dien_thoai = trim($_POST['so_dien_thoai']);
    $mat_khau = trim($_POST['mat_khau']);
    $nhap_lai_mat_khau = trim($_POST['nhap_lai_mat_khau']);

    if (empty($ho_ten) || empty($email) || empty($so_dien_thoai) || empty($mat_khau) || empty($nhap_lai_mat_khau)) {
        header("Location: dangky.php?error=Vui lòng nhập đầy đủ thông tin");
        exit;
    }

    if ($mat_khau != $nhap_lai_mat_khau) {
        header("Location: dangky.php?error=Mật khẩu nhập lại không khớp");
        exit;
    }

    try {
        $check = $pdo->prepare("SELECT MaNguoiDung FROM NguoiDung WHERE Email = :email");
        $check->execute(['email' => $email]);

        if ($check->fetch()) {
            header("Location: dangky.php?error=Email này đã được sử dụng");
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO NguoiDung (TenNguoiDung, Email, SDT, MatKhau)
            VALUES (:ho_ten, :email, :so_dien_thoai, :mat_khau)
        ");

        $stmt->execute([
            'ho_ten' => $ho_ten,
            'email' => $email,
            'so_dien_thoai' => $so_dien_thoai,
            'mat_khau' => $mat_khau 
        ]);

        header("Location: dangnhap.php?success=Đăng ký thành công, mời bạn đăng nhập");
        exit;

    } catch (Exception $e) {
        error_log($e->getMessage());
        header("Location: dangky.php?error=Lỗi hệ thống: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: dangky.php");
    exit;
}
?>