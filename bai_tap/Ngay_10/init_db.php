<?php
require_once 'db.php';

// Hàm khởi tạo cơ sở dữ liệu và bảng
function initializeDatabase()
{
    // Lấy kết nối CSDL
    $conn = getDbConnection();

    // Đọc nội dung file database.sql
    $sql = file_get_contents('database.sql');
    if ($sql === false) {
        die("Không thể đọc file database.sql");
    }

    // Thực thi các lệnh SQL để tạo bảng và thêm dữ liệu
    if ($conn->multi_query($sql)) {
        // Chờ xử lý hết các câu lệnh
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "Cơ sở dữ liệu đã được khởi tạo thành công.";
    } else {
        echo "Lỗi khi khởi tạo cơ sở dữ liệu: " . $conn->error;
    }

    // Đóng kết nối
    $conn->close();
}

// Gọi hàm để khởi tạo CSDL
initializeDatabase();
