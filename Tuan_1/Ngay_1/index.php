<?php
// 2. Syntax
echo "hello world<br>";

//  3. Có 2 cach coment trong PHP
// cach 1:
// Day la coment

// cach 2:
/* Day la coment
 xin chao moi nguoi
*/


// 4. Valriables

$name = "Pham Phu Trung";
$age = 20;
$maritalStatus = false;

// 5. echo/print/print_r

// echo 
// Có thể in ra nhiều chuỗi cùng lúc
echo $name, $age;

// print
// Không thể in ra nhiều chuỗi
print $name;
print $age;
print $maritalStatus . "<br>";

// print_r
// Dùng đển hiện thị mảng hoặc object
$array = ['Pham Phu Trung', 20, false];
print_r($array);

// 6. Các kiểu dữ liệu (data types)
// Kiểu dữ liệu đơn giản (Scalar types)

// int: integer (Số nguyên)
// VD: 
$x = 1;

// float: số thực
// VD: 
$y = 999.999;

// string: chuỗi ký tự
// VD:
$z = "Hello world";

//bool: chỉ có true hoặc false
// VD:
$t = true;

// test kiểu dữ liệu 
var_dump($x, $y, $z, $t);
echo "<br>";

// Kiểu dữ liệu phức hợp(Compound types)
// array: Dữ liệu dạng mảng -> Tập hợp các phần tử có thể khác kiểu dữ liệu
// VD:
$array = [1, 9.99, "Xin chao", true];
var_dump($array);

// Object: Là một thực thể đại diện cho thuộc tính và phương thức
// VD:

class Products
{
    public $iphone = "Iphone 14";
}

// 7. Strings
// VD: Kiểu dữ liệu string
$name = "Pham Phu Trung";
echo "Que quan: Hai Duong";
echo 'Trung<br>';

// 8. Numbers
// VD: Kiểu dữ liệu number
// Kiểu int
$int = 1;
// Kiểu float
$float = 1.1;

// 9. Casting (Ép kiểu dữ liệu)
// Ép dữ liệu từ chuỗi(string) thành số nguyên(int)
var_dump((int)"1234");
echo "<br>";

// Ép dữ liệu từ chuỗi(string) thành số thực(float)
var_dump((float)"1234");
echo "<br>";

// 10. Math: Phép tính
echo 1 + 2; // tổng
echo 1 - 1; // hiệu
echo 1 / 1; // thương
echo 2 * 3; // tích
echo pow(2, 3); //lũy thừa
echo sqrt(16); //căn bậc 2

// 11. Constants: hằng số
// vd:
define("PI", 3.14);
const VAT = 0.1;

// 12. Magic Constants
echo __DIR__; //Lấy thư mục hiện tại
echo __FILE__; //Lấy đường dẫn của file
echo __LINE__; //Hiển thị số dòng hiện tại


// 13. Operators: toán tử
// Có 4 loại toán tử
// Toán tử số học: +, -, * , /
// Toán tử so sánh: ==, ===, !=, !==, >, >=, <, <=
// Toán tử gán: =, +=, -=
// Toán tử logic: ||, &&, !

// 14. If...Else...Elseif
// Khai báo biến
$test = 1;

if ($test = 1) {
    echo "Xin chao ban";
} elseif ($test = 2) {
    echo "Hello world";
} else {
    echo "Good job";
}

// 15. switch
// Khai báo biến

$day = 1;

switch ($day) {
    case 1:
        echo "Monday";
        break;
    case 2:
        echo "Monday";
        break;
    default:
        echo "Sunday";
}

// 16. Loops 
// Vòng lặp for
$i = 1;
for ($i = 0; $i < 5; $i++) {
    echo "Xin chao";
}
// while

while ($i <= 5) {
    echo "Số: $i <br>";
    $i++;
}
// do - while

do {
    echo "Vòng: $i <br>";
    $i++;
} while ($i <= 5);
// foreach
// khai báo biến
$products = ['iphone', 'samsung', 'oppo'];

foreach ($products as $item) {
    echo $item;
}

// 17.Function: hàm
function greeet($name)
{
    return "Hello A";
}
echo greeet("Nguyen Van B");

// 18. Array Basic
$fruits = ["apple", "banana"];
echo $fruits[0]; //Hiển thị phần tử có index = 1 
