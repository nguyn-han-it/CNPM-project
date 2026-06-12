<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Ưu đãi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.deal-card {
  border-radius: 12px;
  overflow: hidden;
  transition: 0.3s;
}
.deal-card:hover {
  transform: translateY(-5px);
}
.badge-sale {
  background: red;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
}
</style>
</head>
<body>

<div class="container my-5">
  <h2 class="text-center mb-4">Ưu đãi hấp dẫn</h2>

  <div class="row g-4">

    <div class="col-md-4">
      <div class="card deal-card p-3 text-center shadow">
        <span class="badge-sale">-20%</span>
        <h5>Răng sứ</h5>
        <p>Giảm giá khi làm combo</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card deal-card p-3 text-center shadow">
        <span class="badge-sale">-15%</span>
        <h5>Niềng răng</h5>
        <p>Ưu đãi cho học sinh sinh viên</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card deal-card p-3 text-center shadow">
        <span class="badge-sale">-10%</span>
        <h5>Implant</h5>
        <p>Giảm giá cho khách hàng mới</p>
      </div>
    </div>

  </div>

  <div class="text-center mt-4">
    <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
  </div>
</div>

</body>
</html>