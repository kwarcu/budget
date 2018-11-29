<?php

namespace spec\YABA\Domain;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use YABA\Domain\BillingCycle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BillingCycleSpec extends ObjectBehavior
{
    const TEST_START_DATE = '2018-01-01';
    const TEST_END_DATE = '2018-01-30';

    /**
     * @throws \Exception
     */
    public function let(): void
    {
        $this->beConstructedThrough('create', [
            new CarbonImmutable(self::TEST_START_DATE, new \DateTimeZone('UTC')),
            new CarbonImmutable(self::TEST_END_DATE, new \DateTimeZone('UTC')),
        ]);

    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BillingCycle::class);
    }

    /**
     * @throws \Exception
     */
    public function it_has_period_dates(): void
    {
        $startDate = new CarbonImmutable(self::TEST_START_DATE, new \DateTimeZone('UTC'));
        $endDate = new CarbonImmutable(self::TEST_END_DATE, new \DateTimeZone('UTC'));

        $this->startDate()->shouldBeLike($startDate->startOfDay());
        $this->endDate()->shouldBeLike($endDate->endOfDay());
    }

}
