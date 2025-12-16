# 🤖 Website Bán Mô Hình Gundam

Đồ án website bán mô hình Gundam sử dụng PHP thuần và PDO kết nối Database.

## ✨ Tính năng

### Trang khách hàng (index.php)
- ✅ Hiển thị danh sách sản phẩm Gundam
- 🔍 Tìm kiếm sản phẩm theo tên, mô tả, series
- 🗂️ Lọc sản phẩm theo danh mục (HG, MG, RG, PG, SD)
- 👁️ Xem chi tiết sản phẩm
- 📱 Giao diện responsive, thân thiện

### Trang quản trị (admin.php)
- 📋 Hiển thị danh sách tất cả sản phẩm
- ➕ Thêm sản phẩm mới
- ✏️ Cập nhật thông tin sản phẩm
- ❌ Xóa sản phẩm
- 💾 Quản lý tồn kho

### Công nghệ sử dụng
- **Backend**: PHP thuần (không framework)
- **Database**: MySQL với PDO (PHP Data Objects)
- **Frontend**: HTML5, CSS3
- **Bảo mật**: Prepared Statements, XSS Protection

## 📁 Cấu trúc thư mục

```
gundam_shop/
├── config.php              # Cấu hình database & functions
├── index.php               # Trang chủ - danh sách sản phẩm
├── product_detail.php      # Chi tiết sản phẩm
├── admin.php               # Quản lý sản phẩm
├── product_add.php         # Thêm sản phẩm mới
├── product_edit.php        # Sửa sản phẩm
├── gundam_shop.sql         # File tạo database
└── README.md               # File hướng dẫn này
```

## 🚀 Cài đặt

### Yêu cầu hệ thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên / MariaDB 10.2 trở lên
- Web Server (Apache/Nginx) hoặc PHP Built-in Server

### Các bước cài đặt

#### Bước 1: Tạo Database
```sql
-- Chạy file gundam_shop.sql trong phpMyAdmin hoặc MySQL CLI
mysql -u root -p < gundam_shop.sql
```

Hoặc:
1. Mở phpMyAdmin
2. Tạo database mới tên `gundam_shop`
3. Import file `gundam_shop.sql`

#### Bước 2: Cấu hình kết nối Database
Mở file `config.php` và chỉnh sửa thông tin kết nối:

```php
define('DB_HOST', 'localhost');      // Host database
define('DB_NAME', 'gundam_shop');    // Tên database
define('DB_USER', 'root');           // Username
define('DB_PASS', '');               // Password
```

#### Bước 3: Chạy website

**Cách 1: Sử dụng XAMPP/WAMP/MAMP**
1. Copy toàn bộ files vào thư mục `htdocs` (XAMPP) hoặc `www` (WAMP)
2. Truy cập: `http://localhost/gundam_shop/`

**Cách 2: Sử dụng PHP Built-in Server**
```bash
cd /path/to/gundam_shop
php -S localhost:8000
```
Truy cập: `http://localhost:8000`

## 📖 Hướng dẫn sử dụng

### Trang khách hàng

1. **Xem danh sách sản phẩm**
   - Truy cập `index.php`
   - Hiển thị tất cả sản phẩm Gundam

2. **Tìm kiếm sản phẩm**
   - Nhập từ khóa vào ô tìm kiếm
   - Chọn danh mục (tùy chọn)
   - Click "Tìm kiếm"

3. **Xem chi tiết**
   - Click "Xem chi tiết" trên mỗi sản phẩm
   - Hiển thị đầy đủ thông tin: giá, grade, tỷ lệ, mô tả...

### Trang quản trị

1. **Thêm sản phẩm mới**
   - Vào `admin.php` → Click "Thêm sản phẩm mới"
   - Điền đầy đủ thông tin
   - Click "Thêm sản phẩm"

2. **Sửa sản phẩm**
   - Tại `admin.php` → Click "Sửa" ở sản phẩm cần sửa
   - Cập nhật thông tin
   - Click "Cập nhật sản phẩm"

3. **Xóa sản phẩm**
   - Tại `admin.php` → Click "Xóa" ở sản phẩm cần xóa
   - Xác nhận xóa

## 🗄️ Cấu trúc Database

### Bảng `categories` (Danh mục)
- `id` - ID danh mục (Primary Key)
- `name` - Tên danh mục (HG, MG, RG, PG, SD)
- `description` - Mô tả danh mục
- `created_at` - Ngày tạo

### Bảng `products` (Sản phẩm)
- `id` - ID sản phẩm (Primary Key)
- `name` - Tên sản phẩm
- `category_id` - ID danh mục (Foreign Key)
- `price` - Giá sản phẩm
- `description` - Mô tả chi tiết
- `image` - Tên file ảnh
- `stock` - Số lượng tồn kho
- `grade` - Cấp độ (HG, MG, RG, PG, SD)
- `scale` - Tỷ lệ (1/144, 1/100, 1/60)
- `series` - Series Gundam
- `created_at` - Ngày tạo
- `updated_at` - Ngày cập nhật

## 🔒 Tính năng bảo mật

1. **PDO Prepared Statements** - Chống SQL Injection
2. **XSS Protection** - Escape output với `htmlspecialchars()`
3. **Input Validation** - Kiểm tra dữ liệu đầu vào
4. **Type Casting** - Ép kiểu dữ liệu số

## 🎨 Giao diện

- Design hiện đại với gradient màu
- Responsive trên mọi thiết bị
- Icons emoji thay vì ảnh (giảm tải tài nguyên)
- Hover effects mượt mà
- Alert messages trực quan

## 📝 Dữ liệu mẫu

Database đã bao gồm 10 sản phẩm Gundam mẫu:
- RX-78-2 Gundam (MG)
- Wing Gundam Zero EW (MG)
- Strike Freedom Gundam (RG)
- Unicorn Gundam (PG)
- Barbatos Lupus Rex (HG)
- Và nhiều mô hình khác...

## 🛠️ Tùy chỉnh

### Thay đổi màu sắc
Chỉnh sửa gradient trong các file `.php`:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Thêm danh mục mới
```sql
INSERT INTO categories (name, description) 
VALUES ('Tên danh mục', 'Mô tả');
```

## ⚠️ Lưu ý

- Đây là bài tập học tập, chưa có xác thực đăng nhập
- Chưa xử lý upload ảnh (có thể mở rộng thêm)
- Chưa có phân trang (có thể thêm sau)
- Nên thêm authentication cho trang admin trong thực tế

## 🎓 Mục đích học tập

Đồ án này phù hợp cho:
- ✅ Học PHP cơ bản
- ✅ Học PDO và database
- ✅ Học CRUD operations
- ✅ Học form handling & validation
- ✅ Học cấu trúc project PHP

## 📞 Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra kết nối database trong `config.php`
2. Đảm bảo đã import file `gundam_shop.sql`
3. Kiểm tra PHP version >= 7.4
4. Bật error reporting: `error_reporting(E_ALL);`

## 📄 License

Đồ án học tập - Tự do sử dụng cho mục đích học tập.

---

**Chúc bạn code vui vẻ! 🚀**

## Update
- Update from dev branch
a