<?php
require_once 'config.php';
$pdo = getDBConnection();

// Xử lý xóa sản phẩm
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    
    // Lấy thông tin ảnh trước khi xóa
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = :id");
    $stmt->execute([':id' => $delete_id]);
    $product = $stmt->fetch();
    
    // Xóa sản phẩm
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $delete_id]);
    
    // Xóa ảnh
    if ($product && !empty($product['image'])) {
        deleteImage($product['image']);
    }
    
    redirect('admin.php?msg=deleted');
}

// Lấy danh sách sản phẩm
$stmt = $pdo->query("SELECT p.*, c.name as category_name 
                     FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.id DESC");
$products = $stmt->fetchAll();

// Thông báo
$message = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'added':
            $message = '<div class="alert alert-success">✓ Thêm sản phẩm thành công!</div>';
            break;
        case 'updated':
            $message = '<div class="alert alert-success">✓ Cập nhật sản phẩm thành công!</div>';
            break;
        case 'deleted':
            $message = '<div class="alert alert-success">✓ Xóa sản phẩm thành công!</div>';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - Gundam Shop</title>
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
            max-width: 1400px;
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
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .nav-links {
            margin-top: 15px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            margin: 0 5px;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .nav-links a:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .content {
            padding: 30px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 1em;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .products-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .products-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95em;
        }
        
        .products-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .products-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .product-name-cell {
            font-weight: 600;
            color: #333;
        }
        
        .category-badge {
            display: inline-block;
            padding: 5px 12px;
            background: #e7f3ff;
            color: #0066cc;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        .price-cell {
            font-weight: 700;
            color: #e74c3c;
            font-size: 1.1em;
        }
        
        .stock-cell {
            font-weight: 600;
        }
        
        .stock-available {
            color: #27ae60;
        }
        
        .stock-out {
            color: #e74c3c;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-small {
            padding: 8px 15px;
            font-size: 0.9em;
        }
        
        .btn-edit {
            background: #3498db;
            color: white;
        }
        
        .btn-edit:hover {
            background: #2980b9;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #999;
            font-size: 1.2em;
        }
        
        @media (max-width: 1024px) {
            .products-table {
                font-size: 0.9em;
            }
            
            .products-table th,
            .products-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>⚙️ Quản lý sản phẩm Gundam</h1>
            <div class="nav-links">
                <a href="index.php">Trang chủ</a>
                <a href="admin.php">Quản lý sản phẩm</a>
            </div>
        </header>
        
        <div class="content">
            <?php echo $message; ?>
            
            <div class="toolbar">
                <h2>Danh sách sản phẩm (<?php echo count($products); ?>)</h2>
                <a href="product_add.php" class="btn btn-primary">+ Thêm sản phẩm mới</a>
            </div>
            
            <?php if (count($products) > 0): ?>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Grade</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th width="180">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td class="product-name-cell"><?php echo escape($product['name']); ?></td>
                                <td>
                                    <span class="category-badge">
                                        <?php echo escape($product['category_name'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                                <td><?php echo escape($product['grade'] ?? 'N/A'); ?></td>
                                <td class="price-cell"><?php echo formatCurrency($product['price']); ?></td>
                                <td class="stock-cell <?php echo $product['stock'] > 0 ? 'stock-available' : 'stock-out'; ?>">
                                    <?php echo $product['stock']; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="product_edit.php?id=<?php echo $product['id']; ?>" 
                                           class="btn btn-edit btn-small">Sửa</a>
                                        <a href="admin.php?delete=<?php echo $product['id']; ?>" 
                                           class="btn btn-delete btn-small"
                                           onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-products">
                    <p>📦 Chưa có sản phẩm nào!</p>
                    <p><a href="product_add.php" class="btn btn-primary" style="margin-top: 20px;">Thêm sản phẩm đầu tiên</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
