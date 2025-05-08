<?php

/* Top 15 hàm được sử dụng nhiều nhất trong các dự án PHP */

# 1. Hàm count() 
// Hàm đếm độ dài của mảng
// VD: 
$name = ["a", "b"];
echo count($name) . "<br>";

# 2. Hàm array_merge()
// Hàm này dùng để gộp nhiều hàm thành 1
// VD:
$a = [1, 2];
$b = [3, 4];

$merge =  array_merge($a, $b);
print_r($merge);
echo "<br>";
# 3. Hàm array_push()
// Dùng để thêm phần tử vào cuối mảng
// VD:
$products = ['iphone', 'samsung'];
array_push($products, "Oppo");
print_r($products);
echo "<br>";

# 4. Hàm array_pop()
// Dùng để xóa phần tử cuối mảng
// VD:
array_pop($products);
print_r($products);
echo "<br>";

# 5. Hàm array_shift()
// Dùng để xóa phần tử đầu tiên trong mảng
// VD: 
array_shift($products);
print_r($products);
echo "<br>";

# 6. Hàm array_unshift()
// Dùng để thêm phần tử vào đầu mảng
// VD:
array_unshift($products, "Xiaomi");
print_r($products);
echo "<br>";

# 7. Hàm in_array()
// Dùng để kiểm tra phần tử trong mảng có tồn tại không
echo  in_array('samsung', $products) . "<br>";

# 8. Hàm array_key_exists()
// Dùng để kiểm tra key có tồn tại không
$member = [
    'id' => 1,
    'name' => "Nguyễn văn a"
];
echo array_key_exists('id', $member) . "<br>";

# 9. Hàm array_keys()
// Lấy toàn bộ key của mảng
print_r(array_keys($member));
echo "<br>";

# 10. Hàm array_values()
// Dùng để lấy toàn bộ phần tử của mảng
print_r(array_values($member));
echo "<br>";

# 11. Hàm array_filter()
// Dùng để lọc mảng theo điều kiện
$filter = [0, 1, 2, null];
$number = array_filter($filter);
print_r($number);
echo "<br>";

# 12. Hàm array_map()
// Dùng để áp dụng hàm lên phần tử
// VD: Nhân 1 số bất kỳ với phần tử trong mảng
$result = array_map(fn($n) => $n * 2, $number);
print_r($result);
echo "<br>";

# 13. Hàm sort()
// Dùng để sắp xếp mảng tăng dần
$sort = [2, 3, 5, 6, 7, 1, 9];
sort($sort);
print_r($sort);
echo "<br>";

# 14. Hàm array_unique()
// Dùng để loại bỏ các phần tử trung lặp trong mảng
$uni = [1, 2, 1, 3, 3, 4, 2, 5];
print_r(array_unique($uni));
echo "<br>";

# 15. Hàm compact()
// Dùng để tạo mảng từ các biến 
$x = 5;
$y = 6;
$z = 7;
$compact = compact('x', 'y', 'z');
print_r($compact);
