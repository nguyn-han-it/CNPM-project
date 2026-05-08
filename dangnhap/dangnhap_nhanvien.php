<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập Nhân viên</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #2c3e50; height: 100vh; display: flex; justify-content: center; align-items: center; }
    .login-box { background: white; padding: 30px; border-radius: 15px; width: 380px; }
    .btn-login { border-radius: 20px; }
  </style>
</head>
<body>

<div class="login-box shadow-lg">
  <h2 class="text-center mb-4 text-secondary">Hệ thống Nhân viên</h2>
  
  <form method="POST" action="xulydangnhapnv.php">
    <div class="mb-3">
      <label class="form-label small fw-bold">Email công vụ</label>
      <input type="email" name="email" class="form-control" placeholder="nv-name@nhakhoa.com" required>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-bold">Mật khẩu hệ thống</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-dark w-100 btn-login mb-3">Đăng nhập hệ thống</button>
    <div class="text-center">
        <a href="dangnhap.php" class="text-decoration-none small text-muted">← Quay lại đăng nhập khách</a>
    </div>
  </form>
</div>

</body>
</html>