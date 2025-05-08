<?php
// Data
$oderList = [
    'oder1' => [
        'quantity' => 5,
        'price' => 99.99,
        'name' => 'Spring Sale 2025',
        'status' => true,
        'oderLists' => [
            'SP001' => 99.99,
            'ID002' => 49.99,
            'ID003' => 1.99,
            'ID004' => 9.99,
            'ID005' => 199.99
        ],

    ],
    'oder2' => [
        'quantity' => 5,
        'price' => 299.99,
        'name' => 'Summer Sale 2025',
        'status' => true,
        'oderLists' => [
            'DH001' => 99.99,
            'DH002' => 49.99,
            'DH003' => 1.99,
            'DH004' => 9.99,
            'DH005' => 199.99
        ],

    ],
    'oder3' => [
        'quantity' => 5,
        'price' => 99.99,
        'name' => 'Warm Sale 2025',
        'status' => false,
        'oderLists' => [
            'SP001' => 99.99,
            'SP002' => 49.99,
            'SP003' => 1.99,
            'SP004' => 9.99,
            'SP005' => 199.99
        ],

    ]
];

const COMMISSION_RATE = 0.2;
const VAT_RATE = 0.1;

// doi kieu du lieu

foreach ($oderList as $key => $oder) {
    // hien thi thong tin chien dich
    echo '<h2>Chien dich:' . $oder['name'] . '</h2>';
    echo 'Trang thai: ' . ($oder['status'] ? "<span style='color: green;'>Dang dien ra</span>" : "<span style='color: red;'>Da ket thuc</span>");

    // ep kieu du lieu
    $oderList['oder1']['price'] = (int)$oder['quantity'];

    // tinh doanh thu
    $revenue = $oder['price'] * $oder['quantity'];
    echo '<br>Doanh thu cua chien dich: ' . $revenue . ' USD';

    // chi phi hoa hong
    $commission_costs = $revenue * COMMISSION_RATE;
    echo '<br>Chi phi hoa hong: ' . $commission_costs . 'USD';

    // tinh loi nhuan
    $profit = $revenue - $commission_costs - $revenue * VAT_RATE;
    echo '<br>Loi nhuan cua chien dich: ' . $profit . 'USD';

    // danh gia loi nhuan 
    if ($profit > 0) {
        echo "<br><span style='color: green;'>Chiến dịch thành công</span>";
    } else if ($profit = 0) {
        echo "<br><span style='color: blue;'>Chiến dịch hòa vốn</span>";
    } else {
        echo "<br><span style='color: red;'>Chiến dịch thất bại</span>";
    }

    //  chi tiet don hang
    echo '<h3>Chi tiet don hang</h3>';
    print_r($oder['oderLists']);



    echo '<hr>';
}

// Magic count debug
echo 'FILE: ' . __FILE__ . ' - dong code: ' . __LINE__;
