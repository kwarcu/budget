<?php declare(strict_types=1);

namespace spec\YABA\Domain\Budget;

use Carbon\CarbonImmutable;
use PhpSpec\ObjectBehavior;
use YABA\Domain\Budget\BillingCycle;
use YABA\Domain\Budget\Transaction;

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

    /**
     * @throws \Exception
     */
    public function it_normalizes_dates_times(): void
    {
        $this->beConstructedThrough('create', [
            new CarbonImmutable(self::TEST_START_DATE . ' 12:00:00', new \DateTimeZone('UTC')),
            new CarbonImmutable(self::TEST_END_DATE . ' 13:00:00', new \DateTimeZone('UTC')),
        ]);

        $this->startDate()
            ->shouldBeLike((new CarbonImmutable(self::TEST_START_DATE, new \DateTimeZone('UTC')))->startOfDay());

        $this->endDate()
            ->shouldBeLike((new CarbonImmutable(self::TEST_END_DATE, new \DateTimeZone('UTC')))->endOfDay());
    }

    public function it_refuses_end_date_before_start_date(): void
    {
        /** @var CarbonImmutable $someDate */
        $someDate = CarbonImmutable::now();

        $this->shouldThrow(\YABA\Domain\Budget\Exception\StartDateCanNotBeAfterEndDateException::class)
            ->during('create', [
                $someDate,
                $someDate->subDay(),
            ]);
    }

    public function it_can_determine_if_another_cycle_is_adhering(BillingCycle $anotherBillingCycle): void
    {
        $anotherBillingCycle->startDate()->willReturn($this->endDate()->addDay()->startOfDay());
        $this->isAdhering($anotherBillingCycle)->shouldEqual(true);

        $anotherBillingCycle->startDate()->willReturn($this->endDate()->addDays(2)->startOfDay());
        $this->isAdhering($anotherBillingCycle)->shouldEqual(false);
    }

    public function it_can_determine_if_a_transaction_belongs_to_it(Transaction $transaction): void
    {
        $transaction->date()->willReturn($this->startDate()->addDay());
        $this->matchesTransaction($transaction)->shouldReturn(true);

        $transaction->date()->willReturn($this->endDate()->addDay());
        $this->matchesTransaction($transaction)->shouldReturn(false);
    }

    public function it_can_have_a_transaciton_assigned(Transaction $transaction): void
    {
        $transaction->date()->willReturn($this->startDate()->addDay());

        $this->assignTransaction($transaction);
        $this->transactions()->shouldReturn([$transaction]);

        $transaction->date()->willReturn($this->endDate()->addDay());
        $this->shouldThrow(Transaction\Exception\TransactionDateIsOutsideBillingCycleDateRangeException::class)
            ->during('assignTransaction', [$transaction]);
    }

}
