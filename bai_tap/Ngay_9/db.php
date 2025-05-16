<?php
// db.php - Quản lý kết nối cơ sở dữ liệu và khởi tạo schema

class Db
{
    // Thông tin xác thực cơ sở dữ liệu
    private $host = 'localhost';
    private $dbname = 'tech_factory';
    private $username = 'root';
    private $password = ''; // Cập nhật mật khẩu nếu cần
    private $conn;

    /**
     * Thiết lập kết nối PDO tới MySQL
     * @return PDO|null Trả về đối tượng PDO hoặc null nếu kết nối thất bại
     */
    public function connectDB()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_PERSISTENT, true);
            echo "Kết nối tới cơ sở dữ liệu {$this->dbname} thành công<br>";
            return $this->conn;
        } catch (PDOException $error) {
            echo "Kết nối thất bại: " . $error->getMessage() . "<br>";
            return null;
        }
    }

    /**
     * Khởi tạo cơ sở dữ liệu và tạo các bảng cần thiết
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function initializeDatabase()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Kết nối tới MySQL thành công<br>";

            $this->conn->exec("CREATE DATABASE IF NOT EXISTS tech_factory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "Tạo cơ sở dữ liệu tech_factory thành công<br>";

            $this->conn->exec("USE tech_factory");
            echo "Chọn cơ sở dữ liệu tech_factory thành công<br>";

            $this->conn->exec("
                CREATE TABLE IF NOT EXISTS products (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    product_name VARCHAR(100) NOT NULL,
                    unit_price DECIMAL(10,2) NOT NULL,
                    stock_quantity INT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "Tạo bảng products thành công<br>";

            $this->conn->exec("
                CREATE TABLE IF NOT EXISTS orders (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    order_date DATE NOT NULL,
                    customer_name VARCHAR(100) NOT NULL,
                    note TEXT
                )
            ");
            echo "Tạo bảng orders thành công<br>";

            $this->conn->exec("
                CREATE TABLE IF NOT EXISTS order_items (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    order_id INT,
                    product_id INT,
                    quantity INT NOT NULL,
                    price_at_order_time DECIMAL(10,2) NOT NULL,
                    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
                )
            ");
            echo "Tạo bảng order_items thành công<br>";

            echo "Khởi tạo cơ sở dữ liệu và các bảng thành công<br>";
            return true;
        } catch (PDOException $e) {
            echo "Khởi tạo cơ sở dữ liệu thất bại: " . $e->getMessage() . "<br>";
            return false;
        }
    }
}
