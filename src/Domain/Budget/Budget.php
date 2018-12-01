<?php

namespace YABA\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use YABA\Domain\Budget\Exception\AddedBillingCycleHasToAdhereToThePreviousOneException;

class Budget
{
    /** @var string */
    protected $name;
    /** @var ArrayCollection|BillingCycle[] */
    protected $billingCycles;

    protected function __construct(
        string $name
    ) {
        $this->name = $name;
        $this->billingCycles = new ArrayCollection();
    }

    public static function create(
        string $name
    ): self {
        $budget = new Budget(
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
}
