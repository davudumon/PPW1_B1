-- =========================================
-- CREATE TABLE
-- =========================================

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    photo VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    price_after_discount INT,
    discount DECIMAL(5,2) DEFAULT 0,
    stock INT NOT NULL,
    image BIGINT,
    gender ENUM('male', 'female')
);

CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    size ENUM('S', 'M', 'L', 'XL') NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_order INT NOT NULL,
    size ENUM('S', 'M', 'L', 'XL') NOT NULL,

    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =========================================
--  A. QUERY KOMPLEKS
-- =========================================

-- QUERY 1: Menampilkan total belanjaan per order beserta user
SELECT 
    o.id AS order_id,
    u.username,
    o.order_date,
    SUM(oi.quantity * p.price_after_discount) AS total_amount
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN order_items oi ON o.id = oi.order_id
JOIN products p ON oi.product_id = p.id
GROUP BY o.id, u.username, o.order_date
ORDER BY o.order_date DESC;

-- QUERY 2: Menampilkan daftar isi keranjang user
SELECT 
    u.username,
    p.name AS product_name,
    c.quantity
FROM cart c
INNER JOIN users u ON c.user_id = u.id
INNER JOIN products p ON c.product_id = p.id
ORDER BY u.username;

-- =========================================
--  B. VIEW
-- =========================================

-- VIEW 1: Menampilkan produk beserta harga setelah diskon
CREATE OR REPLACE VIEW view_products_discounted AS
SELECT 
    id, name, price, discount, 
    (price - (price * discount)) AS price_after_discount
FROM products;

-- VIEW 2: Menampilkan user dengan jumlah dan tanggal order terakhir
CREATE OR REPLACE VIEW view_user_orders AS
SELECT 
    u.username,
    COUNT(o.id) AS total_orders,
    MAX(o.order_date) AS last_order_date
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
GROUP BY u.username;

-- =========================================
--  C. FUNCTION
-- =========================================

-- FUNCTION 1: Menghitung harga setelah diskon
DELIMITER //
CREATE FUNCTION hitung_harga_diskon(harga INT, diskon DECIMAL(5,2))
RETURNS INT
DETERMINISTIC
BEGIN
    RETURN harga - (harga * diskon);
END //
DELIMITER ;

-- FUNCTION 2: Menampilkan status stok produk
DELIMITER //
CREATE FUNCTION cek_stok(stok INT)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    IF stok = 0 THEN
        RETURN 'Habis';
    ELSEIF stok < 5 THEN
        RETURN 'Hampir habis';
    ELSE
        RETURN 'Tersedia';
    END IF;
END //
DELIMITER ;

-- =========================================
--  D. STORED PROCEDURE
-- =========================================

DELIMITER //
CREATE PROCEDURE tambah_produk(
    IN p_nama VARCHAR(255),
    IN p_gambar VARCHAR(255),
    IN p_harga INT,
    IN p_stok INT,
    IN p_diskon DECIMAL(5,2),
    IN p_gender VARCHAR(10)
)
BEGIN
    DECLARE harga_diskon INT;
    SET harga_diskon = p_harga - (p_harga * p_diskon);
    
    INSERT INTO products (name, image, price, price_after_discount, stock, discount, gender)
    VALUES (p_nama, p_gambar, p_harga, harga_diskon, p_stok, p_diskon, p_gender);
END //
DELIMITER ;

-- =========================================
--  E. TRIGGER
-- =========================================

DELIMITER //
CREATE TRIGGER update_price_after_discount
BEFORE INSERT ON products
FOR EACH ROW
BEGIN
    SET NEW.price_after_discount = NEW.price - (NEW.price * NEW.discount);
END;
//
DELIMITER ;
