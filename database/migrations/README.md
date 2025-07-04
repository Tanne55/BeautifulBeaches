# 🏖️ Website Đặt Tour Du Lịch Biển

> Đồ án môn học: Xây dựng Website với PHP Laravel  
> Tên dự án: **SeaTour - Trang web đặt tour du lịch biển**

---

## 📚 MỤC LỤC

    - [1. Giới thiệu](#1-giới-thiệu)
    - [2. Mục tiêu](#2-mục-tiêu)
    - [3. Chức năng chính](#3-chức-năng-chính)
    - [4. Đối tượng sử dụng](#4-đối-tượng-sử-dụng)
    - [5. Công nghệ sử dụng](#5-công-nghệ-sử-dụng)
    - [6. Kiến trúc thư mục](#6-kiến-trúc-thư-mục)
    - [7. Sơ đồ luồng hoạt động](#7-sơ-đồ-luồng-hoạt-động)
    - [8. Lộ trình phát triển](#8-lộ-trình-phát-triển)
    - [9. Hướng dẫn chạy dự án](#9-hướng-dẫn-chạy-dự-án)
    - [10. Khó khăn & giải pháp](#10-khó-khăn--giải-pháp)
    - [11. Ghi chú khác](#11-ghi-chú-khác)
    - [12. Tác giả](#12-tác-giả)

---

## 1. Giới thiệu

Dự án **SeaTour** là một trang web chuyên về **du lịch biển**, nơi người dùng có thể:
- Xem thông tin các tour du lịch bãi biển
- Tìm kiếm tour theo địa điểm, thời gian
- Đăng ký tài khoản, đăng nhập
- Đặt tour trực tuyến
- Quản lý đơn đặt tour

Trang web hướng tới trải nghiệm đơn giản, hình ảnh đẹp, dễ sử dụng và đầy đủ thông tin.

---

## 2. Mục tiêu

- Cung cấp nền tảng đặt tour trực tuyến chuyên biệt về du lịch biển
- Tăng cường trải nghiệm khách hàng với giao diện trực quan
- Rèn luyện kỹ năng lập trình web fullstack PHP Laravel
- Áp dụng MVC, routing, migrations, auth, RESTful API

---

## 3. Chức năng chính

**Người dùng:**
- Xem danh sách tour
- Tìm kiếm tour theo từ khoá, điểm đến
- Xem chi tiết tour
- Đăng ký, đăng nhập
- Đặt tour
- Quản lý lịch sử đặt tour

**Quản trị viên:**
- Quản lý danh sách tour (thêm/sửa/xoá)
- Quản lý người dùng
- Quản lý đơn đặt tour

---

## 4. Đối tượng sử dụng

- Du khách muốn tìm kiếm và đặt tour bãi biển
- Nhân viên/Quản trị viên của công ty du lịch
- Sinh viên/người học lập trình muốn tham khảo dự án mẫu

---

## 5. Công nghệ sử dụng

| Phần       | Công nghệ                         |
|------------|-----------------------------------|
| Frontend   | HTML, CSS, Bootstrap, Blade Templating |
| Backend    | PHP 8.x, Laravel 10.x             |
| Database   | MySQL                            |
| Hosting    | Localhost (XAMPP) / Deploy lên Hostinger / Vercel |
| Auth       | Laravel Breeze / Laravel UI       |
| Công cụ    | Git, GitHub, VS Code              |

---

## 6. Kiến trúc thư mục

/project-root
├── app/
│ ├── Http/
│ ├── Models/
│ └── ...
├── resources/
│ └── views/
│ ├── layouts/
│ ├── tours/
│ └── auth/
├── public/
│ └── images/
├── routes/
│ └── web.php
├── database/
│ └── migrations/
├── .env
└── README.md

yaml
Sao chép
Chỉnh sửa

---

## 7. Sơ đồ luồng hoạt động

[Trang chủ] → [Xem danh sách tour]
↓
[Chi tiết tour] → [Đăng nhập]
↓
[Đặt tour]
↓
[Lịch sử đặt tour]

[Admin login] → [Dashboard] → [Quản lý tour / đơn hàng]

yaml
Sao chép
Chỉnh sửa

---

## 8. Lộ trình phát triển

| Tuần | Công việc chính                            |
|------|--------------------------------------------|
| 1    | Phân tích yêu cầu, dựng mockup             |
| 2    | Khởi tạo Laravel, thiết kế cơ sở dữ liệu   |
| 3    | Tạo chức năng người dùng, đăng nhập, đăng ký |
| 4    | Quản lý tour, trang chi tiết tour          |
| 5    | Đặt tour, xem lịch sử, gửi email xác nhận  |
| 6    | Tối ưu giao diện, responsive, hoàn thiện   |

---

## 9. Hướng dẫn chạy dự án

```bash
git clone https://github.com/yourname/seatour.git
cd seatour
composer install
cp .env.example .env
php artisan key:generate

# Cấu hình database trong file .env

php artisan migrate --seed
php artisan serve
10. Khó khăn & giải pháp
Khó khăn	Giải pháp
Quản lý phiên người dùng	Dùng Laravel Breeze
Responsive mobile	Kết hợp Bootstrap và media queries
Tìm kiếm nâng cao	Sử dụng truy vấn với điều kiện LIKE
Phân quyền Admin/User	Dùng middleware & policy Laravel

11. Ghi chú khác
Các tour chỉ là dữ liệu demo, chưa thực tế

Giao diện hiện đang ưu tiên desktop, sẽ bổ sung mobile sau

Có thể mở rộng thêm thanh toán online sau đồ án

12. Tác giả
Họ và tên: Nhân Phạm

Email: yourname@example.com

Facebook: fb.com/yourprofile (nếu muốn)

Trường: [Tên trường của bạn]

Giảng viên hướng dẫn: [Tên thầy/cô nếu cần]