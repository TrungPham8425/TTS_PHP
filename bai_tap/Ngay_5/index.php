<?php
session_start();
// Nhúng các file
include 'includes/header.php';
include 'includes/logger.php';
include 'includes/upload.php';

// Xử lý code
// Xử lý đăng nhập 

// Kiểm tra người dùng đã ấn nút submit chưa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // echo 123;
    // Lấy thông tin người dùng sau khi đăng nhập
    $email = $_POST['email'];
    $password = $_POST['password'];
    $ip = $_SERVER['REMOTE_ADDR']; //Lấy ip người dùng
    $timestamp = date('Y-m-d H:i:s'); //thời gian hoạt động

    if ($email == 'admin@gmail.com' && $password == '12345') {
        $_SESSION['loggedin'] = true;
        // Ghi lai hành động
        log_action('Đăng nhập thành công.', $ip, $timestamp, 'login');
        header('Location: index.php'); //load lai trang
        exit;
    } else {
        // Ghi lai hành động
        log_action('Đăng nhập thất bại.', $ip, $timestamp, 'login');
        // Lưu lỗi
        $errors['login'] = 'Tài khoản hoặc mật khẩu sai vui lòng thử lại!';
    }
}
// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $timestamp = date('Y-m-d H:i:s');
    log_action('Đăng xuất', $ip, $timestamp, 'logout');
    session_destroy();
    header('Location: index.php');
    exit;
}
// Xử lý upload file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload']) && isset($_SESSION['loggedin'])) {
    // echo 123;
    // Lấy thông tin người dùng sau khi đăng nhập
    $ip = $_SERVER['REMOTE_ADDR']; //Lấy ip người dùng
    $timestamp = date('Y-m-d H:i:s'); //thời gian hoạt động
    // Kiểm tra có file được gửi lên không 
    if (!empty($_FILES['evidence']['name'])) {
        $upload_result = handle_upload($_FILES['evidence']);
        if ($upload_result['success']) {
            // lưu lại hành động
            log_action("Uploaded file: " . $upload_result['filename'], $ip, $timestamp, 'upload');
            $success = 'File đã được upload thành công.';
        } else {
            // lưu lại hành động 
            log_action("Upload failed: " . $upload_result['error'], $ip, $timestamp, 'upload');
            $error = $upload_result['error'];
        }
    }
}

?>

<!-- Giao diện  -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>
    <h1>Nhật ký hoạt động</h1>
    <!-- Nếu chưa đăng nhập xuất hiện form đăng nhập -->
    <?php if (!isset($_SESSION['loggedin'])): ?>
        <!-- Form đăng nhập -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Đăng nhập</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control w-50" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control w-50" id="password" name="password" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Đăng nhập</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Chào mừng, Admin</h5>
                <a href="index.php?logout=true" class="btn btn-danger">Đăng xuất</a>
                <a href="view_log.php" class="btn btn-info">Xem nhật ký</a>
            </div>
        </div>

        <!-- Form upload file -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Upload file minh chứng</h5>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="evidence" class="form-label">File minh chứng (PDF, JPG, PNG, max 2MB)</label>
                        <input type="file" class="form-control" id="evidence" name="evidence" accept=".pdf,.jpg,.png" required>
                    </div>
                    <button type="submit" name="upload" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>