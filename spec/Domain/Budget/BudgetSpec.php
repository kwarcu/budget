<?php

namespace spec\YABA\Domain;

use PhpSpec\ObjectBehavior;
use YABA\Domain\Budget\BillingCycle;
use YABA\Domain\Budget\Exception\AddedBillingCycleHasToAdhereToThePreviousOneException;

class BudgetSpec extends ObjectBehavior
{
    const TEST_NAME = 'Test Budget Name';

    public function let(): void
    {
        $this->beConstructedThrough('create', [
            self::TEST_NAME,
        ]);

        $this->name()->shouldEqual(self::TEST_NAME);
    }

    public function it_can_have_billing_cycle_added(BillingCycle $billingCycle): void
    {
        $this->addBillingCycle($billingCycle);
        $this->billingCycles()->shouldReturn([$billingCycle]);
    }

    public function it_can_only_accept_adhering_billing_cycles(
        BillingCycle $firstBillingCycle,
        BillingCycle $adheringBillingCycle,
        BillingCycle $nonAdheringBillingCycle
    ): void {
        $firstBillingCycle->isAdhering($adheringBillingCycle)->willReturn(true);
        $adheringBillingCycle->isAdhering($nonAdheringBillingCycle)->willReturn(false);

        $this->addBillingCycle($firstBillingCycle);
        $this->addBillingCycle($adheringBillingCycle);

        $this->shouldThrow(AddedBillingCycleHasToAdhereToThePreviousOneException::class)
            ->during('addBillingCycle', [$nonAdheringBillingCycle]);
    }

}
