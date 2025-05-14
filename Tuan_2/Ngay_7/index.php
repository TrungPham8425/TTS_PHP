<?php

# 1. What is OOP
// OOP(Object-Oriented Programming) hay còn gọi là lập trình hướng đối tượng
// Là phương pháp lập trình trong đó chương trình được tổ chức thành các đối tượng 
// Các đối tượng này đại diện cho các thực thể trong thế giới thực
// Lợi ích của OOP: Có thể tái sử dụng mã, dễ mở rộng và bảo trì, tổ chức logic rõ dàng và mô hinh hóa tốt hơn

# 2. Classes/Objects: Các lớp/ các đối tượng
// Các lớp (Class) là khuông mẫu để tạo ra các đối tượng (Object)
// Ngược lại các đối tượng (Objects) là các thực thể được tạo từ các lớp(Class)
// VD:
// Lớp sản phẩm
class Products
{
    // Dối tượng Audi
    public $audi;
}

# 3.Constructor
// Constructor là các hàm đặc biệt chạy tự động khi đối tượng được tạo ra


# 4. Destructor
// Có tác dụng tự động gọi khi đối tượng bị hủy(kết thúc chương trình)

class Car
{
    public $brand;
    // VD: Contractor
    public function __construct($brand)
    {
        $this->brand = $brand;
    }
    // VD: Destructor 
    public function __destruct()
    {
        echo "Đối tượng bị hủy";
    }
}
$car = new Car("Honda");

# 5. Access Modifiers
// Dùng để xác định quyền truy cập của thuộc tính hoặc phương thức
// Có 3 loại: public, protected, private
// VD:
class Access
{
    public $name;
    protected $age;
    private $profile;
}

# 6.  Inheritance (Kế thừa)
// Cho phép class con kế thừa thuộc tính từ class cha
// VD: 
class Employees
{
    //code
}
class Member extends Employees
{
    //code...
}

# 7. Constants
// Là hằng số trong class(conts) 
// Hằng số này không đổi trong chương trình
// VD:
class Movies
{
    const quantity = 100;
}
