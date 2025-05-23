<?php
// Đặt trong namespace
namespace XYZBank;

// Nạp các file cần thiết
require_once 'Accounts/BankAccount.php';
require_once 'Accounts/InterestBearing.php';
require_once 'Accounts/TransactionLogger.php';
require_once 'Accounts/SavingsAccount.php';
require_once 'Accounts/CheckingAccount.php';
require_once 'Accounts/Bank.php';
require_once 'Accounts/AccountCollection.php';

use XYZBank\Accounts\SavingsAccount;
use XYZBank\Accounts\CheckingAccount;
use XYZBank\Accounts\AccountCollection;
use XYZBank\Accounts\Bank;

// Tạo danh sách tài khoản
$accounts = new AccountCollection();
$accounts->addAccount(new SavingsAccount("10201122", "Nguyễn Thị A", 20000000)); // Tài khoản tiết kiệm
$accounts->addAccount(new CheckingAccount("20301123", "Lê Văn B", 8000000)); // Tài khoản thanh toán
$accounts->addAccount(new CheckingAccount("20401124", "Trần Minh C", 12000000)); // Tài khoản thanh toán

// Thực hiện giao dịch
try {
    $leVanB = $accounts->getIterator()->offsetGet(1); // Lấy tài khoản của Lê Văn B
    $tranMinhC = $accounts->getIterator()->offsetGet(2); // Lấy tài khoản của Trần Minh C

    $leVanB->deposit(5000000); // Gửi 5.000.000 VNĐ
    $tranMinhC->withdraw(2000000); // Rút 2.000.000 VNĐ
} catch (\InvalidArgumentException $e) {
    echo "Lỗi giao dịch: " . $e->getMessage() . "<br>";
}

// Hiển thị thông tin tài khoản
echo '<table border="1" cellpadding="8" cellspacing="0">';
echo '<thead>';
echo '<tr>';
echo '<th>Tài khoản</th>';
echo '<th>Chủ sở hữu</th>';
echo '<th>Loại</th>';
echo '<th>Số dư (VNĐ)</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($accounts as $account) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($account->getAccountNumber()) . '</td>';
    echo '<td>' . htmlspecialchars($account->getOwnerName()) . '</td>';
    echo '<td>' . htmlspecialchars($account->getAccountType()) . '</td>';
    echo '<td>' . number_format($account->getBalance(), 0, ',', '.') . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';


// Hiển thị lãi suất tài khoản tiết kiệm
$nguyenThiA = $accounts->getIterator()->offsetGet(0); // Lấy tài khoản của Nguyễn Thị A
echo "Lãi suất hàng năm cho Nguyễn Thị A: " . number_format($nguyenThiA->calculateAnnualInterest(), 0, ',', '.') . " VNĐ<br>";

// Hiển thị thông tin ngân hàng
echo "Tổng số tài khoản đã tạo: " . Bank::getTotalAccounts() . "<br>";
echo "Tên ngân hàng: " . Bank::getBankName() . "<br>";
