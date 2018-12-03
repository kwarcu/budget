<?php declare(strict_types=1);

namespace spec\YABA\Domain\Budget;

use PhpSpec\ObjectBehavior;
use YABA\Domain\Budget\BillingCycle;
use YABA\Domain\Budget\Exception\AddedBillingCycleHasToAdhereToThePreviousOneException;
use YABA\Domain\Budget\Exception\NoMatchingBillingCycleForGivenTransactionException;
use YABA\Domain\Budget\Transaction;

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

    public function it_can_accept_a_transaction(Transaction $transaction): void
    {
        $this->addTransaction($transaction);

        $this->transactions()->shouldReturn([$transaction]);
    }

    public function it_assigns_a_transaction_to_proper_billing_cycle(
        Transaction $transaction,
        BillingCycle $properBillingCycle,
        BillingCycle $otherBillingCycle
    ): void {
        $otherBillingCycle->matchesTransaction($transaction)->willReturn(false);
        $otherBillingCycle->isAdhering($properBillingCycle)->willReturn(true);
        $properBillingCycle->assignTransaction($transaction)->shouldNotBeCalled();

        $properBillingCycle->matchesTransaction($transaction)->willReturn(true);
        $properBillingCycle->assignTransaction($transaction)->shouldBeCalledOnce();

        $this->addBillingCycle($otherBillingCycle);
        $this->addBillingCycle($properBillingCycle);

        $this->addTransaction($transaction);
    }

    public function it_throws_an_exception_if_theres_no_matching_billing_cycle(Transaction $transaction, BillingCycle $otherBillingCycle): void
    {
        $otherBillingCycle->matchesTransaction($transaction)->willReturn(false);
        $this->shouldThrow(NoMatchingBillingCycleForGivenTransactionException::class)
            ->during('addTransaction', [$transaction]);
    }

}
