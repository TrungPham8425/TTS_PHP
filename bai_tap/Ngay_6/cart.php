<?php
// cart.php
require_once 'config.php';
require_once 'exceptions.php';

// Khởi tạo giỏ hàng trong session
function initCart()
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Thêm sản phẩm vào giỏ hàng
function addToCart($book, $quantity)
{
    global $MAX_QUANTITY;

    // Kiểm tra số lượng vượt quá giới hạn
    if ($quantity > $MAX_QUANTITY) {
        throw new CartException("Số lượng sản phẩm trong giỏ hàng vượt quá số lượng cho phép: $MAX_QUANTITY");
    }

    // Tạo mục giỏ hàng
    $cartItem = [
        'title' => $book['title'],
        'price' => $book['price'],
        'quantity' => $quantity
    ];

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['title'] === $cartItem['title']) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // Nếu chưa có, thêm sản phẩm mới
    if (!$found) {
        $_SESSION['cart'][] = $cartItem;
    }
}

// Lưu giỏ hàng vào file JSON
function saveCart($email, $phone, $address)
{
    global $JSON_FILE;
    $cartData = [
        'customer_email' => $email,
        'customer_phone' => $phone,
        'customer_address' => $address,
        'products' => $_SESSION['cart'],
        'total_amount' => array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $_SESSION['cart'])),
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Mã hóa JSON và kiểm tra lỗi
    $jsonData = json_encode($cartData, JSON_PRETTY_PRINT);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new CartException("Lỗi mã hóa JSON: " . json_last_error_msg());
    }

    // Ghi file JSON
    if (file_put_contents($JSON_FILE, $jsonData) === false) {
        throw new CartException("Lỗi khi lưu dữ liệu vào $JSON_FILE");
    }
}

// Xóa giỏ hàng
function clearCart()
{
    global $JSON_FILE;
    $_SESSION['cart'] = [];
    if (file_exists($JSON_FILE)) {
        unlink($JSON_FILE);
    }
}
