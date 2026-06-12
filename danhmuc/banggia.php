<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Bảng giá dịch vụ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.table thead {
  background: #0d6efd;
  color: white;
}
.price {
  font-weight: bold;
  color: #dc3545;
}
</style>
</head>
<body>

<div class="container my-5">
  <h2 class="text-center mb-4">Bảng giá dịch vụ</h2>

  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>Dịch vụ</th>
        <th>Mô tả</th>
        <th>Giá</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Răng sứ</td>
        <td>Bọc răng sứ thẩm mỹ</td>
        <td class="price">2.000.000đ</td>
      </tr>
      <tr>
        <td>Niềng răng</td>
        <td>Niềng mắc cài</td>
        <td class="price">15.000.000đ</td>
      </tr>
      <tr>
        <td>Implant</td>
        <td>Trồng răng Implant</td>
        <td class="price">20.000.000đ</td>
      </tr>
      <tr>
        <td>Tổng quát</td>
        <td>Khám & vệ sinh răng</td>
        <td class="price">300.000đ</td>
      </tr>
    </tbody>
  </table>

  <div class="text-center">
     <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
  </div>
</div>

</body>
</html>