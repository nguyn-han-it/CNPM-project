<?php
session_start();
require_once '../db.php';
// Tổng danh mục
$sql_tong_dm = "SELECT COUNT(*) FROM DanhMucYeuCau";
$tong_danh_muc = $pdo->query($sql_tong_dm)->fetchColumn();

// Danh mục nhiều nhất
$sql_max = "
SELECT dm.MoTa, COUNT(y.MaYeuCau) AS tong
FROM DanhMucYeuCau dm
LEFT JOIN BangYeuCau y ON dm.MaDanhMuc = y.MaDanhMuc
GROUP BY dm.MaDanhMuc
ORDER BY tong DESC
LIMIT 1
";

$danh_muc_max = $pdo->query($sql_max)->fetch();

// Danh mục ít nhất
$sql_min = "
SELECT dm.MoTa, COUNT(y.MaYeuCau) AS tong
FROM DanhMucYeuCau dm
LEFT JOIN BangYeuCau y ON dm.MaDanhMuc = y.MaDanhMuc
GROUP BY dm.MaDanhMuc
ORDER BY tong ASC
LIMIT 1
";

$danh_muc_min = $pdo->query($sql_min)->fetch();
/* =========================
   THỐNG KÊ HỆ THỐNG
========================= */

// Tổng khách hàng
$sql_tong_kh = "
SELECT COUNT(*) 
FROM NguoiDung
";

$tong_khach_hang = $pdo->query($sql_tong_kh)->fetchColumn();

// Tổng yêu cầu
$sql_tong_yc = "
SELECT COUNT(*) 
FROM BangYeuCau
";

$tong_yeu_cau = $pdo->query($sql_tong_yc)->fetchColumn();

// Tổng bác sĩ
$sql_bac_si = "
SELECT COUNT(*) 
FROM NhanVien
WHERE MaVaiTro = 2
";

$tong_bac_si = $pdo->query($sql_bac_si)->fetchColumn();

// Tổng nhân viên
$sql_nhan_vien = "
SELECT COUNT(*) 
FROM NhanVien
WHERE MaVaiTro = 3
";

$tong_nhan_vien = $pdo->query($sql_nhan_vien)->fetchColumn();

// Tổng đánh giá
$sql_danh_gia = "
SELECT COUNT(*) 
FROM BangDanhGia
";

$tong_danh_gia = $pdo->query($sql_danh_gia)->fetchColumn();

// Yêu cầu hoàn thành
$sql_hoan_thanh = "
SELECT COUNT(*) 
FROM BangYeuCau
WHERE TrangThai = 'Hoàn Thành'
";

$yeu_cau_hoan_thanh = $pdo->query($sql_hoan_thanh)->fetchColumn();

// Yêu cầu đang xử lý
$sql_dang_xu_ly = "
SELECT COUNT(*) 
FROM BangYeuCau
WHERE TrangThai = 'Đang Xử Lý'
";

$yeu_cau_dang_xu_ly = $pdo->query($sql_dang_xu_ly)->fetchColumn();

// Trung bình số sao
$sql_trung_binh_sao = "
SELECT ROUND(AVG(SoSao),1)
FROM BangDanhGia
";

$trung_binh_sao = $pdo->query($sql_trung_binh_sao)->fetchColumn();
/* =========================
   XÓA KHÁCH HÀNG
========================= */

if(isset($_GET['xoa_khach_hang'])){

    $id = $_GET['xoa_khach_hang'];

    $sql = "
    DELETE FROM NguoiDung
    WHERE MaNguoiDung = ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: admin.php");
    exit;
}
/* =========================
   XÓA LỊCH SỬ
========================= */

if(isset($_GET['xoa_lich_su'])){

    $id = $_GET['xoa_lich_su'];

    $sql = "
    DELETE FROM LichsuXuly
    WHERE MaLichSu = ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: admin.php");
    exit;
}

// KIỂM TRA QUYỀN ADMIN

if (!isset($_SESSION['logged_in']) || $_SESSION['role_id'] != 1) {
    header("Location: ../dangnhap.php");
    exit;
}

// LẤY THÔNG TIN ADMIN 
$ten_admin = $_SESSION['ho_ten'] ?? 'Admin';


// Bác sĩ
$sql_bacsi = "SELECT COUNT(*) FROM NhanVien WHERE MaVaiTro = 2";
$tong_bac_si = $pdo->query($sql_bacsi)->fetchColumn();

// Nhân viên
$sql_nhanvien = "SELECT COUNT(*) FROM NhanVien WHERE MaVaiTro = 3";
$tong_nhan_vien = $pdo->query($sql_nhanvien)->fetchColumn();

// Khách hàng
$sql_khachhang = "SELECT COUNT(*) FROM NguoiDung";
$tong_khach_hang = $pdo->query($sql_khachhang)->fetchColumn();

// DANH SÁCH NHÂN VIÊN 

$sql_ds_nv = "
SELECT *
FROM NhanVien
ORDER BY MaNhanVien DESC
";

$danh_sach_nv = $pdo->query($sql_ds_nv)->fetchAll();
/* =========================
   DANH SÁCH KHÁCH HÀNG
========================= */

$sql_khach_hang = "
SELECT *
FROM NguoiDung
ORDER BY MaNguoiDung DESC
";

$danh_sach_khach_hang = $pdo->query($sql_khach_hang)->fetchAll();

/* =========================
   LỊCH SỬ XỬ LÝ
========================= */

$sql_ls = "
SELECT ls.*, nv.TenNhanVien
FROM LichsuXuly ls
LEFT JOIN NhanVien nv 
ON ls.MaNhanVien = nv.MaNhanVien
ORDER BY ls.ThoiGian DESC
";

$lich_su = $pdo->query($sql_ls)->fetchAll();
/* =========================
   ĐÁNH GIÁ
========================= */
$sql_dg = "
SELECT dg.*, nd.TenNguoiDung
FROM BangDanhGia dg
LEFT JOIN NguoiDung nd
ON dg.MaNguoiDung = nd.MaNguoiDung
ORDER BY dg.NgayDanhGia DESC
LIMIT 10
";

$danh_gia = $pdo->query($sql_dg)->fetchAll();
/* =========================
   TẠO TÀI KHOẢN
========================= */

$thong_bao = "";
/* =========================
   XÓA NHÂN VIÊN
========================= */

if(isset($_GET['xoa_nhanvien'])){

    $id = $_GET['xoa_nhanvien'];

    try{

        $sql = "
        DELETE FROM NhanVien
        WHERE MaNhanVien = ?
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$id]);

        echo "
        <script>

            alert('Xóa thành công');

            window.location.href='admin.php';

        </script>
        ";

    }catch(Exception $e){

        echo $e->getMessage();

    }

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tao_tai_khoan'])) {

    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['so_dien_thoai']);
    $mat_khau = trim($_POST['mat_khau']);
    $ma_vai_tro = $_POST['ma_vai_tro'];

    try {

        // KIỂM TRA EMAIL
        $check = $pdo->prepare("
            SELECT COUNT(*) 
            FROM NhanVien 
            WHERE Email = ?
        ");

        $check->execute([$email]);

        if ($check->fetchColumn() > 0) {

            $thong_bao = "Email đã tồn tại";

        } else {

            // THÊM TÀI KHOẢN
            $sql = "
            INSERT INTO NhanVien
            (
                TenNhanVien,
                SDT,
                Email,
                MatKhau,
                MaVaiTro
            )
            VALUES
            (
                ?, ?, ?, ?, ?
            )
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $ho_ten,
                $sdt,
                $email,
                $mat_khau,
                $ma_vai_tro
            ]);

            $thong_bao = "Tạo tài khoản thành công";

        }

    } catch (Exception $e) {

        $thong_bao = $e->getMessage();

    }

}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f3f4f6;
}

/* =========================
   SIDEBAR
========================= */

.sidebar{
    width:250px;
    height:100vh;
    background:#111827;
    position:fixed;
    left:0;
    top:0;
    padding:30px 20px;
}

.logo{
    color:white;
    font-size:28px;
    font-weight:bold;
    margin-bottom:40px;
}

.menu a{
    display:block;
    color:#d1d5db;
    text-decoration:none;
    padding:14px 16px;
    border-radius:10px;
    margin-bottom:10px;
    transition:0.2s;
}

.menu a:hover{
    background:#374151;
    color:white;
}

/* =========================
   MAIN
========================= */

.main{
    margin-left:250px;
    padding:35px;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
}

.topbar h1{
    font-size:35px;
    color:#111827;
}

.admin-box{
    background:white;
    padding:12px 20px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

/* =========================
   CARDS
========================= */

.cards{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:25px;
    margin-bottom:40px;
}

.card{
    border-radius:20px;
    padding:30px;
    color:white;
}

.card i{
    font-size:30px;
    margin-bottom:15px;
}

.card h3{
    font-size:20px;
    margin-bottom:15px;
}

.card .number{
    font-size:45px;
    font-weight:bold;
}

.blue{
    background:linear-gradient(135deg,#2563eb,#4f46e5);
}

.green{
    background:linear-gradient(135deg,#059669,#10b981);
}

.orange{
    background:linear-gradient(135deg,#f59e0b,#f97316);
}

/* =========================
   TABLE
========================= */

.section{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:35px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

.section h2{
    margin-bottom:20px;
    color:#111827;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#111827;
    color:white;
    padding:14px;
    text-align:left;
}

table td{
    padding:14px;
    border-bottom:1px solid #e5e7eb;
}

table tr:hover{
    background:#f9fafb;
}

/* =========================
   BADGE
========================= */

.badge{
    padding:6px 12px;
    border-radius:20px;
    color:white;
    font-size:13px;
}

.badge-blue{
    background:#2563eb;
}

.badge-green{
    background:#059669;
}

.star{
    color:orange;
}
.noidung{
    max-width:400px;
    line-height:1.6;
}
/* =========================
   RESPONSIVE
========================= */

@media(max-width:1000px){

.cards{
    grid-template-columns:1fr;
}

.main{
    margin-left:0;
}

.sidebar{
    display:none;
}

}
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

</style>
</head>

<body>

<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <div class="logo">
        ADMIN
    </div>
    <div class="menu">
        
    <a href="../nhakhoa.php" class="btn btn-secondary">Trang Chủ</a>

       <a href="#" onclick="showBox('nhanvien-box')">
            <i class="fa-solid fa-user-tie"></i>
            Nhân viên
        </a>

        <a href="#" onclick="showBox('khachhang-box')">
            <i class="fa-solid fa-users"></i>
             Khách hàng
        </a>
        <a href="#" onclick="showBox('lichsu-box')">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Lịch sử
        </a>
        <a href="#" onclick="showBox('thongke-box')">
            <i class="fa-solid fa-chart-line"></i>
            Thống kê
        </a>
         <a href="../dangnhap/dangxuat.php">Đăng xuất</a>
    </div>

</div>

<!-- =========================
     MAIN
========================= -->

<div class="main">


    <div class="topbar">

        <h1>Dashboard</h1>

        <div class="admin-box">
            Xin chào, <b><?= htmlspecialchars($ten_admin) ?></b>
        </div>

    </div>

    <!-- CARDS -->

    <div class="cards">

        <div class="card blue">
            <i class="fa-solid fa-user-doctor"></i>
            <h3>Tổng Bác Sĩ</h3>
            <div class="number"><?= $tong_bac_si ?></div>
        </div>

        <div class="card green">
            <i class="fa-solid fa-user-tie"></i>
            <h3>Tổng Nhân Viên</h3>
            <div class="number"><?= $tong_nhan_vien ?></div>
        </div>

        <div class="card orange">
            <i class="fa-solid fa-users"></i>
            <h3>Tổng Khách Hàng</h3>
            <div class="number"><?= $tong_khach_hang ?></div>
        </div>
        
        
</div>

<!-- THỐNG KÊ HỆ THỐNG -->
<div class="section content-box"
     id="thongke-box"
     style="display:none;">

    <h2>
        <i class="fa-solid fa-chart-line"></i>
        Thống kê hệ thống
    </h2>

    <div class="cards">

        <!-- Tổng yêu cầu -->
        <div class="card green">
            <i class="fa-solid fa-file-circle-check"></i>
            <h3>Tổng yêu cầu</h3>
            <div class="number">
                <?= $tong_yeu_cau ?>
            </div>
        </div>

        <!-- Tổng đánh giá -->
        <div class="card orange">
            <i class="fa-solid fa-star"></i>
            <h3>Tổng đánh giá</h3>
            <div class="number">
                <?= $tong_danh_gia ?>
            </div>
        </div>

        <!-- Hoàn thành -->
        <div class="card orange">
            <i class="fa-solid fa-check"></i>
            <h3>Đã hoàn thành</h3>
            <div class="number">
                <?= $yeu_cau_hoan_thanh ?>
            </div>
        </div>

        <!-- Đang xử lý -->
        <div class="card blue">
            <i class="fa-solid fa-spinner"></i>
            <h3>Đang xử lý</h3>
            <div class="number">
                <?= $yeu_cau_dang_xu_ly ?>
            </div>
        </div>

        <!-- Trung bình sao -->
        <div class="card green">
            <i class="fa-solid fa-ranking-star"></i>
            <h3>Trung bình sao</h3>
            <div class="number">
                <?= $trung_binh_sao ?> ⭐
            </div>
        </div>

        <!-- Danh mục nhiều nhất -->
        <div class="card blue">
            <i class="fa-solid fa-chart-column"></i>
            <h3>Danh mục nhiều nhất</h3>

            <div class="number">
                <?= $danh_muc_max['tong'] ?>
            </div>

            <small>
                <?= $danh_muc_max['MoTa'] ?>
            </small>
        </div>

        <!-- Danh mục ít nhất -->
        <div class="card green">
            <i class="fa-solid fa-chart-simple"></i>
            <h3>Danh mục ít nhất</h3>

            <div class="number">
                <?= $danh_muc_min['tong'] ?>
            </div>

            <small>
                <?= $danh_muc_min['MoTa'] ?>
            </small>
        </div>

        <!-- Tổng danh mục -->
        <div class="card orange">
            <i class="fa-solid fa-layer-group"></i>
            <h3>Tổng danh mục</h3>

            <div class="number">
                <?= $tong_danh_muc ?>
            </div>
        </div>

    </div>

</div>


        <!-- =========================
     FORM + DANH SÁCH
========================= -->

<style>

/* =========================
   FORM + TABLE
========================= */

.row{
    display:grid;
    grid-template-columns: 380px 1fr;
    gap:30px;
    margin-top:20px;
    align-items:start;
}

/* CARD */

.card-box{
    background:white;
    border-radius:22px;
    padding:30px;
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
}

/* TITLE */

.card-box h2{
    font-size:22px;
    color:#111827;
    margin-bottom:25px;
    display:flex;
    align-items:center;
    gap:12px;
}

.card-box h2 i{
    color:#4f46e5;
}

/* =========================
   FORM
========================= */

.form-group{
    margin-bottom:22px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:15px;
    font-weight:600;
    color:#374151;
}

.form-control,
.form-select{
    width:100%;
    height:52px;
    padding:0 16px;
    border:1px solid #d1d5db;
    border-radius:14px;
    background:#f9fafb;
    font-size:15px;
    transition:0.2s;
}

.form-control:focus,
.form-select:focus{
    outline:none;
    border-color:#4f46e5;
    background:white;
    box-shadow:0 0 0 4px rgba(79,70,229,0.15);
}

/* BUTTON */

.btn-submit{
    width:100%;
    height:52px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,#2563eb,#4f46e5);
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.2s;
}

.btn-submit:hover{
    transform:translateY(-2px);
    opacity:0.95;
}

/* =========================
   TABLE
========================= */

.table-box{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#111827;
    color:white;
    padding:16px;
    font-size:15px;
    text-align:left;
}

table th:first-child{
    border-top-left-radius:12px;
}

table th:last-child{
    border-top-right-radius:12px;
}

table td{
    padding:18px 16px;
    border-bottom:1px solid #e5e7eb;
    color:#111827;
    font-size:15px;
}

table tr:hover{
    background:#f9fafb;
}

/* =========================
   BADGE
========================= */

.badge{
    padding:8px 14px;
    border-radius:30px;
    color:white;
    font-size:13px;
    font-weight:bold;
    display:inline-block;
    white-space: nowrap;
}

.badge-blue{
    background:#4f46e5;
}

.badge-green{
    background:#10b981;
}

/* =========================
   BUTTON ACTION
========================= */

.action-buttons{
    display:flex;
    flex-direction:row;
    align-items:center;
    justify-content:center;
    gap:8px;
}

.btn-edit{
    background:#6d28d9;
    color:white;
    padding:8px 14px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
    display:inline-block;
}

.btn-delete{
    background:#ea580c;
    color:white;
    padding:8px 14px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
    display:inline-block;
}
.btn-edit:hover,
.btn-delete:hover{
    opacity:0.9;
    transform:translateY(-1px);
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:1100px){

    .row{
        grid-template-columns:1fr;
    }

}

</style>



    <!-- FORM TẠO TÀI KHOẢN -->
<div class="row content-box" id="nhanvien-box" style="display:none;">

    <div class="card-box">

        <h2>
            <i class="fa-solid fa-user-plus"></i>
            Tạo Tài Khoản
        </h2>

        <form method="POST">

            <div class="form-group">

                <label>Họ tên *</label>

                <input type="text"
                       name="ho_ten"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Email *</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Số điện thoại</label>

                <input type="text"
                       name="so_dien_thoai"
                       class="form-control">

            </div>

            <div class="form-group">

                <label>Mật khẩu *</label>

                <input type="password"
                       name="mat_khau"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Vai trò</label>

                <select name="ma_vai_tro"
                        class="form-select">

                    <option value="2">
                        Bác sĩ
                    </option>

                    <option value="3">
                        Nhân viên
                    </option>

                </select>

            </div>

            <button type="submit"
                    name="tao_tai_khoan"
                    class="btn-submit">

                Tạo tài khoản

            </button>

        </form>

    </div>

    <!-- DANH SÁCH -->

    <div class="card-box">

        <h2>
            <i class="fa-solid fa-users"></i>
            Danh sách nhân viên & bác sĩ
        </h2>

        <div class="table-box">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Vai trò</th>
                        <th style="width:180px;">Hành động</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach($danh_sach_nv as $nv): ?>

                    <tr>

                        <td>
                            <?= $nv['MaNhanVien'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($nv['TenNhanVien']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($nv['Email']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($nv['SDT']) ?>
                        </td>

                        <td>

                            <?php if($nv['MaVaiTro'] == 2): ?>

                                <span class="badge badge-green">
                                    Bác sĩ
                                </span>
    

                                <?php elseif($nv['MaVaiTro'] == 1): ?>
                                <span class="badge badge-blue">
                                    Admin
                                </span>
                            <?php else: ?>

                                <span class="badge badge-green">
                                    Nhân viên
                                </span>
                            <?php endif; ?>

                        </td>
<td>

    <a href="sua_nhanvien.php?id=<?= $nv['MaNhanVien'] ?>"
       class="btn-edit">

        Sửa

    </a>

        <a href="?xoa_nhanvien=<?= $nv['MaNhanVien'] ?>"
            class="btn-delete"
            onclick="return confirm('Bạn có chắc muốn xóa?')">

                Xóa

        </a>

</td>
                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card-box content-box" id="khachhang-box" style="display:none;">

    <h2>
        <i class="fa-solid fa-users"></i>
        Danh sách khách hàng
    </h2>

    <div class="table-box">
  
        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach($danh_sach_khach_hang as $kh): ?>

                <tr>

                    <td>
                        <?= $kh['MaNguoiDung'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($kh['TenNguoiDung']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($kh['Email']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($kh['SDT']) ?>
                    </td>

                    <td>
                        <?= $kh['NgayTao'] ?>
                    </td>
                    <td>

                        <a href="?xoa_khach_hang=<?= $kh['MaNguoiDung'] ?>"
                        class="btn-delete"
                        onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')">

                        Xóa

                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>
<!-- =========================
     LỊCH SỬ XỬ LÝ
========================= -->

<div class="card-box content-box" id="lichsu-box" style="display:none;">

    <h2>
        <i class="fa-solid fa-clock-rotate-left"></i>
        Lịch sử xử lý
    </h2>

    <div class="table-box">

        <table>

            <thead>

                <tr>
                    <th>Mã</th>
                    <th>Nhân viên</th>
                    <th>Nội dung</th>
                    <th>Thời gian</th>
                    <th>Hành động</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach($lich_su as $ls): ?>

                <tr>

                    <td>
                        <?= $ls['MaLichSu'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($ls['TenNhanVien'] ?? 'Không rõ') ?>
                    </td>

                    <td class="noidung">
                        <?= htmlspecialchars($ls['NoiDung']) ?>
                    </td>

                    <td>
                        <?= $ls['ThoiGian'] ?>
                    </td>
                    <td>

                        <a href="?xoa_lich_su=<?= $ls['MaLichSu'] ?>"
                        class="btn-delete"
                        onclick="return confirm('Bạn có chắc muốn xóa lịch sử này?')">

                        Xóa

                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>
<script>

function showBox(id){

    // Ẩn tất cả
    const boxes = document.querySelectorAll('.content-box');

    boxes.forEach(function(box){
        box.style.display = 'none';
    });

    // Hiện box được chọn
    const selectedBox = document.getElementById(id);

    if(selectedBox){

        // Nếu là row thì dùng grid
        if(id == "nhanvien-box"){
            selectedBox.style.display = 'grid';
        }
        else{
            selectedBox.style.display = 'block';
        }

    }

}

</script>
</body>
</html>