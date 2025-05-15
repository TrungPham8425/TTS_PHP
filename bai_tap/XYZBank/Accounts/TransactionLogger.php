<?php
// Đặt trong namespace
namespace XYZBank\Accounts;

// Trait TransactionLogger để ghi log giao dịch
trait TransactionLogger
{
    // Ghi log giao dịch
    public function logTransaction(string $type, float $amount, float $newBalance): void
    {
        $date = date('Y-m-d H:i:s');
        $formattedAmount = number_format($amount, 0, ',', '.') . ' VNĐ';
        $formattedBalance = number_format($newBalance, 0, ',', '.') . ' VNĐ';
        echo "[$date] Giao dịch: $type $formattedAmount | Số dư mới: $formattedBalance\n";
    }
}
