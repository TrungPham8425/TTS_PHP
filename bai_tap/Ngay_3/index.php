<?php
// Danh sách nhân viên
$employees = [
    ['id' => 101, 'name' => 'Nguyễn Văn A', 'base_salary' => 5000000],
    ['id' => 102, 'name' => 'Trần Thị B', 'base_salary' => 6000000],
    ['id' => 103, 'name' => 'Lê Văn C', 'base_salary' => 5500000],
];

// Bảng chấm công
$timesheet = [
    101 => ['2025-03-01', '2025-03-02', '2025-03-04', '2025-03-05'],
    102 => ['2025-03-01', '2025-03-03', '2025-03-04'],
    103 => ['2025-03-02', '2025-03-03', '2025-03-04', '2025-03-05', '2025-03-06'],
];

// Phụ cấp và khấu trừ
$adjustments = [
    101 => ['allowance' => 500000, 'deduction' => 200000],
    102 => ['allowance' => 300000, 'deduction' => 100000],
    103 => ['allowance' => 400000, 'deduction' => 150000],
];

// Tính số ngày công từng người
$workingDays = array_map(function ($day) {
    return count(array_unique($day)); //Đếm số ngày chấm công và không lấy số  ngày trùng

}, $timesheet);
// var_dump($worksDay);

// Tính lương thực lính

// Khai báo ngày làm việc tiêu chuẩn
define('STANDARD_DAY', 22);

// 2. Tính lương của từng nhân viên
$salarys = array_map(function ($employee) use ($workingDays, $adjustments) {
    $id = $employee['id']; //ID nhân viên
    $day = $workingDays[$id]; //Số ngày làm viêc
    $baseSalary = $employee['base_salary']; //Lương cơ bản
    $allowance = $adjustments[$id]['allowance'] ?? 0; //Phụ cấp
    $deduction = $adjustments[$id]['deduction'] ?? 0; //Khấu hao

    // Lương thực lĩnh
    $salary = ($baseSalary / STANDARD_DAY) * $day + $allowance - $deduction;
    // echo ($allowance);
    return [
        'id' => $id,
        'name' => $employee['name'],
        'day' => $day,
        'base' => $baseSalary,
        'allowance' => $allowance,
        'deduction' => $deduction,
        'salary' => $salary
    ];
}, $employees);



// 3. Tạo báo cáo tổng hợp bảng lương

echo "<h3>Bảng lương nhân viên</h3>";
echo "<table border='1' cellpadding='8' cellspacing='0'>
    <thead>
        <tr> 
            <th>Mã NV</th>
            <th>Họ tên</th>
            <th>Ngày công</th>
            <th>Lương CB</th>
            <th>Phụ cấp</th>
            <th>Khấu trừ</th>
            <th>Thực lĩnh</th>
        </tr>
    </thead>
    <tbody>";

foreach ($salarys as $key) {
    echo "<tr>
        <td>{$key['id']}</td>
        <td>{$key['name']}</td>
        <td>{$key['day']}</td>
        <td>" . number_format($key['base'], 0, ',', '.') . " ₫</td>
        <td>" . number_format($key['allowance'], 0, ',', '.') . " ₫</td>
        <td>" . number_format($key['deduction'], 0, ',', '.') . " ₫</td>
        <td>" . number_format($key['salary'], 0, ',', '.') . " ₫</td>
    </tr>";
}

echo "</tbody></table>";


// 4. Tìm nhân viên có ngày công cao nhất và thấp nhất

// Lấy ngày công của nhân viên
$works = $workingDays;
// Sắp xếp theo thứ tự giản dần ngày công
sort($works);
// Lấy nhân viên có ngày công nhiều nhất
$min = $works[0];
$max = end($works);
$minEmpIds = array_keys($workingDays, $min);
$maxEmpIds = array_keys($workingDays, $max);

$empNames = array_column($employees, 'name', 'id');
echo "<p>Nhân viên làm nhiều nhất: " . $empNames[$maxEmpIds[0]] . " ($max ngày công)</p>";
echo "<p>Nhân viên làm ít nhất: " . $empNames[$minEmpIds[0]] . " ($min ngày công)</p>";

// 5. Cập nhật dữ liệu nhân viên + chấm công
$newEmployees = [
    ['id' => 104, 'name' => 'Phám Thị D', 'base_salary' => 5200000],
];
$employees = array_merge($employees, $newEmployees);
$timesheet[104] = ['2025-03-02'];
array_push($timesheet[101], '2025-03-06');
array_unshift($timesheet[102], '2025-03-07');
array_pop($timesheet[103]);
array_shift($timesheet[103]);

// 6. Lọc nhân viên có >= 4 ngày công
$filtered = array_filter($salarys, function ($emp) {
    return $emp['day'] >= 4;
});
echo "<h4>Nhân viên đủ điều kiện xét thưởng:</h4><ul>";
foreach ($filtered as $emp) {
    echo "<li>{$emp['name']} ({$emp['day']} ngày công)</li>";
}
echo "</ul>";

// 7. Kiểm tra logic
echo "<h4>Kiểm tra logic:</h4>";
echo "Trần Thị B có đi làm 2025-03-03: " . (in_array('2025-03-03', $timesheet[102]) ? 'Có' : 'Không') . "<br>";
echo "Phụ cấp nhân viên 101 tồn tại: " . (array_key_exists(101, $adjustments) ? 'Có' : 'Không') . "<br>";

// 8. Làm sạch dữ liệu chấm công
foreach ($timesheet as $id => &$days) {
    $days = array_unique($days);
}
unset($days);

// 9. Tổng quỹ lương
$totalSalary = array_reduce($salarys, fn($carry, $item) => $carry + $item['salary'], 0);
echo "<p><strong>Tổng quỹ lương tháng 03/2025:</strong> " . number_format($totalSalary, 0, ',', '.') . " VND</p>";
