<?php
// Hàm tạo kết nối tới cơ sở dữ liệu MySQL
function getDbConnection()
{
    // Thông tin kết nối CSDL
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'ecommerce';

    // Tạo kết nối với MySQL
    $conn = new mysqli($host, $username, $password, $database);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Thiết lập mã hóa UTF-8 để hỗ trợ tiếng Việt
    $conn->set_charset("utf8mb4");

    // Trả về đối tượng kết nối
    return $conn;
}
