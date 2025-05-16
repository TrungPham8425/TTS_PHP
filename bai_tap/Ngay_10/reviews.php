<?php
require_once 'db.php';

// Thiết lập header trả về HTML
header('Content-Type: text/html; charset=UTF-8');

// Lấy kết nối CSDL
$conn = getDbConnection();

// Lấy product_id từ query string
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id) {
    // Chuẩn bị câu truy vấn an toàn với prepared statement
    $stmt = $conn->prepare("SELECT user, comment, rating FROM reviews WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Lưu kết quả đánh giá vào mảng
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    // Tạo HTML hiển thị đánh giá với Bootstrap
    echo '<h3 class="card-title">Đánh giá</h3>';
    if (empty($reviews)) {
        // Hiển thị thông báo nếu không có đánh giá
        echo '<p class="text-muted">Chưa có đánh giá nào.</p>';
    } else {
        // Hiển thị danh sách đánh giá trong list-group
        echo '<ul class="list-group list-group-flush">';
        foreach ($reviews as $review) {
            echo '<li class="list-group-item">';
            echo '<strong>' . htmlspecialchars($review['user']) . '</strong> (Đánh giá: ' . $review['rating'] . '/5)<br>';
            echo '<p class="mb-0">' . htmlspecialchars($review['comment']) . '</p>';
            echo '</li>';
        }
        echo '</ul>';
    }

    // Đóng statement
    $stmt->close();
}

// Đóng kết nối CSDL
$conn->close();
