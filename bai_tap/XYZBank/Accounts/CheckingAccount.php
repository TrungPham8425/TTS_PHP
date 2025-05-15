<?php
// Đặt trong namespace
namespace XYZBank\Accounts;

// Lớp CheckingAccount kế thừa BankAccount
class CheckingAccount extends BankAccount
{
    // Sử dụng trait TransactionLogger
    use TransactionLogger;

    // Gửi tiền vào tài khoản
    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Số tiền gửi phải lớn hơn 0");
        }
        $this->balance += $amount;
        $this->logTransaction("Gửi tiền", $amount, $this->balance);
    }

    // Rút tiền từ tài khoản
    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Số tiền rút phải lớn hơn 0");
        }
        if ($amount > $this->balance) {
            throw new \InvalidArgumentException("Số dư không đủ");
        }
        $this->balance -= $amount;
        $this->logTransaction("Rút tiền", $amount, $this->balance);
    }

    // Lấy loại tài khoản
    public function getAccountType(): string
    {
        return "Thanh toán";
    }
}
