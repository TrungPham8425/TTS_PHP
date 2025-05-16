<?php
// Tắt hiển thị lỗi PHP để tránh làm hỏng JSON
error_reporting(0);
ini_set('display_errors', 0);

// Yêu cầu file db.php để sử dụng kết nối
require_once 'db.php';

// Thiết lập header trả về JSON
header('Content-Type: application/json; charset=UTF-8');

// Xử lý yêu cầu POST để cập nhật bình chọn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem dữ liệu 'option' có được gửi không
    if (!isset($_POST['option']) || empty($_POST['option'])) {
        echo json_encode(['error' => 'Không có lựa chọn được gửi']);
        exit;
    }

    // Lấy lựa chọn từ dữ liệu POST
    $option = $_POST['option'];

    // Lấy kết nối CSDL
    $conn = getDbConnection();

    // Kiểm tra tính hợp lệ của option
    $valid_options = ['interface', 'speed', 'service'];
    if (!in_array($option, $valid_options)) {
        echo json_encode(['error' => 'Lựa chọn không hợp lệ']);
        $conn->close();
        exit;
    }

    // Cập nhật số phiếu bình chọn trong CSDL
    $stmt = $conn->prepare("UPDATE poll_results SET votes = votes + 1 WHERE `option` = ?");
    if (!$stmt) {
        echo json_encode(['error' => 'Lỗi chuẩn bị truy vấn: ' . $conn->error]);
        $conn->close();
        exit;
    }
    $stmt->bind_param("s", $option);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Lỗi thực thi truy vấn: ' . $stmt->error]);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Lấy tất cả kết quả bình chọn
    $result = $conn->query("SELECT `option`, votes FROM poll_results");
    if (!$result) {
        echo json_encode(['error' => 'Lỗi lấy kết quả bình chọn: ' . $conn->error]);
        $conn->close();
        exit;
    }
    $totalVotes = 0;
    $percentages = [];

    // Tính tổng số phiếu
    while ($row = $result->fetch_assoc()) {
        $totalVotes += $row['votes'];
        $percentages[$row['option']] = $row['votes'];
    }

    // Tính phần trăm cho từng lựa chọn
    foreach ($percentages as $key => $votes) {
        $percentages[$key] = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 2) : 0;
    }

    // Trả về kết quả dạng JSON
    echo json_encode(['percentages' => $percentages]);

    // Đóng kết nối CSDL
    $conn->close();
} else {
    // Trả về lỗi nếu không phải yêu cầu POST
    echo json_encode(['error' => 'Yêu cầu không hợp lệ']);
}
