<?php

# 1. Hàm cơ bản (Built-in Functions): các hàm có sẵn
// Có 5 dạng hàm có sẵn trong PHP

// Hàm xử lý chuỗi
strlen("Hello"); //Hàm hiển thị độ dài chuỗi
strtoupper("php"); //Hàm viết hoa tất cả các chữ cái
strtolower('PHP'); //Hàm chuyển tất cả các chữ thành chữ viết thường
substr("abcdef", 1, 3); // Hàm lấy ký tự trong chuỗi
strpos("Xin chao", "o"); // Hàm tìm kiếm ký tự trong chuỗi ký tự
trim(" hall "); //Hàm xóa khoảng trắng đầu cuối trong chuỗi

// Hàm số học
abs(-5); //Hàm lấy giá trị tuyệt đối
round(2.5); // Làm trong số
floor(3.98); //Làm trong số xuống
ceil(1.2); //Làm tròn số lên
pow(4, 2); // Hàm tính lũy từa
sqrt(4); // Hàm tính căn bậc 2
rand(1, 100); //Hàm random 1 số ngẫu  nhiên bất kỳ

// Hàm ngày giờ
date("Y-m-d"); //Hàm lấy thời gian theo năm - tháng - ngày
time(); //Lấy thời gian theo thời gian thực

// Hàm mảng
count([1, 2, 3]); //Đếm số ký tự trong mảng
// Hàm thêm ký tự vào mảng 
$arr = [1, 2, 3];
array_push($arr, "name");
// Hàm xóa phân tử cuối mảng
array_pop($arr);

// Một số hàm hay sử dụng khác

// - isset(): dùng để kiểm tra biến bất kỳ có tồn tại
// - empty(): dùng để kiểm tra biến rỗng
// -  var_dump(): hiển thị chi tiết mảng và kiểu dữ liệu

# 2. Hàm do người dùng định nghĩa(Uer-Defined Functions)
// Người dùng có thể tự tạo ra các hàm để tái sử dụng mã, giúp chương trình gọn gàng và dễ quản lý
// Một hàm có thể được gọi nhiều lầnlần
// Cú pháp khai báo 

function tenHam()
{
    // code...
}

// VD:
function hello()
{
    echo "Xin chào<br>";
}
hello(); //Gọi hàm

// Với hàm có tham số giúp truyền giá trị vào trong hàm, giúp hàm linh hoạt hơn
// VD: 

function hello1($ten)
{
    echo "Xin chào $ten<br>";
}
hello1("Nguyễn Văn A"); //Gọi hàm

// Hàm trả về giá trị với return

function tong($a, $b)
{
    return $a + $b;
}
$tong = tong(3, 5);
echo "<br>Tổng 2 số là:$tong";

// Hàm có tham số mặc định

function reoTen($name = "A")
{
    echo "<br>Chào bạn" . $name;
}

// Với hàm này nếu không truyền giá trị thì khi gọi hàm sẽ hiện giá trị mặc định
reoTen();
// Khi truyền giá trị hàm sẽ trả về giá trị đó
reoTen("My name B");

# 3. Hàm ẩn danh (Anonymous Functions)
// Là các hàm không có tên, được dùng khi gán hàm vào biến, truyền hàm như một đối số, cần tạo hàm mới.

// Cú pháp cơ bản để tọ hàm
$hamAnDanh = function ($thamSo) {
    // code... 

};

// VD:
$hoTen = function ($name) {
    echo "<br>Họ tên của bạn là:" . $name;
};
echo $hoTen("Nguyễn Văn C");

# 4. Hàm đệ quy (Recursive Functions)
// Hàm đệ quy là hàm tự gọi lại chính nó trong quá trình thực thi
// Hàm đệ quy thường dùng để tính toán lặp lại hoặc duyệt cây thư mục, cấu trúc phân cấp

// Cú pháp cơ bản 
/*
function ten($thamSo)
{
    if ("dieu_kien_dung") {
        return "ket_qua";
    } else {
        return ten("Tham_so_moi");
    }
};
*/
// VD:Tính tổng thừ 1 đến n

function tinhTong($n)
{
    if ($n == 1) {
        return 1;
    } else {
        return $n + tinhTong($n - 1);
    }
}
echo tinhTong(10);

# 5. Hàm không có tham số 
// Là hàm không nhận bất kỳ giá trị nào khi được gọi 
// Hàm này thường được dùng để thực hiện một hành động cố định không phụ thuộc vào kiểu dữ liệu bên ngoài

// Cú pháp 
function hamKhong()
{
    //codecode
}

// VD: In ngay hiện tại

function today()
{
    echo "<br>Hôm nay là: " . date("d-m-Y");
}
today();

# 6. Hàm có tham số (Parameterless Functions)
// Là hàm nhận dữ liệu từ bên ngoài khi gọi

// Cú pháp 
function hamCoThamSo($thamSo1, $thamSo2)
{
    //code...
}
// VD:

function tuoi($age)
{
    echo "<br>Tuổi của bạn là: " . $age;
}
tuoi(20);

# 7. Hàm có tham số mặc định (Default Paramenters)
// Là hàm có giá trị được gán sẵn cho tham số khi định nghĩa hàm
// Nếu không truyền giá trị thì hàm sẽ dùng giá trị mặc định

// Cú pháp
function hamCoThamSoMD($thamSo = 12425)
{
    //code...
}
// VD:

function name($name = "user")
{
    echo "<br>Tuổi của bạn là: " . $name;
}
tuoi("a");

# 8. Hàm trả về kiểu dữ liệu cụ thể
// Hàm này giúp bảo vệ dữ liệu đầu ra, giảm lỗi logic, giúp code dễ đọc hơn bằng cách thêm đấu ':' sau phần tham số
// Cú pháp

/*
function ham($thamSo) : kieu_tra_ve
{
   code ...
}
*/

// VD:
function tong1(int $a, int $b): int
{
    return $a + $b;
}

echo tong(3, 4);

# 9. Hàm biến đổi tham số (Variadic Functions)
// Là hàm có thể nhận một số tham số không xác định
// Cú pháp
// function tenHam(...$danhSachThamSo) {
//     // $danhSachThamSo là một mảng chứa các giá trị được truyền vào
// }

// VD:
function inTen(...$ten)
{
    foreach ($ten as $value) {
        echo "<br>Xin chào:" . $value;
    }
}
inTen("Nguyễn Văn A", "Nguyễn Văn B", "Nguyễn Văn C");
echo "<br>";

# 10. Biến toàn cục và phạm vi (Global & Scope)
// Phạm vi là khu vực trong mã mà một biến có thể truy cập
// Có 3 loại vi biến: Local, Global, Static

// - Biến cục bộ (Local)
// Là biến bên trong hàm
// VD:
function viDu()
{
    $a = 10;
    echo $a . "<br>";
}
viDu();
// - Biến toàn cục
// Là biến được khai báo bên ngoài hàm
// Có 2 cách sử dụng

// Khai báo biến
$x = 10;
$y = 20;
function tongAB()
{
    global $x, $y;
    echo $x + $y . "<br>";
}

tongAB();


// - Biến tĩnh (Static)
// Khi dùng biến tĩnh sẽ không mất giã trị khi gọi lại hàm
// VD:
function demSo()
{
    static $so = 0;
    $so++;
    echo $so . "<br>";
}
demSo();
demSo();
demSo();
demSo();
