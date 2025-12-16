<?php
require_once 'config.php';
$pdo = getDBConnection();

// Lấy ID sản phẩm
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    redirect('admin.php');
}

// Lấy thông tin sản phẩm hiện tại
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    redirect('admin.php');
}

// Lấy danh sách categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();

// Xử lý form submit
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate dữ liệu
    $name = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    $grade = trim($_POST['grade'] ?? '');
    $scale = trim($_POST['scale'] ?? '');
    $series = trim($_POST['series'] ?? '');
    
    // Kiểm tra dữ liệu
    if (empty($name)) {
        $errors[] = "Tên sản phẩm không được để trống!";
    }
    
    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = "Giá sản phẩm phải là số lớn hơn 0!";
    }
    
    if (empty($series)) {
        $errors[] = "Series không được để trống!";
    }
    
    if ($stock < 0) {
        $errors[] = "Số lượng tồn kho không được âm!";
    }
    
    // Xử lý upload ảnh
    $image_name = $product['image']; // Giữ ảnh cũ
    if (isset($_FILES['image'])) {
        try {
            $new_image = uploadImage($_FILES['image'], $product['image']);
            if (!empty($new_image)) {
                $image_name = $new_image;
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
    
    // Nếu không có lỗi, cập nhật database
    if (empty($errors)) {
        try {
            $sql = "UPDATE products 
                    SET name = :name, 
                        category_id = :category_id, 
                        price = :price, 
                        description = :description, 
                        image = :image,
                        stock = :stock, 
                        grade = :grade, 
                        scale = :scale, 
                        series = :series 
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':category_id' => $category_id > 0 ? $category_id : null,
                ':price' => $price,
                ':description' => $description,
                ':image' => $image_name,
                ':stock' => $stock,
                ':grade' => $grade,
                ':scale' => $scale,
                ':series' => $series,
                ':id' => $product_id
            ]);
            
            redirect('admin.php?msg=updated');
        } catch (PDOException $e) {
            $errors[] = "Lỗi khi cập nhật sản phẩm: " . $e->getMessage();
        }
    }
} else {
    // Nếu chưa submit form, hiển thị dữ liệu hiện tại
    $_POST = $product;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm - Gundam Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        header h1 {
            font-size: 2em;
        }
        
        .product-id {
            font-size: 0.9em;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .form-container {
            padding: 40px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 2px solid #f5c6cb;
        }
        
        .alert-error ul {
            margin-left: 20px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 1em;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1em;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        .form-group input[type="file"] {
            padding: 10px;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .help-text {
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>✏️ Sửa thông tin sản phẩm</h1>
            <div class="product-id">ID: <?php echo $product_id; ?> - <?php echo escape($product['name']); ?></div>
            <a href="admin.php" class="back-link">← Quay lại danh sách</a>
        </header>
        
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="alert-error">
                    <strong>❌ Có lỗi xảy ra:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo escape($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Tên sản phẩm <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="<?php echo escape($_POST['name'] ?? ''); ?>" required>
                    <div class="help-text">Ví dụ: RX-78-2 Gundam, Wing Gundam Zero...</div>
                </div>
                
                <div class="form-group">
                    <label for="image">Ảnh sản phẩm</label>
                    <?php if (!empty($product['image']) && file_exists('images/' . $product['image'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="images/<?php echo escape($product['image']); ?>" 
                                 alt="Current image" 
                                 style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e9ecef;">
                            <div class="help-text">Ảnh hiện tại</div>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div class="help-text">Chấp nhận: JPG, PNG, GIF, WEBP. Tối đa 5MB. Bỏ trống nếu không muốn đổi ảnh.</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id">Danh mục</label>
                        <select id="category_id" name="category_id">
                            <option value="0">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo escape($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Giá (VNĐ) <span class="required">*</span></label>
                        <input type="number" id="price" name="price" step="1000" min="0" 
                               value="<?php echo escape($_POST['price'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="series">Series <span class="required">*</span></label>
                    <input type="text" id="series" name="series" value="<?php echo escape($_POST['series'] ?? ''); ?>" required>
                    <div class="help-text">Ví dụ: Mobile Suit Gundam, Gundam Wing, Gundam SEED...</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="grade">Grade</label>
                        <input type="text" id="grade" name="grade" value="<?php echo escape($_POST['grade'] ?? ''); ?>" 
                               placeholder="HG, MG, RG, PG...">
                    </div>
                    
                    <div class="form-group">
                        <label for="scale">Tỷ lệ</label>
                        <input type="text" id="scale" name="scale" value="<?php echo escape($_POST['scale'] ?? ''); ?>" 
                               placeholder="1/144, 1/100, 1/60...">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="stock">Số lượng tồn kho</label>
                    <input type="number" id="stock" name="stock" min="0" 
                           value="<?php echo escape($_POST['stock'] ?? '0'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả sản phẩm</label>
                    <textarea id="description" name="description"><?php echo escape($_POST['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">✓ Cập nhật sản phẩm</button>
                    <a href="admin.php" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
