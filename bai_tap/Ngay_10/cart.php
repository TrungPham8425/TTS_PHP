<?php
session_start();

// Thiết lập header trả về JSON
header('Content-Type: application/json; charset=UTF-8');

// Kiểm tra và khởi tạo giỏ hàng nếu chưa tồn tại
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý yêu cầu POST để thêm sản phẩm vào giỏ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy ID sản phẩm từ dữ liệu POST
    $product_id = intval($_POST['product_id']);

    // Kiểm tra và thêm sản phẩm vào giỏ nếu chưa có
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }

    // Trả về kết quả dạng JSON
    echo json_encode([
        'success' => true,
        'cartCount' => count($_SESSION['cart'])
    ]);
}
