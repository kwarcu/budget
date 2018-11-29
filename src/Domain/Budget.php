<?php

namespace YABA\Domain;

class Budget
{
    /** @var string  */
    private $name;
    /** @var array  */
    private $billingCycles = [];

    private function __construct(
        string $name
    ) {
        $this->name = $name;
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
        array_push($this->billingCycles, $billingCycle);
    }

    /**
     * @return BillingCycle[]
     */
    public function billingCycles(): array
    {
        return $this->billingCycles;
    }
}
