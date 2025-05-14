<!-- Lớp AffiliatePanter -->
<?php

class AffiliatePartner
{
    // Khai báo hằng số
    const PLATFORM_NAME = "VietLink Affiliate"; //Tên nền tảng

    // Khai báo thuộc tính
    private $name;
    private $email;
    protected $commissionRate;
    private $isActive;

    // Khởi tạo hàm
    public function __construct($name, $email, $commissionRate, $isActive = true)
    {

        $this->name = $name;
        $this->email = $email;
        $this->commissionRate = $commissionRate;
        $this->isActive = $isActive;
    }
    // Hàm hủy
    public function __destruct()
    {
        echo "Đã hủy cộng tác viên $this->name <br>";
    }

    // Tinh hoa hồng
    public function calculateCommission($orderValue)
    {
        return $orderValue * ($this->commissionRate / 100);
    }
    // Trả về thông tin tổng quan của cộng tác viên
    public function getSummary()
    {
        return "Tên: {$this->name} | Email: {$this->email} | Tỷ lệ hoa hồng: {$this->commissionRate} | Trạng thái: " . ($this->isActive ? "Hoạt động" : "Ngưng hoạt động") . " | Nền tảng: " . self::PLATFORM_NAME;
    }
}
// Lớp Premium Affiliate Partner kế thừa từ AffiliatePartner
class PremiumAffiliatePartner extends AffiliatePartner
{
    // Thuộc tính bổ sung
    private $bonusPerOrder;
    public function __construct(string $name, string $email, float $commissionRate, float $bonusPerOrder, bool $isActive = true)
    {
        parent::__construct($name, $email, $commissionRate, $isActive);
        $this->bonusPerOrder = $bonusPerOrder;
    }

    public function calculateCommission($orderValue)
    {
        return parent::calculateCommission($orderValue) + $this->bonusPerOrder;
    }
}
// Lớp AffiliateManager
class AffiliateManager
{
    private $listPartners = [];
    // Thêm một cộng tác viên vào hệ thống

    public function addPartner($affiliate)
    {
        $this->listPartners[] = $affiliate;
    }
    // Lấy ra thông tin cộng tác viên

    public function listPartners()
    {
        foreach ($this->listPartners as $partner) {
            echo  $partner->getSummary() . "<br>";
        }
    }

    // Tính tổng hoa hồng nếu mỗi CTV đều thực hiện thành công một đơn hàng
    public function totalCommission($orderValue)
    {
        $total = 0;
        foreach ($this->listPartners as $panter) {
            $commission = $panter->calculateCommission($orderValue);
            echo "Hoa hồng của cộng tác viên: " . number_format($commission, 0, ',', '.') . " VND<br>";
            $total += $commission;
        }
        return $total;
    }
}

// Tạo ít nhất 3 cộng tác viên
$partner1 = new AffiliatePartner("Nguyễn Văn A", "nguyenvana@gmail.com", 5);
$partner2 = new AffiliatePartner("Nguyễn Văn A", "nguyenvana@gmail.com", 7);
$partner3 = new PremiumAffiliatePartner("Nguyễn Văn B", "nguyenvanB@gmail.com", 10, 50000);

// Khởi tạo 
$manager = new AffiliateManager();

// Thêm cộng tác viên
$manager->addPartner($partner1);
$manager->addPartner($partner2);
$manager->addPartner($partner3);

echo "<h3>Danh sách cộng tác viên</h3>";
$manager->listPartners();

echo "<h3>Hoa hồng trên đơn hàng 2.000.000 VNĐ</h3>";
$manager->totalCommission(2000000);
