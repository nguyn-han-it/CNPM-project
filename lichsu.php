<?php
session_start();
require_once 'db.php'; // Đảm bảo đường dẫn đúng
$ma_khach_hang = $_SESSION['user_id'];

if (!isset($_SESSION['logged_in'])) {
    header("Location: dangnhap/dangnhap.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$ten_nguoi_dung = $_SESSION['ho_ten'] ?? '';

try {
    if ($role_id == 1 || $role_id == 2 || $role_id == 3) {
        $sql = "SELECT y.*, 
                       nd_kh.TenNguoiDung AS ten_khach_hang, 
                       ph.NoiDung AS noi_dung_phan_hoi, 
                       ph.NgayTao AS ngay_phan_hoi, 
                       nv_ph.TenNhanVien AS ten_nhan_su_phan_hoi,
                       ls.NoiDung AS nhat_ky_he_thong,
                       ls.ThoiGian AS ngay_chuyen_tiep
                FROM BangYeuCau y
                JOIN NguoiDung nd_kh ON y.MaKhachHang = nd_kh.MaNguoiDung
                LEFT JOIN BangPhanHoi ph ON y.MaYeuCau = ph.MaYeuCau
                LEFT JOIN NhanVien nv_ph ON ph.MaNhanVien = nv_ph.MaNhanVien
                LEFT JOIN LichsuXuly ls ON y.MaYeuCau = ls.MaYeuCau
                ORDER BY y.NgayTao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "SELECT y.*, 
                       ph.NoiDung AS noi_dung_phan_hoi, 
                       ph.NgayTao AS ngay_phan_hoi, 
                       nv_ph.TenNhanVien AS ten_nhan_su_phan_hoi,
                       ls.NoiDung AS nhat_ky_he_thong,
                       ls.ThoiGian AS ngay_chuyen_tiep
                FROM BangYeuCau y
                LEFT JOIN BangPhanHoi ph ON y.MaYeuCau = ph.MaYeuCau
                LEFT JOIN NhanVien nv_ph ON ph.MaNhanVien = nv_ph.MaNhanVien
                LEFT JOIN LichsuXuly ls ON y.MaYeuCau = ls.MaYeuCau
                WHERE y.MaKhachHang = ?
                ORDER BY y.NgayTao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
    }

    $danh_sach_lich_su = $stmt->fetchAll();

} catch (Exception $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}
if(isset($_POST['gui_danh_gia'])){

    $ma_yeu_cau = $_POST['ma_yeu_cau'];
    $so_sao = $_POST['so_sao'];
    $nhan_xet = $_POST['nhan_xet'];

    $sql_insert = "
    INSERT INTO BangDanhGia
    (
        SoSao,
        NhanXet,
        MaYeuCau,
        MaNguoiDung
    )
    VALUES
    (
        ?, ?, ?, ?
    )
    ";

    $stmt_insert = $pdo->prepare($sql_insert);

    $stmt_insert->execute([
        $so_sao,
        $nhan_xet,
        $ma_yeu_cau,
        $ma_khach_hang
    ]);

    echo "
    <script>
        alert('Đánh giá thành công');
        location.href='';
    </script>
    ";
}

$sql = "
SELECT *
FROM BangYeuCau
WHERE MaKhachHang = ?
ORDER BY NgayTao DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$ma_khach_hang]);

$danh_sach_yeu_cau = $stmt->fetchAll(); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch Sử Hệ Thống - Nha Khoa Việt Mỹ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .top-bar { background: #2c3e50; color: white; padding: 15px 0; }
        .card-history { border: none; border-radius: 15px; overflow: hidden; transition: 0.3s; }
        .card-history:hover { transform: scale(1.01); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .timeline-box { font-size: 0.85rem; background: #fffcf0; border-left: 3px solid #ffc107; padding: 10px 15px; margin: 10px 0; border-radius: 0 8px 8px 0; }
        .reply-box { background: #f0f7ff; border-left: 3px solid #0d6efd; padding: 15px; border-radius: 0 8px 8px 0; }
        .status-pill { font-size: 0.75rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body class="bg-light">

<div class="top-bar mb-4 shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="nhakhoa.php" class="text-white text-decoration-none fw-bold">🏥 NHA KHOA VIỆT MỸ</a>
        <span>Chào, <strong><?= htmlspecialchars($ten_nguoi_dung) ?></strong></span>
    </div>
</div>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-secondary">
            <i class="bi bi-clock-history"></i> 
            <?= ($role_id == 4) ? "Yêu cầu của tôi" : "Nhật ký xử lý hệ thống" ?>
        </h3>
        <a href="javascript:history.back()" class="btn btn-dark btn-sm rounded-pill px-3">Quay lại</a>
    </div>

    <?php if (empty($danh_sach_lich_su)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder2-open text-muted" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3">Chưa có dữ liệu nào được ghi nhận.</p>
        </div>
    <?php else: ?>
        <?php foreach ($danh_sach_lich_su as $item): ?>
            <div class="card card-history shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($item['TieuDe']) ?></h5>
                        <span class="badge status-pill <?php 
                            echo ($item['TrangThai'] == 'Hoàn Thành') ? 'bg-success' : 
                                 (($item['TrangThai'] == 'Đang Xử Lý') ? 'bg-info' : 'bg-warning text-dark'); 
                        ?>">
                            <?= htmlspecialchars($item['TrangThai']) ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <?php if ($role_id != 4): ?>
                            <span class="text-danger fw-bold me-3"><i class="bi bi-person"></i> <?= htmlspecialchars($item['ten_khach_hang']) ?></span>
                        <?php endif; ?>
                        <span class="text-muted small"><i class="bi bi-calendar3"></i> Gửi: <?= date('d/m/Y H:i', strtotime($item['NgayTao'])) ?></span>
                    </div>

                    <div class="bg-white border rounded p-3 mb-3">
                        <p class="card-text small mb-0"><?= nl2br(htmlspecialchars($item['NoiDung'])) ?></p>
                    </div>

                    <?php if (!empty($item['nhat_ky_he_thong']) && $role_id != 4): ?>
                        <div class="timeline-box shadow-sm">
                            <h6 class="fw-bold text-warning small mb-1"><i class="bi bi-arrow-left-right"></i> Quy trình chuyển tiếp:</h6>
                            <p class="mb-0 text-dark italic small"><?= htmlspecialchars($item['nhat_ky_he_thong']) ?></p>
                            <small class="text-muted" style="font-size: 0.7rem;">Lúc: <?= date('H:i d/m/Y', strtotime($item['ngay_chuyen_tiep'])) ?></small>
                        </div>
                    <?php endif; ?>

                    <?php if ($item['noi_dung_phan_hoi']): ?>
                        <div class="reply-box mt-3 shadow-sm">
                            <h6 class="text-primary fw-bold small mb-2">
                                <i class="bi bi-chat-left-dots-fill"></i> 
                                Phản hồi từ: <?= htmlspecialchars($item['ten_nhan_su_phan_hoi']) ?>
                            </h6>
                            <p class="mb-2 text-dark"><?= nl2br(htmlspecialchars($item['noi_dung_phan_hoi'])) ?></p>
                            <small class="text-muted italic" style="font-size: 0.7rem;">Hoàn tất lúc: <?= date('d/m/Y H:i', strtotime($item['ngay_phan_hoi'])) ?></small>
                        </div>
                    <?php endif; ?>
                    <?php if($item['TrangThai'] == 'Hoàn Thành' && $role_id == 4): ?>

    <?php

    $sql_check = "
    SELECT COUNT(*)
    FROM BangDanhGia
    WHERE MaYeuCau = ?
    ";

    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$item['MaYeuCau']]);

    $da_danh_gia = $stmt_check->fetchColumn();

    ?>

    <?php if($da_danh_gia == 0): ?>

        <div class="mt-4 border-top pt-3">

            <h6 class="fw-bold text-warning mb-3">
                ⭐ Đánh giá dịch vụ
            </h6>

            <form method="POST">

                <input type="hidden"
                       name="ma_yeu_cau"
                       value="<?= $item['MaYeuCau'] ?>">

                <div class="mb-3">

                    <label class="form-label">
                        Chọn số sao
                    </label>

                    <select name="so_sao"
                            class="form-select">

                        <option value="5">⭐⭐⭐⭐⭐ 5 Sao</option>
                        <option value="4">⭐⭐⭐⭐ 4 Sao</option>
                        <option value="3">⭐⭐⭐ 3 Sao</option>
                        <option value="2">⭐⭐ 2 Sao</option>
                        <option value="1">⭐ 1 Sao</option>

                    </select>

                </div>

                <div class="mb-3">

                    <textarea
                        name="nhan_xet"
                        class="form-control"
                        rows="3"
                        placeholder="Nhập cảm nhận của bạn..."></textarea>

                </div>

                <button type="submit"
                        name="gui_danh_gia"
                        class="btn btn-warning">

                    Gửi đánh giá

                </button>

            </form>

        </div>

    <?php else: ?>

        <div class="alert alert-success mt-4 mb-0">
            ✅ Bạn đã đánh giá yêu cầu này
        </div>

    <?php endif; ?>

<?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>