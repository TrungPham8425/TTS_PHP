<?php
// File chính để chạy các thao tác mẫu
require_once 'db.php';
require_once 'operations.php';

// Kiểm tra và tạo cơ sở dữ liệu trước
require_once 'schema.php';

// Khởi tạo kết nối và đối tượng TechFactory
$db = new Database();
$conn = $db->connect();

if ($conn === null) {
    die("Không thể tiếp tục vì kết nối cơ sở dữ liệu thất bại.");
}

$techFactory = new TechFactory($db);

// Thêm dữ liệu mẫu
echo " <h3>Thêm sản phẩm mẫu <h3>";
$techFactory->insertSampleProducts();

echo "<h3> Thêm đơn hàng mẫu </h3>";
$techFactory->insertSampleOrders();

// 4.4 Thêm sản phẩm mới bằng prepared statement
echo "<h3> Thêm sản phẩm mới </h3>";
$newProductId = $techFactory->addProduct('Cảm biến ánh sáng', 600000, 120);
echo "ID sản phẩm mới: $newProductId<br>";

// 4.5 Hiển thị tất cả sản phẩm
echo "<h3> Danh sách sản phẩm </h3>";
$products = $techFactory->getAllProducts();
foreach ($products as $product) {
    echo "ID: {$product['id']}, Tên: {$product['product_name']}, Giá: {$product['unit_price']}, Tồn kho: {$product['stock_quantity']}<br>";
}

// 4.6 Lọc sản phẩm giá > 1,000,000
echo "<h3> Sản phẩm giá > 1,000,000 </h3>";
$expensiveProducts = $techFactory->getExpensiveProducts();
foreach ($expensiveProducts as $product) {
    echo "ID: {$product['id']}, Tên: {$product['product_name']}, Giá: {$product['unit_price']}<br>";
}

// 4.7 Sắp xếp sản phẩm theo giá giảm dần
echo "<h3> Sản phẩm sắp xếp theo giá giảm dần </h3>";
$productsByPrice = $techFactory->getProductsByPriceDesc();
foreach ($productsByPrice as $product) {
    echo "ID: {$product['id']}, Tên: {$product['product_name']}, Giá: {$product['unit_price']}<br>";
}

// 4.8 Xóa sản phẩm
echo "<h3> Xóa sản phẩm ID=1 </h3>";
$rowsAffected = $techFactory->deleteProduct(1);
echo "Số dòng bị xóa: $rowsAffected<br>";

// 4.9 Cập nhật sản phẩm
echo "<h3> Cập nhật sản phẩm ID=2 </h3>";
$rowsAffected = $techFactory->updateProduct(2, 550000, 180);
echo "Số dòng được cập nhật: $rowsAffected<br>";

// 4.10 Lấy 5 sản phẩm mới nhất
echo "<h3> 5 sản phẩm mới nhất </h3>";
$newestProducts = $techFactory->getNewestProducts();
foreach ($newestProducts as $product) {
    echo "ID: {$product['id']}, Tên: {$product['product_name']}, Ngày tạo: {$product['created_at']}<br>";
}
