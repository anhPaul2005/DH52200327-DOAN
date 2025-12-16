<?php

// Cấu hình kết nối database (Edit these values with your InfinityFree info)
define('DB_HOST', 'sql110.infinityfree.com');
define('DB_NAME', 'if0_40617067_db_gundam');
define('DB_USER', 'if0_40617067');
define('DB_PASS', 'Tuananh2901'); // <-- replace with the actual MySQL password

function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // For production, don't echo raw error; log it. For now show simple message:
        die("Lỗi kết nối database.");
    }
}

// Hàm helper để format tiền VND
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}

// Hàm helper để escape HTML
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Hàm helper để redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Hàm upload ảnh
function uploadImage($file, $old_image = '') {
    $upload_dir = __DIR__ . '/images/'; // use absolute path
    $web_dir    = 'images/';            // keep web path for DB

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Kiểm tra có file upload không
    if (!isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE) {
        return $old_image; // Giữ nguyên ảnh cũ
    }
    
    // Kiểm tra lỗi upload
    if ($file['error'] != UPLOAD_ERR_OK) {
        throw new Exception('Lỗi khi upload file!');
    }
    
    // Kiểm tra định dạng file
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
        throw new Exception('Chỉ chấp nhận file ảnh JPG, PNG, GIF, WEBP!');
    }
    
    // Kiểm tra kích thước file (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('Kích thước file tối đa 5MB!');
    }
    
    // Tạo tên file mới (unique)
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('gundam_') . '.' . $extension;
    $upload_path = $upload_dir . $new_filename;
    
    // Di chuyển file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Không thể lưu file! Kiểm tra quyền ghi thư mục images/.');
    }
    
    // Xóa ảnh cũ nếu có
    if (!empty($old_image) && file_exists($upload_dir . $old_image)) {
        unlink($upload_dir . $old_image);
    }
    
    return $new_filename;
}

// Hàm xóa ảnh
function deleteImage($filename) {
    $upload_dir = __DIR__ . '/images/';
    if (!empty($filename) && file_exists($upload_dir . $filename)) {
        unlink($upload_dir . $filename);
    }
}

// // Cấu hình kết nối database
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'gundam_shop');
// define('DB_USER', 'root');
// define('DB_PASS', '');

// // // Tạo kết nối PDO

// function getDBConnection() {
//     try {
//         $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
//         $options = [
//             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//             PDO::ATTR_EMULATE_PREPARES => false,
//         ];
        
//         $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
//         return $pdo;
//     } catch (PDOException $e) {
//         die("Lỗi kết nối database: " . $e->getMessage());
//     }
// }


// // Hàm helper để format tiền VND
// function formatCurrency($amount) {
//     return number_format($amount, 0, ',', '.') . ' ₫';
// }

// // Hàm helper để escape HTML
// function escape($string) {
//     return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
// }

// // Hàm helper để redirect
// function redirect($url) {
//     header("Location: $url");
//     exit();
// }

// // Hàm upload ảnh
// function uploadImage($file, $old_image = '') {
//     $upload_dir = 'images/';
    
//     // Tạo thư mục nếu chưa tồn tại
//     if (!is_dir($upload_dir)) {
//         mkdir($upload_dir, 0755, true);
//     }
    
//     // Kiểm tra có file upload không
//     if (!isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE) {
//         return $old_image; // Giữ nguyên ảnh cũ
//     }
    
//     // Kiểm tra lỗi upload
//     if ($file['error'] != UPLOAD_ERR_OK) {
//         throw new Exception('Lỗi khi upload file!');
//     }
    
//     // Kiểm tra định dạng file
//     $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
//     $file_type = mime_content_type($file['tmp_name']);
    
//     if (!in_array($file_type, $allowed_types)) {
//         throw new Exception('Chỉ chấp nhận file ảnh JPG, PNG, GIF, WEBP!');
//     }
    
//     // Kiểm tra kích thước file (max 5MB)
//     if ($file['size'] > 5 * 1024 * 1024) {
//         throw new Exception('Kích thước file tối đa 5MB!');
//     }
    
//     // Tạo tên file mới (unique)
//     $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
//     $new_filename = uniqid('gundam_') . '.' . $extension;
//     $upload_path = $upload_dir . $new_filename;
    
//     // Di chuyển file
//     if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
//         throw new Exception('Không thể lưu file!');
//     }
    
//     // Xóa ảnh cũ nếu có
//     if (!empty($old_image) && file_exists($upload_dir . $old_image)) {
//         unlink($upload_dir . $old_image);
//     }
    
//     return $new_filename;
// }

// // Hàm xóa ảnh
// function deleteImage($filename) {
//     $upload_dir = 'images/';
//     if (!empty($filename) && file_exists($upload_dir . $filename)) {
//         unlink($upload_dir . $filename);
//     }
// }



// DOCKER

// // Cấu hình kết nối database (thay đổi để chạy trong Docker)
// define('DB_HOST', 'db');        // trong docker-compose service tên là "db"
// define('DB_NAME', 'gundam_shop');
// define('DB_USER', 'root');
// define('DB_PASS', 'root');      // phải khớp với MYSQL_ROOT_PASSWORD trong docker-compose

// // Tạo kết nối PDO
// function getDBConnection() {
//     try {
//         // thêm port và charset rõ ràng
//         $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME . ";charset=utf8mb4";
//         $options = [
//             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//             PDO::ATTR_EMULATE_PREPARES => false,
//         ];
        
//         $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
//         return $pdo;
//     } catch (PDOException $e) {
//         die("Lỗi kết nối database: " . $e->getMessage());
//     }
// }

// // Hàm helper để format tiền VND
// function formatCurrency($amount) {
//     return number_format($amount, 0, ',', '.') . ' ₫';
// }

// // Hàm helper để escape HTML
// function escape($string) {
//     return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
// }

// // Hàm helper để redirect
// function redirect($url) {
//     header("Location: $url");
//     exit();
// }

// // Hàm upload ảnh
// function uploadImage($file, $old_image = '') {
//     $upload_dir = 'images/';
    
//     // Tạo thư mục nếu chưa tồn tại
//     if (!is_dir($upload_dir)) {
//         mkdir($upload_dir, 0755, true);
//     }
    
//     // Kiểm tra có file upload không
//     if (!isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE) {
//         return $old_image; // Giữ nguyên ảnh cũ
//     }
    
//     // Kiểm tra lỗi upload
//     if ($file['error'] != UPLOAD_ERR_OK) {
//         throw new Exception('Lỗi khi upload file!');
//     }
    
//     // Kiểm tra định dạng file
//     $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
//     $file_type = mime_content_type($file['tmp_name']);
    
//     if (!in_array($file_type, $allowed_types)) {
//         throw new Exception('Chỉ chấp nhận file ảnh JPG, PNG, GIF, WEBP!');
//     }
    
//     // Kiểm tra kích thước file (max 5MB)
//     if ($file['size'] > 5 * 1024 * 1024) {
//         throw new Exception('Kích thước file tối đa 5MB!');
//     }
    
//     // Tạo tên file mới (unique)
//     $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
//     $new_filename = uniqid('gundam_') . '.' . $extension;
//     $upload_path = $upload_dir . $new_filename;
    
//     // Di chuyển file
//     if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
//         throw new Exception('Không thể lưu file!');
//     }
    
//     // Xóa ảnh cũ nếu có
//     if (!empty($old_image) && file_exists($upload_dir . $old_image)) {
//         unlink($upload_dir . $old_image);
//     }
    
//     return $new_filename;
// }

// // Hàm xóa ảnh
// function deleteImage($filename) {
//     $upload_dir = 'images/';
//     if (!empty($filename) && file_exists($upload_dir . $filename)) {
//         unlink($upload_dir . $filename);
//     }
// }


?>
