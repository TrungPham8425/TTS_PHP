<?php
// khoi chay session
session_start();

// kiem tra nguoi dung da dang nhap chua
if (!isset($_SESSION['loggedin'])) {
    header('Localtion: index.php');
    exit();
}

include 'includes/logger.php';
$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');
log_action('Xem lịch sử hoạt động.', $ip, $timestamp, 'login');
// Lay noi dung log 
$log_content = '';
// Ngay tim kiem
$selected_date = '';
// tu khoa tin kiem
$keyword = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_date'])) {
    $selected_date = $_POST['log_date'];
    $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
    $log_file = "logs/log_$selected_date.txt";

    if (file_exists($log_file)) {
        $file = fopen($log_file, 'r');
        while (!feof($file)) {
            $line = fgets($file);
            if ($line && (empty($keyword) || stripos($line, $keyword) !== false)) {
                // Phân màu theo loại hành động
                if (stripos($line, 'Type: login') !== false) {
                    $log_content .= "<div style='background-color:#d4edda; color:#155724; padding:8px; margin-bottom:5px; border-radius:5px;'>$line</div>";
                } elseif (stripos($line, 'Type: logout') !== false) {
                    $log_content .= "<div style='background-color:#f8d7da; color:#721c24; padding:8px; margin-bottom:5px; border-radius:5px;'>$line</div>";
                } elseif (stripos($line, 'Type: upload') !== false) {
                    $log_content .= "<div style='background-color:#e2d6f3; color:#4b0082; padding:8px; margin-bottom:5px; border-radius:5px;'>$line</div>";
                } else {
                    $log_content .= "<div style='background-color:#f1f1f1; color:#333; padding:8px; margin-bottom:5px; border-radius:5px;'>$line</div>";
                }
            }
        }
        fclose($file);
        if (empty($log_content)) {
            $log_content = "<div class='alert alert-warning'>Không tìm thấy kết quả phù hợp với từ khóa.</div>";
        }
    } else {
        $log_content = "<div class='alert alert-warning'>Không có nhật ký cho ngày này.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem nhật ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .text-purple {
            color: purple;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Xem nhật ký hệ thống</h1>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="log_date" class="form-label">Chọn ngày</label>
                    <input type="date" class="form-control" id="log_date" name="log_date" value="<?php echo $selected_date; ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="keyword" class="form-label">Tìm kiếm từ khóa</label>
                    <input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Xem nhật ký</button>
                </div>
            </div>
        </form>
        <div>
            <?php echo $log_content; ?>
        </div>
        <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>