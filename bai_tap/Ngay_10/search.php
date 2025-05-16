<?php
require_once 'db.php';

// Thiết lập header trả về HTML
header('Content-Type: text/html; charset=UTF-8');

// Lấy kết nối CSDL
$conn = getDbConnection();

// Lấy từ khóa tìm kiếm từ query string
$query = isset($_GET['query']) ? '%' . $_GET['query'] . '%' : '%';

// Chuẩn bị câu truy vấn tìm kiếm với LIKE
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

// Tạo HTML hiển thị kết quả tìm kiếm với Bootstrap
echo '<div class="list-group">';
if ($result->num_rows === 0) {
    // Hiển thị thông báo nếu không tìm thấy sản phẩm
    echo '<div class="list-group-item">Không tìm thấy sản phẩm.</div>';
} else {
    // Duyệt qua các sản phẩm tìm được
    while ($product = $result->fetch_assoc()) {
        echo '<div class="list-group-item">';
        echo '<h5 class="mb-1">' . htmlspecialchars($product['name']) . '</h5>';
        echo '<p class="mb-1">Giá: ' . number_format($product['price'], 0, ',', '.') . ' VND</p>';
        echo '</div>';
    }
}
echo '</div>';

// Đóng statement và kết nối
$stmt->close();
$conn->close();
