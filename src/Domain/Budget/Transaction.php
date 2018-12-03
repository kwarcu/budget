<?php declare(strict_types=1);

namespace YABA\Domain\Budget;

use Carbon\CarbonImmutable;
use Money\Money;
use YABA\Domain\Budget\Transaction\Type;

class Transaction
{
    /** @var AccountId */
    private $accountId;
    /** @var CarbonImmutable */
    private $date;
    /** @var Type */
    private $type;
    /** @var Money */
    private $value;

    protected function __construct(
        AccountId $accountId,
        CarbonImmutable $date,
        Type $type,
        Money $value
    ) {
        $type->assertTransactionValueIsCorrect($value);

        $this->accountId = $accountId;
        $this->date = $date;
        $this->type = $type;
        $this->value = $value;
    }

    public static function create(
        AccountId $accountId,
        CarbonImmutable $date,
        Type $type,
        Money $value
    ): self {
        return new static($accountId, $date, $type, $value);
    }

    public function accountId(): AccountId
    {
        return $this->accountId;
    }

    public function date(): CarbonImmutable
    {
        return $this->date;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function value(): Money
    {
        return $this->value;
    }
}