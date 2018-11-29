<?php

namespace spec\YABA\Domain;

use YABA\Domain\BillingCycle;
use YABA\Domain\Budget;
use PhpSpec\ObjectBehavior;

class BudgetSpec extends ObjectBehavior
{
    const TEST_NAME = 'Test Budget Name';

    public function let(): void
    {
        $this->beConstructedThrough('create', [
            self::TEST_NAME,
        ]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Budget::class);
    }

    public function it_has_name(): void
    {
        $this->name()->shouldBe(self::TEST_NAME);
    }

    public function it_can_have_billing_cycle_added(BillingCycle $billingCycle): void
    {
        $this->addBillingCycle($billingCycle);
        $this->billingCycles()->shouldReturn([$billingCycle]);

    }

}
