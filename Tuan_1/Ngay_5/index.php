<!-- Tương tác đối tượng thời gian và file -->

<?php

# 1 . Date and time(Ngày giờ)
// Dùng để hiển thị thời gian hiện tại, tính toán số ngày, lưu thời điểm hiện tại (timestamp), hẹn giờ và lập lịch
// VD:
echo date("dd-mm-yyy H : i :s") . "<br>"; //lấy ngày giời hiện tại

# 2. Include / Require
// Dùng để nhúng nội dung của các file PHP khác vào file hiện tại
// Giúp tái sử dụng code, chia nhỏ chương trình

// VD:
require 'file.php';
include 'file.php';

require_once 'file.php';
include_once 'file.php';

# 3. File Handling (Xử lý tập tin)

// a. File Open / Read (Mở & đọc file)
// Dùng để mở và đọc lội dung file văn bản hoặc dữ liệu khác trong PHP
// VD:
$fp = fopen("text.txt", "r");
$content = fread($fp, filesize("text.txt"));
fclose($fp);
echo ($content);

// b. File Create / Write (Tạo và ghi file)
// Cho phép người dùng tạo mới file hoặc ghi nội dung vào file
// Có thể ghi đè hoặc ghi thêm

// VD: Ví dụ về ghi đè
$fp = fopen("text.txt", "w");
fwrite($fp, "Tôi năm nay 20 tuổi " . date("d/m/Y H:i:s"));
fclose($fp);
?>
<!-- # 4. File Upload
// Cho phép người dùng tải file từ máy tính lên máy chủ thông qua form -->

<!-- VD: -->

<!-- Form thêm file -->
<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="upload" id="">
    <button type="submit" name="submit">add</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $file = $_FILES['upload'];
    var_dump($file);
}
