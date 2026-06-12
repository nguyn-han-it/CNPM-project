<?php
session_start();
require_once '../db.php';

$da_dang_nhap = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$ten_nguoi_dung = $_SESSION['ho_ten'] ?? '';
$ma_khach_hang = $_SESSION['user_id'] ?? null;

$danh_mục_list = [];
try {
    $stmt_dm = $pdo->query("SELECT MaDanhMuc, MoTa FROM DanhMucYeuCau");
    $danh_mục_list = $stmt_dm->fetchAll();
} catch (Exception $e) {
}

$thong_bao = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && $da_dang_nhap) {
    $ma_danh_muc = $_POST['ma_danh_muc'] ?? null;
    $tieu_de = trim($_POST['tieu_de']);
    $noi_dung = trim($_POST['noi_dung']);

    if (!empty($ma_danh_muc) && !empty($tieu_de) && !empty($noi_dung)) {
        try {
            $sql = "INSERT INTO BangYeuCau (MaKhachHang, MaDanhMuc, TieuDe, NoiDung, TrangThai) VALUES (?, ?, ?, ?, 'CHờ Xử Lý')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ma_khach_hang, $ma_danh_muc, $tieu_de, $noi_dung]);
            
            $thong_bao = "<div class='alert alert-success shadow-sm'>Yêu cầu của bạn đã được gửi thành công!</div>";
        } catch (Exception $e) {
            $thong_bao = "<div class='alert alert-danger shadow-sm'>Lỗi: " . $e->getMessage() . "</div>";
        }
    } else {
        $thong_bao = "<div class='alert alert-warning shadow-sm'>Vui lòng điền đầy đủ thông tin và chọn phân loại.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gửi Yêu Cầu Hỗ Trợ - Nha Khoa Việt Mỹ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .top-bar { background:#f8f9fa; padding:10px 0; border-bottom: 1px solid #dee2e6; }
    .logo { font-weight:bold; color:#0d6efd; font-size:20px; text-decoration: none; }
    .hotline-box { background:linear-gradient(90deg,#cfe2ff,#9ec5fe); padding:8px 16px; border-radius:20px; font-size: 14px; }
    .btn-book { padding:8px 18px; border-radius:20px; font-weight:500; }
    footer { background:#343a40; color:white; padding:30px; margin-top: 50px; }
    .form-container { max-width: 650px; margin: 40px auto; padding: 35px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: #fff; }
    .form-label { font-size: 0.95rem; }
  </style>
</head>
<body class="bg-light">

<div class="top-bar d-flex justify-content-between align-items-center px-4 shadow-sm">
  <a href="../nhakhoa.php" class="logo">NHA KHOA VIỆT MỸ</a>
  <div class="d-flex align-items-center gap-3">
    <div class="hotline-box text-primary fw-bold d-none d-sm-block">📞 0385 063 701</div>
    <?php if ($da_dang_nhap): ?>
      <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle btn-book" type="button" data-bs-toggle="dropdown">
          Chào, <?php echo htmlspecialchars($ten_nguoi_dung); ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li><a class="dropdown-item" href="../dangnhap/dangxuat.php">Đăng xuất</a></li>
        </ul>
      </div>
    <?php else: ?>
      <a href="../dangnhap/dangnhap.php" class="btn btn-primary btn-book">Đăng Nhập</a>
    <?php endif; ?>
  </div>
</div>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4 text-primary fw-bold">Gửi Yêu Cầu Hỗ Trợ</h2>
        
        <?php echo $thong_bao; ?>

        <?php if ($da_dang_nhap): ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold">Vấn đề cần hỗ trợ</label>
                    <select name="ma_danh_muc" class="form-select" required>
                        <option value="">-- Chọn loại yêu cầu --</option>
                        <?php foreach ($danh_mục_list as $dm): ?>
                            <option value="<?php echo $dm['MaDanhMuc']; ?>">
                                <?php echo htmlspecialchars($dm['MoTa']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề</label>
                    <input type="text" name="tieu_de" class="form-control" placeholder="Ví dụ: Tư vấn chỉnh nha" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung chi tiết</label>
                    <textarea name="noi_dung" class="form-control" rows="5" placeholder="Mô tả cụ thể thắc mắc của bạn..." required></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">Gửi Yêu Cầu</button>
                    <a href="../nhakhoa.php" class="btn btn-link text-muted">Hủy và quay lại</a>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="text-muted" style="font-size: 3rem;">🔒</i>
                </div>
                <p class="fs-5">Vui lòng đăng nhập để sử dụng chức năng này.</p>
                <a href="../dangnhap/dangnhap.php" class="btn btn-primary btn-lg px-5 mt-2">Đăng nhập ngay</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center">
  <p class="mb-0">© 2026 Hệ thống Nha Khoa Việt Mỹ - Chăm sóc nụ cười của bạn</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>