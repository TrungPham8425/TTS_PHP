<!-- 
# 1. AJAX PHP
 AJAX (Asynchronous JavaScript and XML) là kỹ thuật cho phép gửi/nhận dữ liệu với server mà không cần tải lại trang. 
VD:
-->
<button onclick="loadData()">Lấy dữ liệu</button>
<div id="result"></div>

<script>
    function loadData() {
        fetch("get_data.php")
            .then(res => res.text())
            .then(data => {
                document.getElementById("result").innerHTML = data;
            });
    }
</script>

<!-- Design Pattern -->
<!-- Design Pattern là các giải pháp lập trình tổng quát, tái sử dụng được -->
<!-- Dùng để giải quyết những vấn đề thường gặp khi phát triển phần mềm -->
<!-- Có 3 nhóm Design Pattern chính: Creational, Structural, Behavioral  -->
<?php
# 1. Creational Pattern – Singleton
// Dùng để kết nối database, cconfig global...
// VD: 
class DBConnection
{
    private static $instance;
    private $conn;

    private function __construct()
    {
        $this->conn = new PDO("mysql:host=localhost;dbname=test", "root", "");
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DBConnection();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

# 2.  Structural Pattern – Adapter
// Dùng để kết nối giữa các hệ thống không tương thích trực tiếp với nhau
// VD: 
class OldSystem
{
    public function getOldData()
    {
        return "Dữ liệu cũ";
    }
}

class Adapter
{
    private $oldSystem;

    public function __construct(OldSystem $oldSystem)
    {
        $this->oldSystem = $oldSystem;
    }

    public function getData()
    {
        return $this->oldSystem->getOldData();
    }
}

// Client
$adapter = new Adapter(new OldSystem());
echo $adapter->getData(); // Dữ liệu cũ


# 3. Behavioral Pattern – Observer
// Dùng trong hệ thống thông báo, nhật ký, feed...
// VD:
interface Observer
{
    public function update($msg);
}

class EmailNotifier implements Observer
{
    public function update($msg)
    {
        echo "Gửi email: $msg<br>";
    }
}

class Logger implements Observer
{
    public function update($msg)
    {
        echo "Ghi log: $msg<br>";
    }
}

class EventManager
{
    private $observers = [];

    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function notify($msg)
    {
        foreach ($this->observers as $obs) {
            $obs->update($msg);
        }
    }
}

// Sử dụng
$event = new EventManager();
$event->attach(new EmailNotifier());
$event->attach(new Logger());

$event->notify("Người dùng mới đăng ký");
