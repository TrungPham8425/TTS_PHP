<?php
// Thiết lập header trả về HTML
header('Content-Type: text/html; charset=UTF-8');

// Lấy danh mục từ query string
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Đọc file brands.xml
$xml = simplexml_load_file('brands.xml');
if ($xml === false) {
    die("Không thể đọc file brands.xml");
}

// Tạo danh sách HTML cho dropdown thương hiệu
echo '<option value="">Chọn thương hiệu</option>';
// Duyệt qua các thương hiệu trong XML
foreach ($xml->brand as $brand) {
    // Lọc thương hiệu theo danh mục
    if ((string)$brand['category'] === $category) {
        // Tạo option với Bootstrap class
        echo '<option value="' . htmlspecialchars($brand) . '">' . htmlspecialchars($brand) . '</option>';
    }
}
