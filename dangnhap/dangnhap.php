<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - Khách hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #74ebd5, #9face6);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-box {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
      width: 380px; 
    }
    .login-box h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #0d6efd;
    }
    .btn-login {
      border-radius: 20px;
    }
    .role-switch {
      border-top: 1px solid #eee;
      padding-top: 15px;
      margin-top: 15px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Đăng nhập</h2>
  
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger py-2 small">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="xulydangnhap.php">
    <div class="mb-3">
      <label class="form-label small fw-bold">Email khách hàng</label>
      <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-bold">Mật khẩu</label>
      <input type="password" name="password" class="form-control" placeholder="********" required>
    </div>

    <div class="d-flex justify-content-between mb-3 small">
      <div>
        <input type="checkbox"> Ghi nhớ
      </div>
      <a href="#" class="text-decoration-none">Quên mật khẩu?</a>
    </div>

    <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm">Đăng nhập</button>
  </form>

  <div class="text-center mt-3 small">
    <span>Chưa có tài khoản?</span>
    <a href="dangky.php" class="text-decoration-none fw-bold">Đăng ký</a>
  </div>

  <!-- PHẦN CHUYỂN HƯỚNG NHÂN VIÊN -->
  <div class="role-switch text-center">
    <p class="mb-1 small text-muted">Bạn là thành viên phòng khám?</p>
    <a href="dangnhap_nhanvien.php" class="btn btn-outline-secondary btn-sm w-100 btn-login">
        Đăng nhập Nhân viên / Admin
    </a>
  </div>
</div>

</body>
</html>