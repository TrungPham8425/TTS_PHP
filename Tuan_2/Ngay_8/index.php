<?php
# 1. Abstract Classes (Lớp trừu tượng)
// Không thể tạo các đối tượng trong lớp trừu tượng
// Lớp trừu tượng dùng để làm khuôn mẫu cho các lớp kế thừa
// VD:

// Lớp trừu tượng
abstract class Animal
{
    abstract public function makeSound();
    public function sleep()
    {
        echo "sleeping...<br>";
    }
}
class Dog extends Animal
{
    public function makeSound()
    {
        echo "Gâu gâu...<br>";
    }
}

$dog = new Dog();
$dog->makeSound();
$dog->sleep();

# 2.Interfaces
// Dùng để chỉ định phương thức không chứa logic thực thi
// Một implements có thể chứa nhiều interface

interface Logger
{
    public function log(string $message);
}

class FileLogger implements Logger
{
    public function log(string $message)
    {
        echo "Log to file: $message\n";
    }
}

# 3. Traits
// Dùng để chia sẻ phương thức giữa các class, tránh trùng lặp mã
trait LoggerTrait
{
    public function log($msg)
    {
        echo "[LOG]: $msg\n";
    }
}

class Service
{
    use LoggerTrait;
}

$svc = new Service();
$svc->log("Hello");

# 4. Static Methods (Phương thức tĩnh)
// Dùng để gọi mà không cần khởi tạo đối tượng
class Math
{
    public static function add($a, $b)
    {
        return $a + $b;
    }
}
echo Math::add(2, 5);

# 5. Static Properties (Thuộc tính tĩnh)
// Dùng để chia sẻ giá trị dùng chung cho mọi đối tượng
// VD:
class Counter
{
    public static $count = 0;
    public function __construct()
    {
        self::$count++;
    }
}
new Counter();
new Counter();
echo Counter::$count;

# 6. Namespaces
// Dùng để tránh xung đột tên class hoặc hàm giữa các file khác nhau 

# 7. Iterables (Duyệt mảng hoặc object)
// Dùng để nhận cả array hoặc Traversable. 
// VD:
function printItems(iterable $items)
{
    foreach ($items as $item) {
        echo $item . "\n";
    }
}

printItems(['a', 'b', 'c']);
