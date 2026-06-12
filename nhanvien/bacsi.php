<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role_id'] != 2) {
    header("Location: ../dangnhap/dangnhap_nhanvien.php");
    exit;
}

$ten_nguoi_dung = $_SESSION['ho_ten'] ?? '';
$ma_bac_si = $_SESSION['user_id']; 
$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gui_phan_hoi'])) {
    $ma_yeu_cau = $_POST['ma_yeu_cau'];
    $noi_dung_ph = trim($_POST['noi_dung_ph']);

    if (!empty($noi_dung_ph)) {
        try {
            $pdo->beginTransaction();
            $stmt1 = $pdo->prepare("INSERT INTO BangPhanHoi (MaYeuCau, MaNhanVien, NoiDung) VALUES (?, ?, ?)");
            $stmt1->execute([$ma_yeu_cau, $ma_bac_si, $noi_dung_ph]);
            $stmt2 = $pdo->prepare("UPDATE BangYeuCau SET TrangThai = 'Hoàn Thành' WHERE MaYeuCau = ?");
            $stmt2->execute([$ma_yeu_cau]);

            $pdo->commit();
            $thong_bao = "<div class='alert alert-success shadow-sm'>Gửi tư vấn thành công!</div>";
        } catch (Exception $e) {
            $pdo->rollBack();
            $thong_bao = "<div class='alert alert-danger'>Lỗi hệ thống: " . $e->getMessage() . "</div>";
        }
    }
}
$sql = "SELECT y.*, n.TenNguoiDung as ten_kh, dm.MoTa as ten_danh_muc
        FROM BangYeuCau y 
        JOIN NguoiDung n ON y.MaKhachHang = n.MaNguoiDung
        LEFT JOIN DanhMucYeuCau dm ON y.MaDanhMuc = dm.MaDanhMuc
        INNER JOIN LichsuXuly ls ON y.MaYeuCau = ls.MaYeuCau
        WHERE y.TrangThai = 'Đang Xử Lý' 
        AND y.MaYeuCau IN (
            SELECT MaYeuCau FROM LichsuXuly WHERE NoiDung LIKE :pattern
        )
        ORDER BY y.NgayTao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['pattern' => "%ID: $ma_bac_si%"]); 
$danh_sach_cong_viec = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bác Sĩ Tư Vấn - Nha Khoa Việt Mỹ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-bar { background: #0d6efd; color: white; padding: 15px 0; }
        .card-job { border: none; border-radius: 15px; border-left: 5px solid #0d6efd; }
        .badge-dm { background: #e7f1ff; color: #0d6efd; font-size: 0.75rem; padding: 5px 10px; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">

<div class="top-bar mb-4 shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="../nhakhoa.php" class="text-white text-decoration-none fw-bold fs-5">🏥 NHA KHOA VIỆT MỸ (BÁC SĨ)</a>
        <div class="dropdown">
            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Chào, Bác sĩ <b><?= htmlspecialchars($ten_nguoi_dung) ?></b>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="../lichsu.php">Lịch sử tư vấn</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../dangnhap/dangxuat.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📋 Danh sách tư vấn chuyên môn</h4>
        <span class="badge bg-primary"><?= count($danh_sach_cong_viec) ?> ca chờ</span>
    </div>

    <?= $thong_bao ?>

    <?php if (empty($danh_sach_cong_viec)): ?>
        <div class="card p-5 text-center shadow-sm border-0">
            <div class="mb-3" style="font-size: 3rem;">🎉</div>
            <h5 class="text-muted">Hiện tại không có ca nào được phân công cho bác sĩ.</h5>
            <p class="small text-secondary">Các yêu cầu mới sẽ xuất hiện ở đây khi nhân viên chuyển giao.</p>
        </div>
    <?php else: ?>
        <?php foreach ($danh_sach_cong_viec as $cv): ?>
            <div class="card mb-4 shadow-sm card-job">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge-dm mb-2 d-inline-block"><?= htmlspecialchars($cv['ten_danh_muc'] ?? 'Yêu cầu chung') ?></span>
                            <h5 class="fw-bold"><?= htmlspecialchars($cv['TieuDe']) ?></h5>
                            <small class="text-muted">
                                👤 Khách hàng: <b><?= htmlspecialchars($cv['ten_kh']) ?></b> | 
                                📅 Gửi lúc: <?= date('d/m/Y H:i', strtotime($cv['NgayTao'])) ?>
                            </small>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-light rounded border mb-4">
                        <strong>Nội dung khách hàng gửi:</strong><br>
                        <?= nl2br(htmlspecialchars($cv['NoiDung'])) ?>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="ma_yeu_cau" value="<?= $cv['MaYeuCau'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">Nội dung bác sĩ tư vấn:</label>
                            <textarea name="noi_dung_ph" class="form-control" rows="4" required placeholder="Nhập lời khuyên chuyên môn, hướng dẫn điều trị..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" name="gui_phan_hoi" class="btn btn-primary px-4">Gửi tư vấn cho khách hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>