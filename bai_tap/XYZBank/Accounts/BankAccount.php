<?php
// Đặt trong namespace
namespace XYZBank\Accounts;

// Lớp trừu tượng BankAccount
abstract class BankAccount
{
    // Khai báo thuộc tính
    protected string $accountNumber; // Mã số tài khoản
    protected string $ownerName; // Tên chủ tài khoản
    protected float $balance; // Số dư tài khoản

    // Khởi tạo tài khoản ngân hàng
    public function __construct(string $accountNumber, string $ownerName, float $balance)
    {
        $this->accountNumber = $accountNumber;
        $this->ownerName = $ownerName;
        $this->balance = $balance;
        // Tăng số lượng tài khoản
        Bank::addAccountNumber();
    }

    // Lấy số dư tài khoản
    public function getBalance(): float
    {
        return $this->balance;
    }

    // Lấy tên chủ tài khoản
    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    // Lấy mã số tài khoản
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    // Gửi tiền vào tài khoản
    abstract public function deposit(float $amount): void;

    // Rút tiền từ tài khoản
    abstract public function withdraw(float $amount): void;

    // Lấy loại tài khoản
    abstract public function getAccountType(): string;
}
