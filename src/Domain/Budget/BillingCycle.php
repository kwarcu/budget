<?php

namespace YABA\Domain;


use Carbon\CarbonImmutable;
use YABA\Domain\Budget\Exception\StartDateCanNotBeAfterEndDateException;

class BillingCycle
{
    /** @var CarbonImmutable */
    protected $startDate;

    /** @var CarbonImmutable */
    protected $endDate;

    protected function __construct(
        CarbonImmutable $startDate,
        CarbonImmutable $endDate
    ) {
        $this->assertStartDateBeforeEndDate($startDate, $endDate);

        $this->startDate = $this->normalizeStartDate($startDate);
        $this->endDate = $this->normalizeEndDate($endDate);
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
        $billingCycle = new BillingCycle(
            $startDate,
            $endDate
        );

        return $billingCycle;
    }

    public function isAdhering(BillingCycle $anotherBillingCycle)
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
}
