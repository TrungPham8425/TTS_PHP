<?php

namespace XYZBank\Accounts;

class Bank
{
    // Tong so tai khoan duoc tao
    private static $totalAccounts = 0;

    // Ten ngan hang
    private const Bank_name = "Ngân hàng XYZ";

    // Tang so tai khoan moi khi tai khoan moi duoc tao
    public static function addAccountNumber()
    {
        self::$totalAccounts++;
    }

    // Lay ra tong so tai khoan
    public static function getTotalAccounts()
    {
        return self::$totalAccounts;
    }

    // Lay ra ten ngan hang
    public static function getBankName()
    {
        return self::Bank_name;
    }
}
