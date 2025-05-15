<?php

namespace XYZBank\Accounts;

class SavingsAccount extends BankAccount implements InterestBearing
{
    use TransactionLogger;

    // Lai xuat hang nam
    private const INTEREST_RATE = 0.05;

    // So du toi thieu yeu cau
    private const MINIMUM_BALANCE = 1000000;

    // Gui tien vao tai khoan tiet kiem
    public function deposit($amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Số tiền gửi phải lớn hơn 0");
        }
        $this->balance += $amount;
        $this->logTransaction("Gửi tiền", $amount, $this->balance);
    }
    // Rut tien tu tai khoan
    public function withdraw($amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Số tiền rút phải lớn hơn 0");
        }
        if ($this->balance - $amount < self::MINIMUM_BALANCE) {
            throw new \InvalidArgumentException("Số dư sau rút phải >= " . number_format(self::MINIMUM_BALANCE, 0, ',', '.') . " VNĐ");
        }
        $this->balance -= $amount;
        $this->logTransaction("Rút tiền", $amount, $this->balance);
    }
    // Lấy loại tài khoản
    public function getAccountType(): string
    {
        return "Tiết kiệm";
    }
    // Tính lãi suất hàng năm
    public function calculateAnnualInterest(): float
    {
        return $this->balance * self::INTEREST_RATE;
    }
}
