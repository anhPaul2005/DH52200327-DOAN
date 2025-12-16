CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    grade VARCHAR(50),
    scale VARCHAR(50),
    series VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name, description) VALUES
('High Grade (HG)', 'Mô hình Gundam cấp độ High Grade, tỷ lệ 1/144'),
('Master Grade (MG)', 'Mô hình Gundam cấp độ Master Grade, tỷ lệ 1/100'),
('Real Grade (RG)', 'Mô hình Gundam cấp độ Real Grade với chi tiết cao'),
('Perfect Grade (PG)', 'Mô hình Gundam cấp độ Perfect Grade cao cấp'),
('SD Gundam', 'Mô hình Gundam phong cách Super Deformed');

INSERT INTO products (name, category_id, price, description, image, stock, grade, scale, series) VALUES
('RX-78-2 Gundam', 2, 850000, 'Gundam đầu tiên của Amuro Ray trong Mobile Suit Gundam', 'rx78-2.jpg', 15, 'MG', '1/100', 'Mobile Suit Gundam'),
('Wing Gundam Zero EW', 2, 920000, 'Gundam mạnh nhất từ series Gundam Wing Endless Waltz', 'wing-zero.jpg', 10, 'MG', '1/100', 'Gundam Wing'),
('Strike Freedom Gundam', 3, 1200000, 'Gundam của Kira Yamato với hệ thống vũ khí mạnh mẽ', 'strike-freedom.jpg', 8, 'RG', '1/144', 'Gundam SEED Destiny'),
('Unicorn Gundam', 4, 2500000, 'Gundam có khả năng biến hình giữa Unicorn Mode và Destroy Mode', 'unicorn.jpg', 5, 'PG', '1/60', 'Gundam Unicorn'),
('Barbatos Lupus Rex', 1, 450000, 'Gundam chính từ series Iron-Blooded Orphans', 'barbatos.jpg', 20, 'HG', '1/144', 'Gundam IBO'),
('Zaku II Char Custom', 2, 780000, 'MS đỏ nổi tiếng của Char Aznable', 'zaku-char.jpg', 12, 'MG', '1/100', 'Mobile Suit Gundam'),
('Nu Gundam', 3, 1350000, 'Gundam cuối cùng của Amuro Ray', 'nu-gundam.jpg', 7, 'RG', '1/144', 'Chars Counterattack'),
('Exia', 1, 520000, 'Gundam của Setsuna F. Seiei', 'exia.jpg', 18, 'HG', '1/144', 'Gundam 00'),
('Sazabi Ver.Ka', 2, 1450000, 'MS cuối cùng của Char Aznable với thiết kế Ver.Ka', 'sazabi.jpg', 6, 'MG', '1/100', 'Chars Counterattack'),
('SD Gundam Cross Silhouette RX-78-2', 5, 280000, 'Phiên bản SD với khả năng tùy biến cao', 'sd-rx78.jpg', 25, 'SD', 'SD', 'Mobile Suit Gundam');
