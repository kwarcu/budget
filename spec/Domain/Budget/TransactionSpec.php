<?php declare(strict_types=1);

namespace spec\YABA\Domain\Budget;

use Carbon\CarbonImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use YABA\Domain\Budget\AccountId;
use YABA\Domain\Budget\Transaction;

class TransactionSpec extends ObjectBehavior
{
    public function it_asserts_that_income_can_only_be_positive(AccountId $accountId, CarbonImmutable $date): void
    {
        $this->beConstructedThrough('create', [
            $accountId,
            $date,
            $type = new Transaction\Income(),
            $value = new Money(-100, new Currency('PLN'))
        ]);

        $this->shouldThrow(Transaction\Exception\IncomeHasToHavePositiveValueException::class)
            ->duringInstantiation();
    }

    public function it_asserts_that_outcome_can_only_be_negative(AccountId $accountId, CarbonImmutable $date): void
    {
        $this->beConstructedThrough('create', [
            $accountId,
            $date,
            $type = new Transaction\Outcome(),
            $value = new Money(100, new Currency('PLN'))
        ]);

        $this->shouldThrow(Transaction\Exception\OutcomeHasToHaveNegativeValueException::class)
            ->duringInstantiation();
    }

    public function it_can_be_instantiated(): void
    {
        $this->shouldBeAnInstanceOf(Transaction::class);
    }
}
