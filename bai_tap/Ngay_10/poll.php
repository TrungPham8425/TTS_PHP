<?php
require_once 'db.php';

// Thiết lập header trả về JSON
header('Content-Type: application/json; charset=UTF-8');

// Lấy kết nối CSDL
$conn = getDbConnection();

// Xử lý yêu cầu POST để cập nhật bình chọn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy lựa chọn từ dữ liệu POST
    $option = $_POST['option'];

    // Cập nhật số phiếu bình chọn trong CSDL
    $stmt = $conn->prepare("UPDATE poll_results SET votes = votes + 1 WHERE option = ?");
    $stmt->bind_param("s", $option);
    $stmt->execute();
    $stmt->close();

    // Lấy tất cả kết quả bình chọn
    $result = $conn->query("SELECT option, votes FROM poll_results");
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
}

// Đóng kết nối CSDL
$conn->close();
