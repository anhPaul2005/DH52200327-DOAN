<?php
require_once 'config.php';
$pdo = getDBConnection();

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Lấy danh sách categories cho filter
$stmt_cat = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt_cat->fetchAll();

// Query sản phẩm với tìm kiếm và filter
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE 1=1";

$params = [];

if (!empty($search)) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.series LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_filter > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_filter;
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Mô Hình Gundam</title>
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
            max-width: 1200px;
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
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
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
            transform: translateY(-2px);
        }
        
        .search-section {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }
        
        .search-form input[type="text"] {
            flex: 1;
            min-width: 250px;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        .search-form input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .search-form select {
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
        }
        
        .search-form button {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .search-form button:hover {
            transform: scale(1.05);
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 30px;
        }
        
        .product-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        
        .product-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-image .no-image {
            font-size: 3em;
            color: #999;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-name {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            min-height: 50px;
        }
        
        .product-category {
            color: #667eea;
            font-size: 0.9em;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .product-series {
            color: #666;
            font-size: 0.85em;
            margin-bottom: 10px;
        }
        
        .product-price {
            font-size: 1.4em;
            color: #e74c3c;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .product-stock {
            font-size: 0.9em;
            color: #27ae60;
            margin-bottom: 15px;
        }
        
        .product-stock.out-of-stock {
            color: #e74c3c;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
        }
        
        .btn-view {
            background: #3498db;
            color: white;
        }
        
        .btn-view:hover {
            background: #2980b9;
        }
        
        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #999;
            font-size: 1.2em;
        }
        
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🤖 MOI Gundam</h1>
            <p>Bộ sưu tập mô hình Gundam chính hãng</p>
            <div class="nav-links">
                <a href="index.php">Trang chủ</a>
                <a href="admin.php">Quản lý sản phẩm</a>
            </div>
        </header>
        
        <div class="search-section">
            <form method="GET" action="index.php" class="search-form">
                <input type="text" name="search" placeholder="🔍 Tìm kiếm sản phẩm..." value="<?php echo escape($search); ?>">
                <select name="category">
                    <option value="0">Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $category_filter == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo escape($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Tìm kiếm</button>
                <?php if (!empty($search) || $category_filter > 0): ?>
                    <a href="index.php" class="btn btn-view" style="padding: 12px 30px; border-radius: 25px;">Xóa bộ lọc</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="products-grid">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($product['image']) && file_exists('images/' . $product['image'])): ?>
                                <img src="images/<?php echo escape($product['image']); ?>" alt="<?php echo escape($product['name']); ?>">
                            <?php else: ?>
                                <div class="no-image">🤖</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-category"><?php echo escape($product['category_name'] ?? 'Chưa phân loại'); ?></div>
                            <div class="product-name"><?php echo escape($product['name']); ?></div>
                            <div class="product-series">📺 <?php echo escape($product['series']); ?></div>
                            <div class="product-price"><?php echo formatCurrency($product['price']); ?></div>
                            <div class="product-stock <?php echo $product['stock'] == 0 ? 'out-of-stock' : ''; ?>">
                                <?php echo $product['stock'] > 0 ? "Còn hàng: {$product['stock']} sản phẩm" : "Hết hàng"; ?>
                            </div>
                            <div class="product-actions">
                                <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-view">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <p>😔 Không tìm thấy sản phẩm nào!</p>
                    <?php if (!empty($search) || $category_filter > 0): ?>
                        <p><a href="index.php" style="color: #667eea;">Quay lại xem tất cả sản phẩm</a></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <footer>
            <p>&copy; 2025 MOI Gundam Shop. Tất cả quyền được bảo lưu.</p>
        </footer>
    </div>
</body>
</html>
