<?php
require_once 'config.php';
$pdo = getDBConnection();

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    redirect('index.php');
}

// Lấy thông tin sản phẩm
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name 
                       FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($product['name']); ?> - Gundam Shop</title>
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
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        header h1 {
            font-size: 1.8em;
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .product-detail {
            padding: 40px;
        }
        
        .product-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .product-image-large {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .product-image-large img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .product-image-large .no-image {
            font-size: 8em;
            color: #999;
        }
        
        .product-main-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .product-category {
            color: #667eea;
            font-size: 1em;
            font-weight: 600;
        }
        
        .product-name {
            font-size: 2.2em;
            font-weight: bold;
            color: #333;
            line-height: 1.2;
        }
        
        .product-series {
            color: #666;
            font-size: 1.1em;
        }
        
        .product-grade-scale {
            display: flex;
            gap: 20px;
            margin: 10px 0;
        }
        
        .badge {
            padding: 8px 16px;
            background: #f8f9fa;
            border-radius: 20px;
            font-weight: 600;
            color: #495057;
            border: 2px solid #e9ecef;
        }
        
        .product-price {
            font-size: 2.5em;
            color: #e74c3c;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .product-stock {
            font-size: 1.2em;
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .in-stock {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        
        .out-of-stock {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
        
        .product-description {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        
        .product-description h2 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 15px;
        }
        
        .product-description p {
            font-size: 1.1em;
            line-height: 1.8;
            color: #555;
        }
        
        .product-meta {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .product-meta table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .product-meta td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .product-meta td:first-child {
            font-weight: 600;
            color: #495057;
            width: 150px;
        }
        
        .product-meta td:last-child {
            color: #212529;
        }
        
        @media (max-width: 768px) {
            .product-header {
                grid-template-columns: 1fr;
            }
            
            .product-detail {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🤖 Chi tiết sản phẩm</h1>
            <a href="index.php" class="back-link">← Quay lại</a>
        </header>
        
        <div class="product-detail">
            <div class="product-header">
                <div class="product-image-large">
                    <?php if (!empty($product['image']) && file_exists('images/' . $product['image'])): ?>
                        <img src="images/<?php echo escape($product['image']); ?>" alt="<?php echo escape($product['name']); ?>">
                    <?php else: ?>
                        <div class="no-image">🤖</div>
                    <?php endif; ?>
                </div>
                
                <div class="product-main-info">
                    <div class="product-category"><?php echo escape($product['category_name'] ?? 'Chưa phân loại'); ?></div>
                    <h1 class="product-name"><?php echo escape($product['name']); ?></h1>
                    <div class="product-series">📺 Series: <?php echo escape($product['series']); ?></div>
                    
                    <div class="product-grade-scale">
                        <?php if (!empty($product['grade'])): ?>
                            <span class="badge">Grade: <?php echo escape($product['grade']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($product['scale'])): ?>
                            <span class="badge">Tỷ lệ: <?php echo escape($product['scale']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-price"><?php echo formatCurrency($product['price']); ?></div>
                    
                    <div class="product-stock <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                        <?php if ($product['stock'] > 0): ?>
                            ✓ Còn hàng: <?php echo $product['stock']; ?> sản phẩm
                        <?php else: ?>
                            ✗ Hết hàng
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($product['description'])): ?>
            <div class="product-description">
                <h2>Mô tả sản phẩm</h2>
                <p><?php echo nl2br(escape($product['description'])); ?></p>
            </div>
            <?php endif; ?>
            
            <div class="product-meta">
                <table>
                    <tr>
                        <td>Mã sản phẩm:</td>
                        <td>#<?php echo str_pad($product['id'], 5, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr>
                        <td>Danh mục:</td>
                        <td><?php echo escape($product['category_name'] ?? 'Chưa phân loại'); ?></td>
                    </tr>
                    <tr>
                        <td>Grade:</td>
                        <td><?php echo escape($product['grade'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td>Tỷ lệ:</td>
                        <td><?php echo escape($product['scale'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td>Series:</td>
                        <td><?php echo escape($product['series']); ?></td>
                    </tr>
                    <tr>
                        <td>Ngày thêm:</td>
                        <td><?php echo date('d/m/Y H:i', strtotime($product['created_at'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
