<?php
// Dữ liệu bảng người dùng
$users = [
    1 => ['name' => 'Alice', 'referrer_id' => null],
    2 => ['name' => 'Bob', 'referrer_id' => 1],
    3 => ['name' => 'Charlie', 'referrer_id' => 2],
    4 => ['name' => 'David', 'referrer_id' => 3],
    5 => ['name' => 'Eva', 'referrer_id' => 1],
];

// Dữ liệu bảng đơn hàng
$orders = [
    ['order_id' => 101, 'user_id' => 4, 'amount' => 200.0],  // David mua hàng
    ['order_id' => 102, 'user_id' => 3, 'amount' => 150.0],  // Charlie mua hàng
    ['order_id' => 103, 'user_id' => 5, 'amount' => 300.0],  // Eva mua hàng
];

// Tỷ lệ hoa hồng theo cấp 
$commissionRates = [
    1 => 0.10,  // Cấp 1: 10%
    2 => 0.05,  // Cấp 2: 5%
    3 => 0.02,  // Cấp 3: 2%
];

// Hàm đệ quy tìm referrer theo cấp
function referrers($userId,  $users,  $maxLevel = 3)
{
    // Khai báo biến
    $referrers = [];
    $current = $userId;
    for ($level = 1; $level <= $maxLevel; $level++) {
        $referrer_id = $users[$current]['referrer_id'] ?? null;
        // Điều kiện dừng
        if ($referrer_id == null) break;
        $referrers[$level] = $referrer_id;
        $current = $referrer_id;
    }

    return $referrers;
}

// Hàm tổng hợp
function calculateCommission($orders, $users, $commissionRates)
{
    $totals = []; //tổng hoa hồng mỗi người nhận được
    $commissionDetail = []; //chi tiết hoa hồng nhận đượcđược

    // tính hoa hồng
    foreach ($orders as $order) {

        // lấy id người mua
        $buyerId = $order['user_id'];

        // Lấy số tiền trong đơn hàng của người mua
        $amount = $order['amount'];

        // Lấy mã đơn hàng
        $orderId = $order['order_id'];

        // Xác định người nhận hoa hồng theo cấp độ
        $referrers = referrers($buyerId, $users);

        foreach ($referrers as $level => $referrerId) {
            $rate = $commissionRates[$level] ?? 0; // Tỷ lệ hoa hồng nhận được theo cấp độ
            $commission = $amount * $rate; //Số hoa hồng được nhận theo từng đơn hàng

            //Tính tổng số tiền hoa hồng nhận được

            // Kiểm tra tiền hoa hồng của từng người có hay không
            if (!isset($totals[$referrerId])) {
                $totals[$referrerId] = 0;
            }

            // Tổng số hoa hồng nhận được của mỗi người
            $totals[$referrerId] += $commission;
        }

        // Lấy chi tiết hoa hồng nhận được
        $commissionDetail[] = [
            'referrer_id'  => $referrerId,
            'order_id'  => $orderId,
            'buyer_id'  => $buyerId,
            'commission'  => $commission,
            'level' => $level

        ];
    }
    return [$totals, $commissionDetail];
}

// Hiển thị tổng số hoa hồng
function totals($totals, $users)
{
    echo "<h3>Tổng Số Hoa Hồng Nhận Được</h3>";
    foreach ($totals as $userId => $amount) {
        echo "Người dùng: " . $users[$userId]['name'] . " nhận được $" . number_format($amount, 2) . "<br>";
    }
}


// In ra chi tiết 
function commissionDetail($commissionDetail, $users)
{
    echo "<h3>Chi Tiết Hoa Hồng Nhận Được</h3>";

    foreach ($commissionDetail as $item) {
        $refName = $users[$item['referrer_id']]['name'];
        $buyerName = $users[$item['buyer_id']]['name'];
        $money = number_format($item['commission'], 2);
        echo "- $refName nhận $$money từ đơn #{$item['order_id']} của $buyerName (Cấp {$item['level']})<br>";
    }
}

// Hàm lấy danh sách đơn hàng
[$totals, $commissionDetail] = calculateCommission($orders, $users, $commissionRates);
totals($totals, $users);
commissionDetail($commissionDetail, $users);
