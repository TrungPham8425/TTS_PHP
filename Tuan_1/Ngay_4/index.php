<?php

// Global Variables + Superglobals, Forms và Regex

# 1. Biến toàn cục Global Variables

// Biến toàn cục được khai báo ngoài tất cả các hàm
// Biến toàn cục không thể truy cập vào bên trong nếu không dùng từ khóa global
// VD:
$name = "Nguyễn Văn A"; // khai báo biến toàn cục
function yourName()
{
    global $name;
    echo "Tên của bạn là: $name <br>";
}

// Gọi hàm
yourName();

# 2. Siêu biến toàn cục được PHP định nghĩa sẵn (Superglobals)

// Các biến này có thể truy cập được ở mọi nơi trong code kể cả trong lớp(class), hàm(function)...
// Các Superglobals thường sử dụng 
// - $_POST: Dùng để lấy dữ liệu từ url
// - $_POST: Dùng để lấy dữ liệu từ form được gửi bằng POST
// - $_REQUEST: Là gộp của GET, POST và COOKIE
// - $_SESSION: Dùng để quản lý các phiên người dùng
// - $_COOKIE: Dùng để quản lý cookie
// - $_SERVER: Dùng để chỉ mục thông tin máy chủ
// - $_FILES: Dùng để quản lý file upload
// - $_ENV: Dùng để lưu và truy xuất biến môi trường
// - $_GLOBAL: Dùng để truy cập hoặc thay đổi biến toàn cục bên trong hàm mà không cần khai báo

# 3. Forms và Regex 

// - Form
// Form là dùng để gửi dữ liệu của người dùng lên server
// Cách dể PHP lấy dữ liệu từ form 
// + Dùng $_GET: $_GET['tên_ô_input'] (method của form phải là GET)
// + Dùng $_POST: $_POST['tên_ô_input'] (method của form phải là POST)
// -Regex 
// Regex là chuỗi ký tự dùng để kiềm tra, tìm kiếm, hoặc thay thế văn bản theo quy tắc cụ thể

// VD: kiểm tra email có đúng định dạng
$email = "phutrung1606a@gmail.com";
if (preg_match("/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,}$/", $email)) {
    echo "Email đã đúng định dạng";
} else {
    echo "Email không đúng định dạng vui lòng sửa lại";
}
