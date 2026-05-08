<?php
session_start();
$da_dang_nhap = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$ten_nguoi_dung = $_SESSION['TenNguoiDung'] ?? '';

require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nha Khoa Việt Mỹ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --blue:    #1a56db;
      --blue-lt: #ebf0ff;
      --blue-dk: #0f3fa6;
      --ink:     #131927;
      --muted:   #5a6478;
      --border:  #e4e8f0;
      --bg:      #f7f8fc;
      --white:   #ffffff;
      --green:   #0f7b52;
      --green-lt:#e6f5ef;
      --red:     #c0392b;
      --red-lt:  #fdecea;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--ink);
      font-size: 14px;
      line-height: 1.6;
    }

    /* ───── TOPBAR ───── */
    .topbar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      height: 56px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 28px;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      font-weight: 600;
      color: var(--blue);
      text-decoration: none;
      letter-spacing: 0.3px;
    }
    .logo span { color: var(--ink); font-weight: 500; }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-wrap {
      position: relative;
    }
    .search-wrap input {
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 7px 36px 7px 12px;
      font-size: 13px;
      font-family: inherit;
      width: 220px;
      outline: none;
      background: var(--bg);
      color: var(--ink);
      transition: border-color .15s, background .15s;
    }
    .search-wrap input:focus { border-color: var(--blue); background: var(--white); }
    .search-wrap svg {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      pointer-events: none;
    }

    .hotline {
      background: var(--blue-lt);
      color: var(--blue-dk);
      font-weight: 500;
      font-size: 13px;
      padding: 6px 12px;
      border-radius: 8px;
      white-space: nowrap;
    }

    .btn-login {
      border: 1px solid var(--blue);
      color: var(--blue);
      background: transparent;
      border-radius: 8px;
      padding: 6px 16px;
      font-size: 13px;
      font-weight: 500;
      font-family: inherit;
      cursor: pointer;
      text-decoration: none;
      transition: background .15s, color .15s;
    }
    .btn-login:hover { background: var(--blue-lt); color: var(--blue-dk); }

    .btn-register {
      background: var(--blue);
      color: #fff;
      border: 1px solid var(--blue);
      border-radius: 8px;
      padding: 6px 16px;
      font-size: 13px;
      font-weight: 500;
      font-family: inherit;
      cursor: pointer;
      text-decoration: none;
      transition: background .15s;
    }
    .btn-register:hover { background: var(--blue-dk); }

    .user-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 13px;
      font-weight: 500;
      font-family: inherit;
      background: var(--white);
      cursor: pointer;
      color: var(--ink);
    }
    .user-avatar {
      width: 26px; height: 26px;
      border-radius: 50%;
      background: var(--blue-lt);
      color: var(--blue-dk);
      font-size: 11px;
      font-weight: 600;
      display: flex; align-items: center; justify-content: center;
    }

    /* ───── LAYOUT ───── */
    .page-body {
      display: grid;
      grid-template-columns: 220px 1fr 300px;
      gap: 0;
      max-width: 1280px;
      margin: 0 auto;
      padding: 24px 20px;
      align-items: start;
    }

    /* ───── LEFT SIDEBAR – DANH MỤC ───── */
    .sidebar-left {
      padding-right: 20px;
    }
    .sidebar-title {
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 10px;
    }
    .cat-list { list-style: none; }
    .cat-item { margin-bottom: 2px; }
    .cat-item > a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 10px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      color: var(--ink);
      text-decoration: none;
      transition: background .12s, color .12s;
    }
    .cat-item > a:hover, .cat-item > a.active {
      background: var(--blue-lt);
      color: var(--blue);
    }
    .cat-item > a .cat-icon {
      width: 28px; height: 28px;
      border-radius: 7px;
      background: var(--border);
      display: flex; align-items: center; justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }
    .cat-item > a:hover .cat-icon,
    .cat-item > a.active .cat-icon { background: #c7d9ff; }

    .sub-list { list-style: none; padding-left: 46px; margin-top: 2px; display: none; }
    .cat-item:hover .sub-list,
    .cat-item:focus-within .sub-list { display: block; }
    .sub-list li a {
      display: block;
      padding: 5px 8px;
      border-radius: 6px;
      font-size: 12.5px;
      color: var(--muted);
      text-decoration: none;
    }
    .sub-list li a:hover { background: var(--blue-lt); color: var(--blue); }

    .sidebar-divider { border: none; border-top: 1px solid var(--border); margin: 14px 0; }

    /* ───── MAIN CONTENT ───── */
    .main-content { padding: 0 20px; border-left: 1px solid var(--border); border-right: 1px solid var(--border); min-height: 80vh; }

    /* HERO STRIP */
    .hero-strip {
      background: linear-gradient(135deg, #1a56db 0%, #0f3fa6 100%);
      border-radius: 14px;
      padding: 32px 28px;
      color: #fff;
      margin-bottom: 24px;
      position: relative;
      overflow: hidden;
    }
    .hero-strip::after {
      content: '';
      position: absolute;
      right: -30px; bottom: -40px;
      width: 180px; height: 180px;
      border-radius: 50%;
      background: rgba(255,255,255,0.07);
    }
    .hero-strip h1 {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 8px;
      line-height: 1.3;
    }
    .hero-strip p { font-size: 13px; opacity: .85; margin-bottom: 16px; }
    .btn-hero {
      background: #fff;
      color: var(--blue-dk);
      border: none;
      border-radius: 8px;
      padding: 9px 20px;
      font-size: 13px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      text-decoration: none;
      transition: opacity .15s;
      display: inline-block;
    }
    .btn-hero:hover { opacity: .9; }

    /* POSTS SECTION */
    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 14px;
    }
    .section-header h2 {
      font-family: 'Playfair Display', serif;
      font-size: 17px;
      font-weight: 600;
      color: var(--ink);
    }
    .section-header a { font-size: 12px; color: var(--blue); text-decoration: none; }
    .section-header a:hover { text-decoration: underline; }

    .posts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px; }

    .post-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
      text-decoration: none;
      color: inherit;
      display: block;
      transition: box-shadow .15s, transform .15s;
    }
    .post-card:hover { box-shadow: 0 4px 18px rgba(26,86,219,.1); transform: translateY(-2px); }
    .post-card-img {
      width: 100%; height: 130px;
      background: var(--blue-lt);
      display: flex; align-items: center; justify-content: center;
      font-size: 32px;
    }
    .post-card-body { padding: 12px; }
    .post-tag {
      display: inline-block;
      font-size: 10px;
      font-weight: 600;
      letter-spacing: .5px;
      text-transform: uppercase;
      color: var(--blue);
      background: var(--blue-lt);
      border-radius: 4px;
      padding: 2px 7px;
      margin-bottom: 6px;
    }
    .post-card-body h3 { font-size: 13px; font-weight: 500; line-height: 1.4; color: var(--ink); margin-bottom: 6px; }
    .post-card-body .post-meta { font-size: 11px; color: var(--muted); }

    /* SERVICES ROW */
    .services-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 24px; }
    .svc-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px 12px;
      text-align: center;
      text-decoration: none;
      color: inherit;
      transition: box-shadow .15s, transform .15s;
      display: block;
    }
    .svc-card:hover { box-shadow: 0 4px 16px rgba(26,86,219,.1); transform: translateY(-2px); }
    .svc-icon { font-size: 24px; margin-bottom: 8px; }
    .svc-card h4 { font-size: 13px; font-weight: 500; color: var(--ink); margin-bottom: 4px; }
    .svc-card p { font-size: 11px; color: var(--muted); }

    /* ───── RIGHT SIDEBAR – HỖ TRỢ ───── */
    .sidebar-right { padding-left: 20px; }

    .support-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 16px;
    }
    .support-card-head {
      padding: 14px 16px 10px;
      border-bottom: 1px solid var(--border);
    }
    .support-card-head h3 {
      font-size: 14px;
      font-weight: 600;
      color: var(--ink);
      margin-bottom: 2px;
    }
    .support-card-head p { font-size: 12px; color: var(--muted); }
    .support-card-body { padding: 12px 16px; }

    /* Nút gửi yêu cầu lớn */
    .btn-support-primary {
      display: block;
      width: 100%;
      background: var(--blue);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px;
      font-size: 13px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      margin-bottom: 8px;
      transition: background .15s;
    }
    .btn-support-primary:hover { background: var(--blue-dk); }

    /* Hotline card */
    .hotline-card {
      background: var(--blue-lt);
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 10px;
    }
    .hotline-card .hl-label { font-size: 11px; color: var(--blue-dk); font-weight: 500; margin-bottom: 2px; }
    .hotline-card .hl-number { font-size: 20px; font-weight: 700; color: var(--blue-dk); letter-spacing: .5px; }
    .hotline-card .hl-hours { font-size: 11px; color: var(--muted); margin-top: 2px; }
    
    /* FAQ item */
    .faq-item {
      border-bottom: 1px solid var(--border);
      padding: 10px 0;
    }
    .faq-item:last-child { border-bottom: none; }
    .faq-q {
      font-size: 13px;
      font-weight: 500;
      color: var(--ink);
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 8px;
    }
    .faq-q svg { flex-shrink: 0; color: var(--muted); }
    .faq-a { font-size: 12px; color: var(--muted); margin-top: 6px; line-height: 1.55; display: none; }
    .faq-item.open .faq-a { display: block; }
    .faq-item.open .faq-q svg { transform: rotate(180deg); }

    /* Trạng thái lịch hẹn */
    .status-row {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
      border-bottom: 1px solid var(--border);
      font-size: 12px;
    }
    .status-row:last-child { border-bottom: none; }
    .status-dot {
      width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .dot-green { background: var(--green); }
    .dot-amber { background: #d97706; }
    .dot-blue  { background: var(--blue); }
    .status-info { flex: 1; }
    .status-info .s-name { font-weight: 500; color: var(--ink); }
    .status-info .s-time { color: var(--muted); }

    /* ───── FOOTER ───── */
    footer {
      background: var(--ink);
      color: rgba(255,255,255,.75);
      text-align: center;
      padding: 18px;
      font-size: 12px;
      margin-top: 32px;
    }
    footer a { color: rgba(255,255,255,.5); text-decoration: none; margin: 0 8px; }
    footer a:hover { color: #fff; }

    /* ───── DROPDOWN ───── */
    .dropdown-menu {
      font-size: 13px;
      border: 1px solid var(--border);
      border-radius: 10px;
      box-shadow: 0 8px 24px rgba(0,0,0,.08);
      padding: 6px;
    }
    .dropdown-item {
      border-radius: 7px;
      padding: 7px 12px;
    }
    .dropdown-item:hover { background: var(--blue-lt); color: var(--blue); }

    @media (max-width: 992px) {
      .page-body { grid-template-columns: 1fr; }
      .sidebar-left, .sidebar-right { padding: 0; }
      .main-content { border: none; padding: 0; }
      .posts-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<!-- ===== TOPBAR ===== -->
<header class="topbar">
  <a href="nhakhoa.php" class="logo">Nha Khoa <span>Việt Mỹ</span></a>

  <div class="topbar-right">
    <div class="search-wrap">
      <input type="text" placeholder="Tìm kiếm dịch vụ, bài viết...">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </div>

    <div class="hotline">📞 0385 063 701</div>

    <?php if ($da_dang_nhap): ?>
      <div class="dropdown">
        <button class="user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <div class="user-avatar"><?= mb_strtoupper(mb_substr($ten_nguoi_dung, 0, 2)) ?></div>
          <?= htmlspecialchars($ten_nguoi_dung) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="lichsu.php">Lịch sử yêu cầu</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="dangnhap/dangxuat.php">Đăng xuất</a></li>
        </ul>
      </div>
    <?php else: ?>
      <a href="dangnhap/dangnhap.php" class="btn-login">Đăng nhập</a>
      <a href="dangnhap/dangky.php" class="btn-register">Đăng ký</a>
    <?php endif; ?>
  </div>
</header>

<!-- ===== BODY ===== -->
<div class="page-body">

  <!-- ── LEFT SIDEBAR: DANH MỤC DỊCH VỤ ── -->
  <aside class="sidebar-left">
    <p class="sidebar-title">Danh mục dịch vụ</p>
<ul class="cat-list">
      <li class="cat-item">
         <a href="dichvu/tongquat.php">
          <span class="cat-icon">🦷</span> Tổng quát
        </a>
        
      </li>
      <li class="cat-item">
         <a href="dichvu/rangsu.php">
          <span class="cat-icon">✨</span> Răng sứ thẩm mỹ
        </a>
       
      </li>
      <li class="cat-item">
         <a href="dichvu/niengrang.php">
          <span class="cat-icon">🔧</span> Niềng răng
        </a>
        
      </li>
      <li class="cat-item">
        <a href="dichvu/implant.php">
          <span class="cat-icon">🔩</span> Trồng răng Implant
        </a>
          
      </li>
      <li class="cat-item">
        <a href="#">
          <span class="cat-icon">🦴</span> Răng tháo lắp
        </a>
      </li>

      <li class="cat-item">
  <a href="danhmuc/banggia.php">
    <span class="cat-icon">💰</span> Bảng giá
  </a>
</li>

<li class="cat-item">
  <a href="danhmuc/uudai.php">
    <span class="cat-icon">🎁</span> Ưu đãi
  </a>
</li>
</ul>

    <hr class="sidebar-divider">

    <p class="sidebar-title">Thông tin</p>
<ul class="cat-list">
  <li class="cat-item">
   <a href="pages/vechungtoi.php">
      <span class="cat-icon">👥</span> Về chúng tôi
    </a>
  </li>

  <li class="cat-item">
     <a href="pages/doingubacsi.php">
      <span class="cat-icon">🩺</span> Đội ngũ bác sĩ
    </a>
  </li>

  <li class="cat-item">
     <a href="pages/kienthuc.php">
      <span class="cat-icon">📊</span> Kiến thức
    </a>
  </li>

  <li class="cat-item">
     <a href="pages/lienhe.php">
      <span class="cat-icon">📍</span> Liên hệ
    </a>
  </li>
</ul>
  </aside>

  <!-- ── MAIN CONTENT ── -->
  <main class="main-content">

    <!-- Hero -->
    <div class="hero-strip">
      <h1>Chăm sóc nụ cười<br>của bạn</h1>
      <p>Đội ngũ bác sĩ chuyên nghiệp, trang thiết bị hiện đại.<br>Đặt lịch tư vấn miễn phí ngay hôm nay.</p>
      <a href="yeucau/guiyeucau.php" class="btn-hero">Gửi yêu cầu ngay →</a>
    </div>

    <!-- Dịch vụ nổi bật -->
<div class="section-header">
  <h2>Dịch vụ nổi bật</h2>
  <a href="#">Xem tất cả →</a>
</div>

<div class="services-row">

  <!-- Răng sứ -->
  <a href="dichvu/rangsu.php" class="svc-card">
    <div class="svc-icon">✨</div>
    <h4>Răng sứ</h4>
    <p>Thẩm mỹ hoàn hảo</p>
  </a>

  <!-- Niềng răng -->
  <a href="dichvu/niengrang.php" class="svc-card">
    <div class="svc-icon">🔧</div>
    <h4>Niềng răng</h4>
    <p>Chỉnh nha hiện đại</p>
  </a>

  <!-- Implant -->
  <a href="dichvu/implant.php" class="svc-card">
    <div class="svc-icon">🔩</div>
    <h4>Implant</h4>
    <p>Trồng răng bền vững</p>
  </a>

  <!-- Tổng quát -->
  <a href="dichvu/tongquat.php" class="svc-card">
    <div class="svc-icon">🦷</div>
    <h4>Tổng quát</h4>
    <p>Chăm sóc toàn diện</p>
  </a>

</div>

    <!-- Bài đăng nổi bật -->
    <div class="section-header">
      <h2>Bài đăng nổi bật</h2>
      <a href="#">Xem tất cả →</a>
    </div>
    <div class="posts-grid">
      <a href="#" class="post-card">
        <div class="post-card-img">🦷</div>
        <div class="post-card-body">
          <span class="post-tag">Kiến thức</span>
          <h3>Khi nào cần bọc răng sứ? Dấu hiệu nhận biết bạn cần điều trị</h3>
          <p class="post-meta">12/04/2026 · 5 phút đọc</p>
        </div>
      </a>
      <a href="#" class="post-card">
        <div class="post-card-img">😁</div>
        <div class="post-card-body">
          <span class="post-tag">Niềng răng</span>
          <h3>So sánh niềng mắc cài và niềng trong suốt: Loại nào phù hợp với bạn?</h3>
          <p class="post-meta">08/04/2026 · 4 phút đọc</p>
        </div>
      </a>
      <a href="#" class="post-card">
        <div class="post-card-img">🔩</div>
        <div class="post-card-body">
          <span class="post-tag">Implant</span>
          <h3>Trồng răng Implant có đau không? Quy trình chi tiết từ A–Z</h3>
          <p class="post-meta">03/04/2026 · 6 phút đọc</p>
        </div>
      </a>
      <a href="#" class="post-card">
        <div class="post-card-img">✨</div>
        <div class="post-card-body">
          <span class="post-tag">Ưu đãi</span>
          <h3>Tháng 4: Giảm 20% dịch vụ tẩy trắng răng cho khách hàng mới</h3>
          <p class="post-meta">01/04/2026 · 2 phút đọc</p>
        </div>
      </a>
    </div>

<div class="posts-grid">


  </main>

  <!-- ── RIGHT SIDEBAR: HỖ TRỢ BỆNH NHÂN ── -->
  <aside class="sidebar-right">

    <!-- Gửi yêu cầu -->
    <div class="support-card">
      <div class="support-card-head">
        <h3>Hỗ trợ bệnh nhân</h3>
        <p>Chúng tôi sẵn sàng giải đáp</p>
      </div>
      <div class="support-card-body">
        <a href="yeucau/guiyeucau.php" class="btn-support-primary">✉️ Gửi yêu cầu tư vấn</a>

        <div class="hotline-card">
          <div class="hl-label">Hotline hỗ trợ</div>
          <div class="hl-number">0385 063 701</div>
          <div class="hl-hours">Thứ 2 – Thứ 7 · 8:00 – 20:00</div>
        </div>

        <?php if (!$da_dang_nhap): ?>
        <a href="dangnhap.php" class="btn-login" style="display:block;text-align:center;margin-top:4px;">Đăng nhập để xem lịch hẹn</a>
        <?php else: ?>
        <p style="font-size:12px;color:var(--muted);margin-bottom:8px;font-weight:500;">Lịch hẹn gần đây</p>
        <div class="status-row">
          <div class="status-dot dot-green"></div>
          <div class="status-info">
            <div class="s-name">Khám tổng quát</div>
            <div class="s-time">15/04 · 09:00</div>
          </div>
        </div>
        <div class="status-row">
          <div class="status-dot dot-amber"></div>
          <div class="status-info">
            <div class="s-name">Niềng răng – tái khám</div>
            <div class="s-time">22/04 · 14:30</div>
          </div>
        </div>
        <a href="lichsu.php" style="font-size:12px;color:var(--blue);text-decoration:none;display:block;margin-top:8px;">Xem toàn bộ lịch sử →</a>
        <?php endif; ?>
      </div>
    </div>

    <!-- FAQ -->
    <div class="support-card">
      <div class="support-card-head">
        <h3>Câu hỏi thường gặp</h3>
      </div>
      <div class="support-card-body" style="padding-top:4px;">
        <div class="faq-item">
          <div class="faq-q">
            Implant có đau không?
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div class="faq-a">Quy trình được thực hiện dưới gây tê cục bộ, bệnh nhân hầu như không cảm thấy đau trong khi phẫu thuật.</div>
        </div>
        <div class="faq-item">
          <div class="faq-q">
            Niềng răng mất bao lâu?
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div class="faq-a">Thông thường từ 12–24 tháng tùy mức độ lệch lạc và loại mắc cài được sử dụng.</div>
        </div>
        <div class="faq-item">
          <div class="faq-q">
            Bảo hiểm y tế có áp dụng không?
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div class="faq-a">Một số dịch vụ cơ bản được hỗ trợ bảo hiểm. Vui lòng liên hệ để được tư vấn chi tiết.</div>
        </div>
        <div class="faq-item">
          <div class="faq-q">
            Có đặt lịch trực tuyến không?
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
          <div class="faq-a">Có, bạn có thể gửi yêu cầu trực tiếp trên website và chúng tôi sẽ xác nhận trong vòng 30 phút.</div>
        </div>
      </div>
    </div>

    <!-- Địa chỉ -->
    <div class="support-card">
      <div class="support-card-head">
        <h3>Địa chỉ phòng khám</h3>
      </div>
      <div class="support-card-body">
        <p style="font-size:12px;color:var(--muted);line-height:1.7;">
          📍 123 Đường Nguyễn Huệ, P.Bến Nghé,<br>Quận 1, TP. Hồ Chí Minh<br><br>
          🕐 Thứ 2 – Thứ 7: 8:00 – 20:00<br>
          🕐 Chủ nhật: 8:00 – 12:00
          <a href="https://www.google.com/maps/dir/?api=1&destination=..."
   class="btn btn-success px-4">
   🚗 Chỉ đường ngay
    </a>
        </p>
    </div>
    </div>
    
<!-- Đánh giá -->
<div class="support-card">
      <div class="support-card-head">
        <h3>Đánh giá</h3>
      </div>
  <div class="support-card-body">
    <form action="xulydanhgia.php" method="POST">

      <input type="text" name="ten" class="form-control mb-2" placeholder="Tên của bạn" required>

      <select name="sao" class="form-select mb-2">
        <option value="5">5⭐ - Rất tốt</option>
        <option value="4">4⭐ - Tốt</option>
        <option value="3">3⭐ - Bình thường</option>
        <option value="2">2⭐ - Kém</option>
        <option value="1">1⭐ - Rất kém</option>
      </select>

      <textarea name="noidung" class="form-control mb-2" rows="3" placeholder="Nhập đánh giá..." required></textarea>

      <button class="btn-support-primary">Gửi đánh giá</button>
    </form>
  </div>
</div>
</div>

 </aside>

</div>


<!-- ===== FOOTER ===== -->
<footer>
  <p>© 2026 Nha Khoa Việt Mỹ &nbsp;·&nbsp;
    <a href="#">Chính sách bảo mật</a>
    <a href="#">Điều khoản</a>
    <a href="#">Liên hệ</a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.faq-q').forEach(q => {
    q.addEventListener('click', () => {
      const item = q.closest('.faq-item');
      const wasOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
      if (!wasOpen) item.classList.add('open');
    });
  });
</script>
</body>
</html>


