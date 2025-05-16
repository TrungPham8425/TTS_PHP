<?php
// index.php - Xử lý logic nghiệp vụ và thao tác dữ liệu cho TechFactory

require_once 'db.php';

class TechFactory
{
    private $db;

    /**
     * Khởi tạo kết nối cơ sở dữ liệu
     */
    public function __construct()
    {
        $this->db = (new Db())->connectDB();
        if (!$this->db) {
            die("Không thể tiếp tục mà không có kết nối cơ sở dữ liệu");
        }
    }

    /**
     * Thêm 5 sản phẩm mẫu vào bảng products (4.1, 4.2)
     */
    public function insertProducts()
    {
        try {
            // Sửa lỗi cú pháp: VALUE -> VALUES
            $stmt = $this->db->prepare("
                INSERT INTO products (product_name, unit_price, stock_quantity) 
                VALUES (:product_name, :unit_price, :stock_quantity)
            ");

            // Dữ liệu sản phẩm mẫu
            $products = [
                ['product_name' => 'Động cơ công nghiệp', 'unit_price' => 1500000, 'stock_quantity' => 50],
                ['product_name' => 'Cảm biến nhiệt độ', 'unit_price' => 250000, 'stock_quantity' => 100],
                ['product_name' => 'Bảng điều khiển', 'unit_price' => 2000000, 'stock_quantity' => 30],
                ['product_name' => 'Cảm biến áp suất', 'unit_price' => 350000, 'stock_quantity' => 75],
                ['product_name' => 'Động cơ servo', 'unit_price' => 1200000, 'stock_quantity' => 40]
            ];

            foreach ($products as $product) {
                $stmt->execute($product);
                // Hiển thị ID của sản phẩm vừa thêm
                echo "Đã thêm sản phẩm: {$product['product_name']}, ID: " . $this->db->lastInsertId() . "<br>";
            }
        } catch (PDOException $e) {
            echo "Lỗi khi thêm sản phẩm: " . $e->getMessage() . "<br>";
        }
    }
}

// Khởi tạo cơ sở dữ liệu trước
try {
    $db = new Db();
    $db->initializeDatabase();
} catch (Exception $e) {
    echo "Lỗi khởi tạo cơ sở dữ liệu: " . $e->getMessage() . "<br>";
    die();
}

// Tạo instance của TechFactory và gọi insertProducts
try {
    $techFactory = new TechFactory();
    $techFactory->insertProducts();
} catch (Exception $e) {
    echo "Lỗi khi thực thi: " . $e->getMessage() . "<br>";
}
