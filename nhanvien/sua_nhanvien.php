<?php

require_once '../db.php';

$id = $_GET['id'] ?? 0;

/* =========================
   LẤY THÔNG TIN NHÂN VIÊN
========================= */

$sql = "
SELECT *
FROM NhanVien
WHERE MaNhanVien = ?
";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

$nv = $stmt->fetch();

if(!$nv){
    die("Không tìm thấy nhân viên");
}

/* =========================
   CẬP NHẬT
========================= */

if(isset($_POST['cap_nhat'])){

    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $vai_tro = $_POST['vai_tro'];

    $sql_update = "
    UPDATE NhanVien
    SET
        TenNhanVien = ?,
        Email = ?,
        SDT = ?,
        MaVaiTro = ?
    WHERE MaNhanVien = ?
    ";

    $stmt_update = $pdo->prepare($sql_update);

    $stmt_update->execute([
        $ho_ten,
        $email,
        $sdt,
        $vai_tro,
        $id
    ]);

    echo "
    <script>

        alert('Cập nhật thành công');

        window.location.href='admin.php';

    </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">
<title>Sửa nhân viên</title>

<style>

body{
    font-family:Arial;
    background:#f3f4f6;
    padding:40px;
}

.box{
    width:500px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
}

h2{
    margin-bottom:25px;
}

.form-group{
    margin-bottom:18px;
}

label{
    display:block;
    margin-bottom:8px;
}

input,
select{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:10px;
}

button{
    width:100%;
    padding:14px;
    border:none;
    background:#2563eb;
    color:white;
    border-radius:10px;
    cursor:pointer;
}

</style>

</head>

<body>

<div class="box">

    <h2>Sửa nhân viên</h2>

    <form method="POST">

        <div class="form-group">

            <label>Họ tên</label>

            <input type="text"
                   name="ho_ten"
                   value="<?= $nv['TenNhanVien'] ?>"
                   required>

        </div>

        <div class="form-group">

            <label>Email</label>

            <input type="email"
                   name="email"
                   value="<?= $nv['Email'] ?>"
                   required>

        </div>

        <div class="form-group">

            <label>SĐT</label>

            <input type="text"
                   name="sdt"
                   value="<?= $nv['SDT'] ?>">

        </div>

        <div class="form-group">

            <label>Vai trò</label>

            <select name="vai_tro">

                <option value="2"
                    <?= $nv['MaVaiTro'] == 2 ? 'selected' : '' ?>>

                    Bác sĩ

                </option>

                <option value="3"
                    <?= $nv['MaVaiTro'] == 3 ? 'selected' : '' ?>>

                    Nhân viên

                </option>

            </select>

        </div>

        <button type="submit" name="cap_nhat">

            Cập nhật

        </button>

    </form>

</div>

</body>
</html>