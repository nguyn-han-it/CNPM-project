<?php
session_start();
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT MaNhanVien, TenNhanVien, Email, MatKhau, MaVaiTro FROM NhanVien WHERE Email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && $password == $user['MatKhau']) {
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']   = $user['MaNhanVien'];
            $_SESSION['ho_ten']    = $user['TenNhanVien'];
            $_SESSION['role_id']   = $user['MaVaiTro'];

            switch ($user['MaVaiTro']) {
                case 1: // Admin
                    header("Location: ../nhanvien/admin.php");
                    break;
                case 2: // Bác sĩ
                    header("Location: ../nhanien/bacsi.php");
                    break;
                case 3: // Nhân viên
                    header("Location: ../nhanvien/nhanvien.php");
                    break;
                default:
                    header("Location: dangnhap_nhanvien.php?error=Quyền truy cập không hợp lệ");
                    break;
            }
            exit;
        } else {
            header("Location: dangnhap_nhanvien.php?error=Sai thông tin đăng nhập hệ thống");
            exit;
        }
    } catch (Exception $e) {
        header("Location: dangnhap_nhanvien.php?error=Lỗi kết nối");
        exit;
    }
}