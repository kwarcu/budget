<?php declare(strict_types=1);

namespace YABA\Domain\Budget;

use Doctrine\Common\Collections\ArrayCollection;
use YABA\Domain\Budget\Exception\AddedBillingCycleHasToAdhereToThePreviousOneException;
use YABA\Domain\Budget\Exception\NoMatchingBillingCycleForGivenTransactionException;

class Budget
{
    /** @var string */
    protected $name;
    /** @var ArrayCollection|BillingCycle[] */
    protected $billingCycles;
    /** @var ArrayCollection|Transaction[] */
    protected $transactions;

    protected function __construct(
        string $name
    ) {
        $this->name = $name;
        $this->billingCycles = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public static function create(
        string $name
    ): self {
        $budget = new static(
            $name
        );

        return $budget;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function addBillingCycle(BillingCycle $billingCycle)
    {
        $this->assertNewBillingCycleIsAdhering($billingCycle);

        $this->billingCycles->add($billingCycle);
    }

    protected function assertNewBillingCycleIsAdhering(BillingCycle $billingCycle): void
    {
        if ($this->billingCycles->count() === 0) {
            return;
        }

        /** @var BillingCycle $lastBillingCycle */
        $lastBillingCycle = $this->billingCycles->last();
        if (!$lastBillingCycle->isAdhering($billingCycle)) {
            throw new AddedBillingCycleHasToAdhereToThePreviousOneException();
        }
    }

    /**
     * @return BillingCycle[]
     */
    public function billingCycles(): array
    {
        return array_values($this->billingCycles->toArray());
    }

    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions->add($transaction);

        $this->findMatchingBillingCycleAndAssignTransaction($transaction);
    }

    protected function findMatchingBillingCycleAndAssignTransaction(Transaction $transaction): void
    {
        /** @var BillingCycle $matchingCycle */
        $matchingCycle = $this->billingCycles->filter(function (BillingCycle $billingCycle) use ($transaction) {
            return $billingCycle->matchesTransaction($transaction);
        })->first();

        if (!$matchingCycle) {
            throw new NoMatchingBillingCycleForGivenTransactionException();
        }

        $matchingCycle->assignTransaction($transaction);
    }

    /**
     * @return Transaction[]
     */
    public function transactions(): array
    {
        return array_values($this->transactions->toArray());
    }
}
