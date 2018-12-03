<?php declare(strict_types=1);

namespace YABA\Domain\Budget\Transaction;

use Money\Money;
use YABA\Domain\Budget\Transaction\Exception\OutcomeHasToHaveNegativeValueException;

class Outcome implements Type
{
    public function assertTransactionValueIsCorrect(Money $value): void
    {
        if (!$value->isNegative()) {
            throw new OutcomeHasToHaveNegativeValueException();
        }
    }
}
