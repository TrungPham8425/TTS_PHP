<?php
// Kết nối cơ sở dữ liệu sử dụng PDO
class Database
{
    // Thông tin kết nối
    private $host = 'localhost';
    private $dbname = 'tech_factory';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Hàm kết nối tới MySQL
    public function connect()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                $this->username,
                $this->password
            );
            // Thiết lập chế độ báo lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Kết nối thất bại: " . $e->getMessage();
            return null;
        }
    }
}
