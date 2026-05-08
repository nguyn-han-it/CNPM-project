<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #84fab0, #8fd3f4);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .register-box {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
      width: 400px;
    }
    .register-box h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .btn-register {
      border-radius: 20px;
    }
  </style>
</head>
<body>

<div class="register-box">
  <h2>Đăng ký</h2>
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
      <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="xulydangky.php">
    <div class="mb-3">
      <label class="form-label">Họ và tên</label>
      <input type="text" name="ho_ten" class="form-control" placeholder="Nhập họ tên">
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" placeholder="Nhập email">
    </div>

    <div class="mb-3">
      <label class="form-label">Số điện thoại</label>
      <input type="tel" name="so_dien_thoai" class="form-control" placeholder="Nhập số điện thoại">
    </div>

    <div class="mb-3">
      <label class="form-label">Mật khẩu</label>
      <input type="password" name="mat_khau" class="form-control" placeholder="Nhập mật khẩu">
    </div>

    <div class="mb-3">
      <label class="form-label">Nhập lại mật khẩu</label>
      <input type="password" name="nhap_lai_mat_khau" class="form-control" placeholder="Nhập lại mật khẩu">
    </div>

    <button type="submit" class="btn btn-success w-100 btn-register">Đăng ký</button>
  </form>

  <div class="text-center mt-3">
    <span>Đã có tài khoản?</span>
    <a href="dangnhap.php">Đăng nhập</a>
  </div>
</div>

</body>
</html>