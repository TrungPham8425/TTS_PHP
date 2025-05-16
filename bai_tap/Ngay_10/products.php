<?php
require_once 'db.php';

// Thiết lập header mặc định trả về JSON
header('Content-Type: application/json; charset=UTF-8');

// Lấy kết nối CSDL
$conn = getDbConnection();

// Kiểm tra nếu có tham số id trong query string
if (isset($_GET['id'])) {
    // Lấy chi tiết sản phẩm theo ID
    $id = intval($_GET['id']);
    // Sử dụng prepared statement để truy vấn an toàn
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Thiết lập header trả về HTML cho chi tiết sản phẩm
    header('Content-Type: text/html; charset=UTF-8');
    // Tạo HTML với Bootstrap cho giao diện chi tiết sản phẩm
    echo '<h3 class="card-title">' . htmlspecialchars($product['name']) . '</h3>';
    echo '<p class="card-text">Mô tả: ' . htmlspecialchars($product['description']) . '</p>';
    echo '<p class="card-text">Giá: ' . number_format($product['price'], 0, ',', '.') . ' VND</p>';
    echo '<p class="card-text">Tồn kho: ' . $product['stock'] . '</p>';
    $stmt->close();
} else {
    // Lấy danh sách tất cả sản phẩm
    $result = $conn->query("SELECT id, name FROM products");
    $products = [];
    // Lưu kết quả vào mảng
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    // Trả về danh sách sản phẩm dạng JSON
    echo json_encode($products);
}

// Đóng kết nối CSDL
$conn->close();
