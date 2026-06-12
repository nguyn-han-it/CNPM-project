<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../dangnhap/dangnhap.php?error=Vui lòng đăng nhập để gửi yêu cầu");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ma_khach_hang = $_SESSION['user_id'];
    $ma_danh_muc   = $_POST['ma_danh_muc'] ?? null;
    $tieu_de       = trim($_POST['tieu_de']);
    $noi_dung      = trim($_POST['noi_dung']);
    $trang_thai    = 'Chờ Xử Lý';
    if (empty($ma_danh_muc) || empty($tieu_de) || empty($noi_dung)) {
        header("Location: guiyeucau.php?error=Vui lòng điền đầy đủ các thông tin và chọn phân loại");
        exit;
    }
    try {
        $stmt = $pdo->prepare("
            INSERT INTO BangYeuCau (MaKhachHang, MaDanhMuc, TieuDe, NoiDung, TrangThai)
            VALUES (:ma_khach_hang, :ma_danh_muc, :tieu_de, :noi_dung, :trang_thai)
        ");

        $stmt->execute([
            'ma_khach_hang' => $ma_khach_hang,
            'ma_danh_muc'   => $ma_danh_muc,
            'tieu_de'       => $tieu_de,
            'noi_dung'      => $noi_dung,
            'trang_thai'    => $trang_thai
        ]);
        header("Location: guiyeucau.php?success=" . urlencode("Yêu cầu của bạn đã được tiếp nhận!"));
        exit;

    } catch (Exception $e) {
        header("Location: guiyeucau.php?error=Lỗi SQL: " . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: guiyeucau.php");
    exit;
}
?>