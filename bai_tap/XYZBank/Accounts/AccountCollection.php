<?php

namespace XYZBank\Accounts;

//  Lớp AccountCollection

class AccountCollection implements \IteratorAggregate
{
    // Danh sach tai khoan
    private array $accounts = [];
    // them tai khoan moi
    public function addAccount(BankAccount $account): void
    {
        $this->accounts[] = $account;
    }

    //Duyet danh sach tai khoan
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->accounts);
    }

    // loc tai khoan co so du lon hon hoac bang nguong
    public function filterHighBalanceAccounts(float $threshold = 10000000): array
    {
        return array_filter($this->accounts, function ($account) use ($threshold) {
            return $account->getBalance() >= $threshold;
        });
    }
}
