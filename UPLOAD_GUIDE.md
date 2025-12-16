# 📸 Hướng dẫn sử dụng chức năng Upload Ảnh

## ✨ Tính năng đã thêm

### 1. Upload ảnh khi thêm sản phẩm mới
- Vào `admin.php` → Click "Thêm sản phẩm mới"
- Điền thông tin sản phẩm
- **Chọn ảnh** từ máy tính (nút "Choose File")
- Click "Thêm sản phẩm"

### 2. Upload ảnh khi sửa sản phẩm
- Vào `admin.php` → Click "Sửa" ở sản phẩm
- **Xem ảnh hiện tại** (nếu có)
- **Chọn ảnh mới** để thay thế (hoặc bỏ trống để giữ ảnh cũ)
- Click "Cập nhật sản phẩm"

### 3. Tự động xóa ảnh
- Khi **xóa sản phẩm**, ảnh sẽ tự động bị xóa khỏi server
- Khi **thay ảnh mới**, ảnh cũ sẽ tự động bị xóa

## 🔒 Bảo mật & Giới hạn

### Định dạng file được chấp nhận
- ✅ JPG / JPEG
- ✅ PNG
- ✅ GIF
- ✅ WEBP

### Giới hạn
- 📏 Kích thước tối đa: **5MB**
- 🛡️ Chặn upload file PHP (bảo mật)
- 🔐 Kiểm tra MIME type thực sự

## 📁 Cấu trúc thư mục

```
gundam_shop/
├── images/                  # Thư mục chứa ảnh (tự động tạo)
│   ├── .htaccess           # Bảo mật thư mục
│   ├── gundam_xxxxx.jpg    # Ảnh sản phẩm (tên unique)
│   └── no-image.jpg        # Ảnh placeholder
├── config.php              # Thêm hàm uploadImage()
├── product_add.php         # Form upload ảnh
├── product_edit.php        # Form upload + preview ảnh cũ
├── index.php               # Hiển thị ảnh sản phẩm
└── product_detail.php      # Hiển thị ảnh chi tiết
```

## 🎯 Cách hoạt động

### Khi upload ảnh
1. Kiểm tra định dạng file (chỉ cho phép ảnh)
2. Kiểm tra kích thước (max 5MB)
3. Tạo tên file unique: `gundam_[timestamp][random].jpg`
4. Di chuyển file vào thư mục `images/`
5. Lưu tên file vào database
6. Xóa ảnh cũ (nếu có)

### Khi hiển thị ảnh
1. Kiểm tra file có tồn tại không
2. Nếu **có**: hiển thị ảnh thật
3. Nếu **không**: hiển thị icon emoji 🤖

## 🚀 Cài đặt

### Bước 1: Giải nén và upload
```bash
# Giải nén file zip
unzip gundam_shop_with_upload.zip

# Copy vào thư mục web
cp -r gundam_shop/* /path/to/htdocs/gundam_shop/
```

### Bước 2: Cấu hình quyền thư mục (Linux/Mac)
```bash
chmod 755 images/
chmod 644 images/.htaccess
```

### Bước 3: Kiểm tra php.ini
Đảm bảo các setting sau được bật:
```ini
file_uploads = On
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
```

## 📝 Cách sử dụng

### Thêm sản phẩm mới với ảnh
1. Truy cập: `http://localhost/gundam_shop/admin.php`
2. Click "Thêm sản phẩm mới"
3. Điền thông tin
4. Click "Choose File" → chọn ảnh Gundam
5. Click "Thêm sản phẩm"
6. ✓ Xong! Ảnh sẽ hiển thị trên trang chủ

### Sửa ảnh sản phẩm
1. Vào `admin.php`
2. Click "Sửa" ở sản phẩm cần đổi ảnh
3. Xem ảnh hiện tại
4. Click "Choose File" → chọn ảnh mới
5. Click "Cập nhật sản phẩm"
6. ✓ Ảnh cũ tự động bị xóa, ảnh mới được lưu

## ⚠️ Xử lý lỗi thường gặp

### Lỗi: "Không thể lưu file"
**Nguyên nhân:** Không có quyền ghi vào thư mục `images/`

**Giải pháp:**
```bash
# Linux/Mac
chmod 755 images/

# Windows: 
# Click chuột phải thư mục images → Properties → Security
# Cho phép quyền "Write" cho user hiện tại
```

### Lỗi: "File quá lớn"
**Nguyên nhân:** File > 5MB hoặc vượt quá giới hạn PHP

**Giải pháp:**
1. Resize ảnh trước khi upload
2. Hoặc tăng giới hạn trong `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Lỗi: "Chỉ chấp nhận file ảnh"
**Nguyên nhân:** Upload file không phải ảnh (PDF, Word, v.v.)

**Giải pháp:** Chỉ upload file JPG, PNG, GIF, WEBP

### Ảnh không hiển thị
**Kiểm tra:**
1. File có tồn tại trong thư mục `images/` không?
2. Tên file trong database có đúng không?
3. Đường dẫn `images/` có đúng không?
4. Quyền đọc file có OK không?

## 🎨 Tùy chỉnh

### Thay đổi kích thước ảnh hiển thị
**Trong index.php:**
```css
.product-image {
    height: 220px;  /* Thay đổi chiều cao */
}
```

**Trong product_detail.php:**
```css
.product-image-large {
    height: 400px;  /* Thay đổi chiều cao */
}
```

### Thay đổi giới hạn kích thước file
**Trong config.php:**
```php
// Đổi từ 5MB sang 10MB
if ($file['size'] > 10 * 1024 * 1024) {
    throw new Exception('Kích thước file tối đa 10MB!');
}
```

### Thêm định dạng file mới
**Trong config.php:**
```php
$allowed_types = [
    'image/jpeg', 
    'image/jpg', 
    'image/png', 
    'image/gif', 
    'image/webp',
    'image/svg+xml'  // Thêm SVG
];
```

## 📊 Thống kê

### Tính năng đã hoàn thiện
- ✅ Upload ảnh khi thêm sản phẩm
- ✅ Upload ảnh khi sửa sản phẩm
- ✅ Preview ảnh cũ khi sửa
- ✅ Tự động xóa ảnh cũ khi thay mới
- ✅ Tự động xóa ảnh khi xóa sản phẩm
- ✅ Kiểm tra định dạng file
- ✅ Kiểm tra kích thước file
- ✅ Bảo mật upload
- ✅ Tên file unique (không trùng lặp)
- ✅ Hiển thị ảnh trên trang chủ
- ✅ Hiển thị ảnh trang chi tiết

## 🔧 Code quan trọng

### Hàm uploadImage() trong config.php
```php
function uploadImage($file, $old_image = '') {
    // Xử lý upload, validate, rename, move file
    // Xóa ảnh cũ tự động
    return $new_filename;
}
```

### Form upload trong product_add.php
```html
<form enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*">
</form>
```

### Xử lý upload trong product_add.php
```php
$image_name = uploadImage($_FILES['image']);
```

## 💡 Tips

1. **Đặt tên ảnh có ý nghĩa** trước khi upload (vd: rx-78-2-gundam.jpg)
2. **Resize ảnh** trước khi upload để tối ưu tốc độ load
3. **Định dạng khuyến nghị**: JPG cho ảnh thật, PNG cho ảnh có nền trong suốt
4. **Kích thước khuyến nghị**: 800x800px hoặc 1000x1000px
5. **Backup thư mục images/** thường xuyên

## 🎓 Học thêm

### Upload file trong PHP
- `$_FILES` superglobal
- `move_uploaded_file()`
- `mime_content_type()`
- File permissions

### Bảo mật upload
- Validate MIME type
- Check file extension
- Rename uploaded files
- Store outside public directory
- Limit file size

---

**Chúc bạn sử dụng tốt! 🚀**
