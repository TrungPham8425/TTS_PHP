<?php
// File chứa các thao tác dữ liệu cho TechFactory
require_once 'db.php';

class TechFactory
{
    private $conn;

    // Khởi tạo với kết nối cơ sở dữ liệu
    public function __construct($db)
    {
        $this->conn = $db->connect();
    }

    // 4.1 Thêm 5 sản phẩm mẫu
    public function insertSampleProducts()
    {
        try {
            $products = [
                ['name' => 'Động cơ công nghiệp', 'price' => 1500000, 'quantity' => 100],
                ['name' => 'Cảm biến nhiệt độ', 'price' => 500000, 'quantity' => 200],
                ['name' => 'Bảng điều khiển', 'price' => 2000000, 'quantity' => 50],
                ['name' => 'Cảm biến áp suất', 'price' => 800000, 'quantity' => 150],
                ['name' => 'Động cơ servo', 'price' => 2500000, 'quantity' => 75]
            ];

            $stmt = $this->conn->prepare("
                INSERT INTO products (product_name, unit_price, stock_quantity) 
                VALUES (:name, :price, :quantity)
            ");

            foreach ($products as $product) {
                $stmt->execute([
                    ':name' => $product['name'],
                    ':price' => $product['price'],
                    ':quantity' => $product['quantity']
                ]);
                // 4.2 In ID của sản phẩm vừa thêm
                echo "Đã thêm sản phẩm: {$product['name']}, ID: " . $this->conn->lastInsertId() . "\n";
            }
        } catch (PDOException $e) {
            echo "Thêm sản phẩm thất bại: " . $e->getMessage();
        }
    }

    // 4.3 Thêm 3 đơn hàng với các sản phẩm
    public function insertSampleOrders()
    {
        try {
            $orders = [
                [
                    'customer' => 'TechCorp',
                    'date' => '2025-05-16',
                    'items' => [
                        ['product_id' => 1, 'quantity' => 2, 'price' => 1500000],
                        ['product_id' => 2, 'quantity' => 5, 'price' => 500000]
                    ]
                ],
                [
                    'customer' => 'IndustryInc',
                    'date' => '2025-05-15',
                    'items' => [
                        ['product_id' => 3, 'quantity' => 1, 'price' => 2000000],
                        ['product_id' => 4, 'quantity' => 3, 'price' => 800000],
                        ['product_id' => 1, 'quantity' => 1, 'price' => 1500000]
                    ]
                ],
                [
                    'customer' => 'AutoTech',
                    'date' => '2025-05-14',
                    'items' => [
                        ['product_id' => 5, 'quantity' => 2, 'price' => 2500000],
                        ['product_id' => 2, 'quantity' => 4, 'price' => 500000]
                    ]
                ]
            ];

            foreach ($orders as $order) {
                // Thêm đơn hàng
                $stmt = $this->conn->prepare("
                    INSERT INTO orders (order_date, customer_name) 
                    VALUES (:date, :customer)
                ");
                $stmt->execute([
                    ':date' => $order['date'],
                    ':customer' => $order['customer']
                ]);
                $orderId = $this->conn->lastInsertId();

                // Thêm các sản phẩm trong đơn hàng
                $itemStmt = $this->conn->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price_at_order_time)
                    VALUES (:order_id, :product_id, :quantity, :price)
                ");

                foreach ($order['items'] as $item) {
                    $itemStmt->execute([
                        ':order_id' => $orderId,
                        ':product_id' => $item['product_id'],
                        ':quantity' => $item['quantity'],
                        ':price' => $item['price']
                    ]);
                }
            }
            echo "Thêm đơn hàng thành công!\n";
        } catch (PDOException $e) {
            echo "Thêm đơn hàng thất bại: " . $e->getMessage();
        }
    }

    // 4.4 Thêm sản phẩm mới bằng prepared statement
    public function addProduct($name, $price, $quantity)
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO products (product_name, unit_price, stock_quantity)
                VALUES (:name, :price, :quantity)
            ");
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':quantity' => $quantity
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Thêm sản phẩm thất bại: " . $e->getMessage();
            return false;
        }
    }

    // 4.5 Lấy tất cả sản phẩm
    public function getAllProducts()
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM products");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lấy danh sách sản phẩm thất bại: " . $e->getMessage();
            return [];
        }
    }

    // 4.6 Lọc sản phẩm có giá > 1,000,000 VNĐ
    public function getExpensiveProducts()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE unit_price > :price");
            $stmt->execute([':price' => 1000000]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lọc sản phẩm thất bại: " . $e->getMessage();
            return [];
        }
    }

    // 4.7 Sắp xếp sản phẩm theo giá giảm dần
    public function getProductsByPriceDesc()
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM products ORDER BY unit_price DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Sắp xếp sản phẩm thất bại: " . $e->getMessage();
            return [];
        }
    }

    // 4.8 Xóa sản phẩm theo ID
    public function deleteProduct($id)
    {
        try {
            // Kiểm tra xem sản phẩm có được sử dụng trong order_items không
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM order_items WHERE product_id = :id");
            $checkStmt->execute([':id' => $id]);
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                // Xóa các bản ghi liên quan trong order_items trước
                $deleteItemsStmt = $this->conn->prepare("DELETE FROM order_items WHERE product_id = :id");
                $deleteItemsStmt->execute([':id' => $id]);
                echo "Đã xóa {$deleteItemsStmt->rowCount()} mục đơn hàng liên quan đến sản phẩm ID=$id\n";
            }

            // Xóa sản phẩm
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $rowsAffected = $stmt->rowCount();

            if ($rowsAffected > 0) {
                echo "Xóa sản phẩm ID=$id thành công\n";
            } else {
                echo "Không tìm thấy sản phẩm ID=$id\n";
            }
            return $rowsAffected;
        } catch (PDOException $e) {
            echo "Xóa sản phẩm thất bại: " . $e->getMessage() . "\n";
            return 0;
        }
    }

    // 4.9 Cập nhật giá và số lượng tồn kho
    public function updateProduct($id, $price, $quantity)
    {
        try {
            $stmt = $this->conn->prepare("
                UPDATE products 
                SET unit_price = :price, stock_quantity = :quantity 
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':price' => $price,
                ':quantity' => $quantity
            ]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Cập nhật sản phẩm thất bại: " . $e->getMessage();
            return 0;
        }
    }

    // 4.10 Lấy 5 sản phẩm mới nhất
    public function getNewestProducts()
    {
        try {
            $stmt = $this->conn->query("
                SELECT * FROM products 
                ORDER BY created_at DESC 
                LIMIT 5
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lấy sản phẩm mới nhất thất bại: " . $e->getMessage();
            return [];
        }
    }
}
