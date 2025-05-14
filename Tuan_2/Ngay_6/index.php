<?php

# 1. cookie
// Cookies laf cách lưu trữ dữ liệu nhỏ như token, ID phía client
// VD:
// Thiết lập cookies 
setcookie("username", "nguyen_van_a", time() + 3600); //Thời hạn 1 giờ

// Dọc cookies
// Kiểm tra nếu có cookie hiển thị câu xin chào

if (isset($_COOKIE["username"])) {
    echo "Xin chào " . $_COOKIE["username"] . "<br>";
}

# 2. session 
// Sessions dùng để lưu thông tin tạm thời cho người dung, phía server 
// VD: 
// Khởi chạy phiên làm việc
session_start();

// Gán giá trị 
$_SESSION["ho_ten"] = "Pham Van C";

// Hiển thị session 
echo $_SESSION["ho_ten"] . "<br>";

// Hủy session
session_destroy();

# 3. Filters 
// Dùng để kiểm tra và lọc dữ liệu đầu vào, thường dùng để bảo về dữ liệu từ form hoặc URL
// VD: Kiểm tra email

// Tạo biến chứa email
$email = "phutrung1606a@gmail.com";

// kiểm tra email có hợp lệ không
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email: $email hợp lệ <br>";
} else {
    echo "Email: $email không hợp lệ <br>";
}


# 4. Filters Advanced
// Filter nâng cao dùng để kết hợp với tham số tùy chỉnh hoặc các kỹ thuật phức tạp hơn
// VD: Kiểm tra số nguyễn trong khoảng
$options = [
    "options" => [
        "min_range" => 1,
        "max_range" => 100
    ]
];
$value = 50;

if (filter_var($value, FILTER_VALIDATE_INT, $options)) {
    echo "Hợp lệ (trong khoảng)";
} else {
    echo "Không hợp lệ";
}

# 5. JSON
// Thường dùng để gữi giữ liệu giữa client server 
// VD: 

// Chuyển màng sang dạng Json
$data = ["name" => "John", "age" => 25];
$json = json_encode($data);
echo $json . "<br>";

// Chuyển Json sang dạng mảng
$json = '{"name":"John","age":25}';
$data = json_decode($json, true);
echo $data['name'] . "<br>";

# 6. Exceptions
// Dùng để xử lý lỗi khi có sự cố xảy ra mà muốn bắt lại để sử ký an toàn
