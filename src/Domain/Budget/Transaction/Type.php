<?php declare(strict_types=1);

namespace YABA\Domain\Budget\Transaction;

use Money\Money;

interface Type
{
    public function assertTransactionValueIsCorrect(Money $value): void;
}
