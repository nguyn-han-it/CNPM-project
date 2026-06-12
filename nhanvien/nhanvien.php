<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['role_id'] != 3) {
    header("Location: dangnhap/dangnhap_nhanvien.php");
    exit;
}

$ten_nguoi_dung = $_SESSION['ho_ten'] ?? '';
$ma_nhan_vien = $_SESSION['user_id'];
$thong_bao = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tra_loi_truc_tiep'])) {
    $ma_yeu_cau = $_POST['ma_yeu_cau'];
    $noi_dung_ph = trim($_POST['noi_dung_ph']);

    if (!empty($noi_dung_ph)) {
        try {
            $pdo->beginTransaction();
            $stmt1 = $pdo->prepare("INSERT INTO BangPhanHoi (MaYeuCau, MaNhanVien, NoiDung) VALUES (?, ?, ?)");
            $stmt1->execute([$ma_yeu_cau, $ma_nhan_vien, $noi_dung_ph]);
            $stmt2 = $pdo->prepare("UPDATE BangYeuCau SET TrangThai = 'Hoàn Thành' WHERE MaYeuCau = ?");
            $stmt2->execute([$ma_yeu_cau]);

            $pdo->commit();
            $thong_bao = "<div class='alert alert-success'>Đã gửi phản hồi thành công!</div>";
        } catch (Exception $e) {
            $pdo->rollBack();
            $thong_bao = "<div class='alert alert-danger'>Lỗi trả lời: " . $e->getMessage() . "</div>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chuyen_bac_si'])) {
    $ma_yeu_cau = $_POST['ma_yeu_cau'];
    $ma_bac_si = $_POST['ma_bac_si'];
    $stmt_name = $pdo->prepare("SELECT TenNhanVien FROM NhanVien WHERE MaNhanVien = ?");
    $stmt_name->execute([$ma_bac_si]);
    $ten_bac_si = $stmt_name->fetchColumn();
    try {
        $pdo->beginTransaction();
        $ghi_chu = "Nhân viên $ten_nguoi_dung đã chuyển yêu cầu cho Bác sĩ $ten_bac_si(ID: $ma_bac_si)";
        $stmt_hist = $pdo->prepare("INSERT INTO LichsuXuly (MaYeuCau, MaNhanVien, NoiDung) VALUES (?, ?, ?)");
        $stmt_hist->execute([$ma_yeu_cau, $ma_nhan_vien, $ghi_chu]);
        $stmt_update = $pdo->prepare("UPDATE BangYeuCau SET TrangThai = 'Đang Xử Lý' WHERE MaYeuCau = ?");
        $stmt_update->execute([$ma_yeu_cau]);

        $pdo->commit();
        $thong_bao = "<div class='alert alert-info'>Đã chuyển tiếp yêu cầu cho Bác sĩ.</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $thong_bao = "<div class='alert alert-danger'>Lỗi chuyển tiếp.</div>";
    }
}

$sql_yc = "SELECT y.*, n.TenNguoiDung, dm.MoTa 
           FROM BangYeuCau y 
           JOIN NguoiDung n ON y.MaKhachHang = n.MaNguoiDung 
           LEFT JOIN DanhMucYeuCau dm ON y.MaDanhMuc = dm.MaDanhMuc
           WHERE y.TrangThai = 'Chờ Xử Lý'";
$yeu_cau_cho = $pdo->query($sql_yc)->fetchAll();

$danh_sach_bac_si = $pdo->query("SELECT MaNhanVien, TenNhanVien FROM NhanVien WHERE MaVaiTro = 2")->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý yêu cầu - Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-bar { background: #2c3e50; color: white; padding: 15px 0; }
        .card-yc { border: none; border-radius: 12px; transition: 0.3s; }
        .card-yc:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .category-badge { font-size: 0.75rem; background: #e9ecef; color: #495057; padding: 4px 8px; border-radius: 5px; }
    </style>
</head>
<body class="bg-light">

<div class="top-bar mb-4 shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="fw-bold fs-5">🏥 NHA KHOA VIỆT MỸ - NHÂN VIÊN</span>
        <div class="dropdown">
            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Chào, <b><?= htmlspecialchars($ten_nguoi_dung) ?></b>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="../lichsu.php">Lịch sử hệ thống</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../dangnhap/dangxuat.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <h4 class="mb-4 text-secondary">Yêu cầu mới cần xử lý</h4>
    <?= $thong_bao ?>

    <?php if (empty($yeu_cau_cho)): ?>
        <div class="alert alert-secondary text-center">Tất cả yêu cầu đã được xử lý xong!</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($yeu_cau_cho as $yc): ?>
                <div class="col-md-12 mb-4">
                    <div class="card card-yc shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="category-badge mb-2 d-inline-block"><?= htmlspecialchars($yc['MoTa'] ?? 'Chưa phân loại') ?></span>
                                    <h5 class="card-title text-primary"><?= htmlspecialchars($yc['TieuDe']) ?></h5>
                                    <h6 class="text-muted small">Khách hàng: <?= htmlspecialchars($yc['TenNguoiDung']) ?> | Ngày gửi: <?= date('d/m/Y H:i', strtotime($yc['NgayTao'])) ?></h6>
                                </div>
                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                            </div>
                            
                            <p class="card-text bg-light p-3 rounded border"><?= nl2br(htmlspecialchars($yc['NoiDung'])) ?></p>

                            <hr>

                            <div class="row">
                                <div class="col-md-7 border-end">
                                    <label class="form-label fw-bold text-success small">Tự xử lý (Nghiệp vụ nhân viên)</label>
                                    <form method="POST">
                                        <input type="hidden" name="ma_yeu_cau" value="<?= $yc['MaYeuCau'] ?>">
                                        <textarea name="noi_dung_ph" class="form-control form-control-sm mb-2" rows="2" placeholder="Nhập nội dung tư vấn cho khách hàng..."></textarea>
                                        <button type="submit" name="tra_loi_truc_tiep" class="btn btn-success btn-sm w-100">Gửi phản hồi cho khách</button>
                                    </form>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-danger small">Chuyển chuyên môn (Bác sĩ)</label>
                                    <form method="POST">
                                        <input type="hidden" name="ma_yeu_cau" value="<?= $yc['MaYeuCau'] ?>">
                                        <select name="ma_bac_si" class="form-select form-select-sm mb-2" required>
                                            <option value="">-- Chọn bác sĩ chuyên khoa --</option>
                                            <?php foreach ($danh_sach_bac_si as $bs): ?>
                                                <option value="<?= $bs['MaNhanVien'] ?>"><?= htmlspecialchars($bs['TenNhanVien']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" name="chuyen_bac_si" class="btn btn-outline-danger btn-sm w-100">Chuyển tiếp cho Bác sĩ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>