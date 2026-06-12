CREATE DATABASE IF NOT EXISTS NhaKhoaDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE NhaKhoaDB;

-- 1. Bảng VaiTro
CREATE TABLE VaiTro (
    MaVaiTro INT PRIMARY KEY AUTO_INCREMENT,
    TenVaiTro VARCHAR(50) NOT NULL
);

-- 2. Bảng NhanVien (Nhân viên có 1 Vai trò)
CREATE TABLE NhanVien (
    MaNhanVien INT PRIMARY KEY AUTO_INCREMENT,
    TenNhanVien VARCHAR(100) NOT NULL,
    SDT VARCHAR(15),
    Email VARCHAR(100) NOT NULL UNIQUE,
    MatKhau VARCHAR(255) NOT NULL,
    MaVaiTro INT,
    FOREIGN KEY (MaVaiTro) REFERENCES VaiTro(MaVaiTro)
);

-- 3. Bảng NguoiDung (Khách hàng)
CREATE TABLE NguoiDung (
    MaNguoiDung INT PRIMARY KEY AUTO_INCREMENT,
    TenNguoiDung VARCHAR(100) NOT NULL,
    SDT VARCHAR(15),
    Email VARCHAR(100) NOT NULL UNIQUE,
    MatKhau VARCHAR(255) NOT NULL,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 4. Bảng DanhMucYeuCau
CREATE TABLE DanhMucYeuCau (
    MaDanhMuc INT PRIMARY KEY AUTO_INCREMENT,
    MoTa TEXT NOT NULL
);

-- 5. Bảng BangYeuCau (Người dùng gửi yêu cầu, thuộc Danh mục)
CREATE TABLE BangYeuCau (
    MaYeuCau INT PRIMARY KEY AUTO_INCREMENT,
    TieuDe VARCHAR(200) NOT NULL,
    NoiDung TEXT NOT NULL,
    TrangThai VARCHAR(30) DEFAULT 'Chờ Xử Lý',
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    NgayCapNhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    MaKhachHang INT,
    MaDanhMuc INT,
    FOREIGN KEY (MaKhachHang) REFERENCES NguoiDung(MaNguoiDung),
    FOREIGN KEY (MaDanhMuc) REFERENCES DanhMucYeuCau(MaDanhMuc)
);

-- 6. Bảng BangPhanHoi (Nhân viên thực hiện phản hồi cho Yêu cầu)
CREATE TABLE BangPhanHoi (
    MaPhanHoi INT PRIMARY KEY AUTO_INCREMENT,
    NoiDung TEXT NOT NULL,
    NgayTao DATETIME DEFAULT CURRENT_TIMESTAMP,
    MaNhanVien INT,
    MaYeuCau INT,
    FOREIGN KEY (MaNhanVien) REFERENCES NhanVien(MaNhanVien),
    FOREIGN KEY (MaYeuCau) REFERENCES BangYeuCau(MaYeuCau)
);

-- 7. Bảng BangDanhGia (Người dùng gửi đánh giá cho Yêu cầu/Phản hồi)
CREATE TABLE BangDanhGia (
    MaDanhGia INT PRIMARY KEY AUTO_INCREMENT,
    SoSao TINYINT CHECK (SoSao BETWEEN 1 AND 5),
    NhanXet VARCHAR(255),
    NgayDanhGia DATETIME DEFAULT CURRENT_TIMESTAMP,
    MaYeuCau INT UNIQUE, 
    MaNguoiDung INT,
    FOREIGN KEY (MaYeuCau) REFERENCES BangYeuCau(MaYeuCau),
    FOREIGN KEY (MaNguoiDung) REFERENCES NguoiDung(MaNguoiDung)
);

-- 8. Bảng LichsuXuly (Nhân viên xem và lưu trữ lịch sử từ Yêu cầu)
CREATE TABLE LichsuXuly (
    MaLichSu INT PRIMARY KEY AUTO_INCREMENT,
    NoiDung TEXT NOT NULL,
    ThoiGian DATETIME DEFAULT CURRENT_TIMESTAMP,
    MaNhanVien INT,
    MaYeuCau INT,
    FOREIGN KEY (MaNhanVien) REFERENCES NhanVien(MaNhanVien),
    FOREIGN KEY (MaYeuCau) REFERENCES BangYeuCau(MaYeuCau)
);