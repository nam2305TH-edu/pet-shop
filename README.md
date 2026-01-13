# PetShop Management System

## Giới thiệu

Đây là dự án quản lý cửa hàng thú cưng (PetShop) được xây dựng bằng PHP. Hệ thống hỗ trợ quản lý sản phẩm, đơn hàng, người dùng, khuyến mãi, chatbot hỗ trợ khách hàng, thanh toán qua VNPay và nhiều tính năng khác.

## Tính năng chính
- Quản lý sản phẩm, loại sản phẩm, khuyến mãi, voucher
- Quản lý đơn hàng, chi tiết đơn hàng, vận chuyển
- Quản lý người dùng, phân quyền (admin, shipper, distributor, khách hàng)
- Chatbot hỗ trợ khách hàng
- Thống kê, báo cáo doanh thu
- Thanh toán trực tuyến qua VNPay
- Hệ thống bình luận, đánh giá sản phẩm
- Quản lý banner, thông báo, ticket hỗ trợ

## Cấu trúc thư mục
- `app/`         : Các class cốt lõi, cấu hình, helper
- `controllers/` : Các controller xử lý logic nghiệp vụ
- `models/`      : Các model truy xuất dữ liệu
- `views/`       : Giao diện người dùng (admin, frontend, backend)
- `public/`      : Tài nguyên tĩnh (CSS, JS, hình ảnh, uploads)
- `data/`        : Dữ liệu chatbot, từ ngữ cấm
- `db_migrations/`: Các file migration cho database
- `vnpay_php/`   : Tích hợp thanh toán VNPay
- `cmap/`        : Thư viện bản đồ (Google Maps API)
- `vendor/`      : Thư viện bên thứ ba (autoload, phpmailer, ...)

## Yêu cầu hệ thống
- PHP >= 7.4
- MySQL/MariaDB
- Composer
- Web server (Apache/Nginx)

## Cài đặt
1. Clone repository:
   ```bash
   git clone https://github.com/yourusername/petshop.git
   ```
2. Cài đặt các thư viện PHP:
   ```bash
   composer install
   ```
3. Tạo database và import file `phuongnamshop.sql`
4. Cấu hình kết nối database trong `app/config.php`
5. Cấu hình quyền cho thư mục `public/uploads/`
6. (Tùy chọn) Cấu hình VNPay trong `vnpay_php/config.php`

## Sử dụng
- Truy cập trang chủ: `/index.php`
- Đăng nhập admin, shipper, distributor để sử dụng các chức năng quản trị
- Khách hàng có thể đăng ký, đặt hàng, chat với chatbot, thanh toán online

## Đóng góp
Mọi đóng góp, báo lỗi hoặc đề xuất tính năng mới đều được hoan nghênh qua [Issues](https://github.com/yourusername/petshop/issues) hoặc Pull Request.

## License
Dự án sử dụng giấy phép MIT. Xem chi tiết trong file `LICENSE`.
