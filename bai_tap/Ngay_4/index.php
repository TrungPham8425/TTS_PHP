<?php
// Khởi động phiên làm việc để lưu trữ giao dịch
session_start();

// Khởi tạo biến phiên nếu chưa được thiết lập
if (!isset($_SESSION['giao_dich'])) {
    $_SESSION['giao_dich'] = [];
}
if (!isset($_SESSION['tong_thu'])) {
    $_SESSION['tong_thu'] = 0;
}
if (!isset($_SESSION['tong_chi'])) {
    $_SESSION['tong_chi'] = 0;
}

// Khởi tạo biến toàn cục
$errors = []; // Lưu trữ lỗi xác thực
$warnings = []; // Lưu trữ cảnh báo cho từ khóa nhạy cảm
$success_message = ''; // Thông báo thành công cho dữ liệu hợp lệ

// Xử lý xóa toàn bộ giao dịch
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all'])) {
    $_SESSION['giao_dich'] = [];
    $_SESSION['tong_thu'] = 0;
    $_SESSION['tong_chi'] = 0;
    $success_message = "Đã xóa toàn bộ giao dịch!";
}

// Xử lý dữ liệu biểu mẫu giao dịch sử dụng $_POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Làm sạch và lấy dữ liệu từ biểu mẫu sử dụng $_POST
    $transactionName = trim($_POST['transaction_name'] ?? '');
    $amount = $_POST['amount'] ?? '';
    $transactionType = $_POST['transaction_type'] ?? '';
    $note = trim($_POST['note'] ?? '');
    $date = trim($_POST['date'] ?? '');

    // Kiểm tra tên giao dịch: chỉ chứa chữ cái tiếng Việt, số và khoảng trắng
    if (empty($transactionName) || !preg_match('/^[\p{L}0-9\s]+$/u', $transactionName)) {
        $errors['transaction_name'] = "Tên giao dịch không được để trống và chỉ chứa chữ tiếng Việt, số, khoảng trắng.";
    }

    // Kiểm tra số tiền: phải là số dương
    if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $amount) || $amount <= 0) {
        $errors['amount'] = "Số tiền phải là số dương.";
    }

    // Kiểm tra định dạng ngày: dd/mm/yyyy
    if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
        $errors['date'] = "Ngày phải có định dạng dd/mm/yyyy.";
    } else {
        $d = DateTime::createFromFormat('d/m/Y', $date);
        if (!$d || $d->format('d/m/Y') !== $date) {
            $errors['date'] = "Ngày không hợp lệ.";
        }
    }

    // Kiểm tra loại giao dịch
    if (!in_array($transactionType, ['thu', 'chi'])) {
        $errors['transaction_type'] = "Phải chọn loại giao dịch (thu hoặc chi).";
    }

    // Kiểm tra từ khóa nhạy cảm trong ghi chú
    if (preg_match('/nợ xấu|vay nóng|cho vay nặng lãi/i', $note)) {
        $warnings[] = "Ghi chú chứa từ khóa nhạy cảm.";
    }

    // Nếu không có lỗi, xử lý giao dịch
    if (empty($errors)) {
        $transaction = [
            'name' => $transactionName,
            'amount' => (float)$amount,
            'type' => $transactionType,
            'note' => $note,
            'date' => $date,
            'timestamp' => time() // Sử dụng $_SERVER['REQUEST_TIME'] gián tiếp
        ];

        // Lưu vào phiên
        $_SESSION['giao_dich'][] = $transaction;

        // Cập nhật tổng
        if ($transactionType === 'thu') {
            $_SESSION['tong_thu'] += $transaction['amount'];
        } else {
            $_SESSION['tong_chi'] += $transaction['amount'];
        }

        $success_message = "Giao dịch đã được ghi nhận thành công!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Giao Dịch Tài Chính</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Quản Lý Giao Dịch Tài Chính</h1>

        <!-- Hiển thị thông báo thành công -->
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Hiển thị cảnh báo -->
        <?php if (!empty($warnings)): ?>
            <div class="alert alert-warning">
                <?php foreach ($warnings as $w): ?>
                    <p><?php echo htmlspecialchars($w); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Biểu mẫu giao dịch -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="transaction_name">Tên giao dịch:</label>
                <input type="text" name="transaction_name" id="transaction_name"
                    class="form-control <?php echo isset($errors['transaction_name']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo isset($_POST['transaction_name']) ? htmlspecialchars(string: $_POST['transaction_name']) : ''; ?>">
                <?php if (isset($errors['transaction_name'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['transaction_name']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="amount">Số tiền:</label>
                <input type="number" step="0.01" name="amount" id="amount"
                    class="form-control <?php echo isset($errors['amount']) ? 'is-invalid' : ''; ?>"
                    value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : ''; ?>">
                <?php if (isset($errors['amount'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['amount']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Loại giao dịch:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input <?php echo isset($errors['transaction_type']) ? 'is-invalid' : ''; ?>"
                        type="radio" name="transaction_type" value="thu" id="thu"
                        <?php echo (isset($_POST['transaction_type']) && $_POST['transaction_type'] === 'thu') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="thu">Thu</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input <?php echo isset($errors['transaction_type']) ? 'is-invalid' : ''; ?>"
                        type="radio" name="transaction_type" value="chi" id="chi"
                        <?php echo (isset($_POST['transaction_type']) && $_POST['transaction_type'] === 'chi') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="chi">Chi</label>
                </div>
                <?php if (isset($errors['transaction_type'])): ?>
                    <div class="text-danger mt-1"><?php echo $errors['transaction_type']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="note">Ghi chú:</label>
                <input type="text" name="note" id="note" class="form-control"
                    value="<?php echo isset($_POST['note']) ? htmlspecialchars($_POST['note']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="date">Ngày thực hiện:</label>
                <input type="text" name="date" id="date"
                    class="form-control <?php echo isset($errors['date']) ? 'is-invalid' : ''; ?>"
                    placeholder="dd/mm/yyyy"
                    value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
                <?php if (isset($errors['date'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['date']; ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Gửi</button>
        </form>

        <!-- Bảng giao dịch -->
        <?php if (!empty($_SESSION['giao_dich'])): ?>
            <h2 class="mt-5">Danh Sách Giao Dịch</h2>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên giao dịch</th>
                        <th>Số tiền</th>
                        <th>Loại</th>
                        <th>Ghi chú</th>
                        <th>Ngày</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['giao_dich'] as $index => $gd): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($gd['name']); ?></td>
                            <td><?php echo number_format($gd['amount'], 2); ?></td>
                            <td><?php echo $gd['type'] === 'thu' ? 'Thu' : 'Chi'; ?></td>
                            <td><?php echo htmlspecialchars($gd['note']); ?></td>
                            <td><?php echo htmlspecialchars($gd['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Tóm tắt -->
            <div class="alert alert-info mt-3">
                <p><strong>Tổng thu:</strong> <?php echo number_format($_SESSION['tong_thu'], 2); ?> VND</p>
                <p><strong>Tổng chi:</strong> <?php echo number_format($_SESSION['tong_chi'], 2); ?> VND</p>
                <p><strong>Số dư:</strong> <?php echo number_format($_SESSION['tong_thu'] - $_SESSION['tong_chi'], 2); ?> VND</p>
                <p><strong>Tỷ giá (VND/USD):</strong> <?php echo number_format($GLOBALS['ty_gia'], 2); ?></p>
            </div>

            <!-- Nút xóa toàn bộ giao dịch -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giao dịch?');">
                <button type="submit" name="delete_all" class="btn btn-danger mt-3">Xóa Toàn Bộ Giao Dịch</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>