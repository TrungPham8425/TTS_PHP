<?php
// File tạo cơ sở dữ liệu và các bảng cho TechFactory
require_once 'db.php';

try {
    // Tạo kết nối tạm thời để tạo database
    $tempConn = new PDO("mysql:host=localhost;charset=utf8mb4", 'root', '');
    // Tạo cơ sở dữ liệu tech_factory nếu chưa tồn tại
    $tempConn->exec("CREATE DATABASE IF NOT EXISTS tech_factory");

    // Kết nối tới cơ sở dữ liệu tech_factory
    $db = new Database();
    $conn = $db->connect();

    // Tạo bảng products
    $conn->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT PRIMARY KEY AUTO_INCREMENT,
            product_name VARCHAR(100) NOT NULL,
            unit_price DECIMAL(10,2) NOT NULL,
            stock_quantity INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Tạo bảng orders
    $conn->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT PRIMARY KEY AUTO_INCREMENT,
            order_date DATE NOT NULL,
            customer_name VARCHAR(100) NOT NULL,
            note TEXT
        )
    ");

    // Tạo bảng order_items
    $conn->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INT PRIMARY KEY AUTO_INCREMENT,
            order_id INT,
            product_id INT,
            quantity INT NOT NULL,
            price_at_order_time DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ");
    echo "Tạo cơ sở dữ liệu và bảng thành công!<br>";
} catch (PDOException $e) {
    echo "Tạo cơ sở dữ liệu thất bại: " . $e->getMessage();
}
