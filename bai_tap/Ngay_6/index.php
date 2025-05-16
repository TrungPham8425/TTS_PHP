<?php
// index.php
session_start();
require_once 'config.php';
require_once 'exceptions.php';
require_once 'cart.php';

// Khởi tạo giỏ hàng
initCart();

// Hàm xác thực và lọc dữ liệu đầu vào
function validate($data, $books)
{

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        throw new CartException("Email không hợp lệ");
    }

    // Tách phần domain sau dấu @
    $parts = explode('@', $email);
    $domain = $parts[1]; // lấy phần sau dấu @

    if ($domain !== 'gmail.com') {
        throw new CartException("Chỉ chấp nhận email @gmail.com");
    }

    // Nếu đến đây thì email hợp lệ và có đuôi là gmail.com

    // Lọc và xác thực số điện thoại
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $phone = filter_var($phone, FILTER_VALIDATE_REGEXP, [
        'options' => ['regexp' => '/^[0-9]{10,11}$/']
    ]);
    if (!$phone) {
        throw new CartException("Số điện thoại không hợp lệ (phải có 10-11 số)");
    }

    // Lọc địa chỉ
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
    if (!$address) {
        throw new CartException("Địa chỉ không được để trống");
    }

    // Lọc chỉ số sách
    $bookIndex = filter_input(INPUT_POST, 'book', FILTER_VALIDATE_INT);
    if ($bookIndex === false || !isset($books[$bookIndex])) {
        throw new CartException("Lựa chọn sách không hợp lệ");
    }

    // Lọc số lượng
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    if ($quantity === false || $quantity <= 0) {
        throw new CartException("Số lượng không hợp lệ");
    }

    // tra ve mang chua gia tri
    return  [
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'bookIndex' => $bookIndex,
        'quantity' => $quantity,
    ];
}
// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'add_to_cart') {
            // Xác thực dữ liệu
            $validatedData = validate($_POST, $books);

            // Thêm vào giỏ hàng
            addToCart($books[$validatedData['bookIndex']], $validatedData['quantity']);

            // Lưu cookie email
            global $COOKIE_EXPIRE_DAYS;
            setcookie('user_email', $validatedData['email'], time() + ($COOKIE_EXPIRE_DAYS * 24 * 60 * 60), "/");

            // Lưu giỏ hàng vào JSON
            saveCart($validatedData['email'], $validatedData['phone'], $validatedData['address']);

            $successMessage = "Đã thêm sách vào giỏ hàng thành công!";
        } elseif ($_POST['action'] === 'clear_cart') {
            clearCart();
            $successMessage = "Đã xóa giỏ hàng!";
        }
    } catch (CartException $e) {
        $errorMessage = $e->getMessage();
    } catch (Exception $e) {
        $errorMessage = "Đã xảy ra lỗi không mong muốn";
        global $LOG_FILE;
        file_put_contents($LOG_FILE, date('Y-m-d H:i:s') . " - Unexpected Error: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}
// Lấy email từ cookie
$storedEmail = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : '';

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $APP_NAME; ?> - Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4"><?php echo $APP_NAME; ?> - Giỏ hàng</h1>

        <!-- Hiển thị thông báo -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <!-- Form thêm sách vào giỏ hàng -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="add_to_cart">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="book" class="form-label">Chọn sách</label>
                <select class="form-select" id="book" name="book" required>
                    <?php foreach ($books as $index => $book): ?>
                        <option value="<?php echo $index; ?>">
                            <?php echo htmlspecialchars($book['title']) . " - " . number_format($book['price']) . " $CURRENCY"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Số lượng</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
        </form>

        <!-- Hiển thị giỏ hàng -->
        <?php if (!empty($_SESSION['cart'])): ?>
            <h2>Nội dung giỏ hàng</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên sách</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grandTotal = 0;
                    foreach ($_SESSION['cart'] as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $grandTotal += $itemTotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo number_format($item['price']); ?> <?php echo $CURRENCY; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($itemTotal); ?> <?php echo $CURRENCY; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td><strong><?php echo number_format($grandTotal); ?> <?php echo $CURRENCY; ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <form method="POST">
                <input type="hidden" name="action" value="clear_cart">
                <button type="submit" class="btn btn-danger">Xóa giỏ hàng</button>
            </form>
        <?php endif; ?>

        <!-- Hiển thị xác nhận đơn hàng -->
        <?php
        if (file_exists($JSON_FILE)) {
            $jsonContent = file_get_contents($JSON_FILE);
            $order = json_decode($jsonContent, true);

            if (is_array($order) && isset($order['products']) && isset($order['total_amount'])):
        ?>
                <h2 class="mt-5">Xác nhận đơn hàng</h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
                <p><strong>Thời gian đặt hàng:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
                <h3>Sản phẩm đã đặt</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên sách</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['products'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo number_format($item['price']); ?> <?php echo $CURRENCY; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'] * $item['quantity']); ?> <?php echo $CURRENCY; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td><strong><?php echo number_format($order['total_amount']); ?> <?php echo $CURRENCY; ?></strong></td>
                        </tr>
                    </tbody>
                </table>
        <?php
            endif;
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>