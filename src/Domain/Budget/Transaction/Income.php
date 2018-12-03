<?php declare(strict_types=1);

namespace YABA\Domain\Budget\Transaction;

use Money\Money;
use YABA\Domain\Budget\Transaction\Exception\IncomeHasToHavePositiveValueException;

class Income implements Type
{
    public function assertTransactionValueIsCorrect(Money $value): void
    {
        if (!$value->isPositive()) {
            throw new IncomeHasToHavePositiveValueException();
        }
    }
}