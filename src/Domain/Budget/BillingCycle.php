<?php declare(strict_types=1);

namespace YABA\Domain\Budget;

use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use YABA\Domain\Budget\Exception\StartDateCanNotBeAfterEndDateException;
use YABA\Domain\Budget\Transaction\Exception\TransactionDateIsOutsideBillingCycleDateRangeException;

class BillingCycle
{
    /** @var CarbonImmutable */
    protected $startDate;
    /** @var CarbonImmutable */
    protected $endDate;
    /** @var ArrayCollection|Transaction[] */
    protected $transactions;

    protected function __construct(
        CarbonImmutable $startDate,
        CarbonImmutable $endDate
    ) {
        $this->assertStartDateBeforeEndDate($startDate, $endDate);

        $this->startDate = $this->normalizeStartDate($startDate);
        $this->endDate = $this->normalizeEndDate($endDate);

        $this->transactions = new ArrayCollection();
    }

    protected function assertStartDateBeforeEndDate(CarbonImmutable $startDate, CarbonImmutable $endDate): void
    {
        if ($startDate->isAfter($endDate)) {
            throw new StartDateCanNotBeAfterEndDateException();
        }
    }

    protected function normalizeStartDate(CarbonImmutable $startDate): CarbonImmutable
    {
        return $startDate->startOfDay();
    }

    protected function normalizeEndDate(CarbonImmutable $endDate): CarbonImmutable
    {
        return $endDate->endOfDay();
    }

    public static function create(
        CarbonImmutable $startDate,
        CarbonImmutable $endDate
    ): self {
        $billingCycle = new static(
            $startDate,
            $endDate
        );

        return $billingCycle;
    }

    public function isAdhering(BillingCycle $anotherBillingCycle): bool
    {
        /** @var CarbonImmutable $adheringStartDate */
        $adheringStartDate = $this->endDate()->addDay()->startOfDay();
        return $adheringStartDate->equalTo($anotherBillingCycle->startDate());
    }

    public function endDate(): CarbonImmutable
    {
        return $this->endDate;
    }

    public function startDate(): CarbonImmutable
    {
        return $this->startDate;
    }

    public function matchesTransaction(Transaction $transaction): bool
    {
        return $transaction->date()->isBetween($this->startDate(), $this->endDate());
    }

    public function assignTransaction(Transaction $transaction): void
    {
        $this->assertCycleMatchesTransaction($transaction);
        $this->transactions->add($transaction);
    }

    /**
     * @return Transaction[]
     */
    public function transactions(): array
    {
        return array_values($this->transactions->toArray());
    }

    protected function assertCycleMatchesTransaction(Transaction $transaction): void
    {
        if (!$this->matchesTransaction($transaction)) {
            throw new TransactionDateIsOutsideBillingCycleDateRangeException();
        }
    }
}
