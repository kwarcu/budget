<?php

namespace YABA\Domain;



use Carbon\CarbonImmutable;

class BillingCycle
{
    /** @var CarbonImmutable  */
    private $startDate;

    /** @var CarbonImmutable  */
    private $endDate;

    private function __construct(
        CarbonImmutable $startDate,
        CarbonImmutable $endDate
    ) {
        $this->startDate = $startDate->startOfDay();
        $this->endDate = $endDate->endOfDay();
    }

    public static function create(
        CarbonImmutable $startDate,
        CarbonImmutable $endDate
    ): self {
        $billingCycle = new BillingCycle(
            $startDate,
            $endDate
        );

        return $billingCycle;
    }

    public function startDate(): CarbonImmutable
    {
        return $this->startDate;
    }

    public function EndDate(): CarbonImmutable
    {
        return $this->endDate;
    }
}
