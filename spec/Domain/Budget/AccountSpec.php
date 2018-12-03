<?php declare(strict_types=1);

namespace spec\YABA\Domain\Budget;

use PhpSpec\ObjectBehavior;
use YABA\Domain\Budget\Account;
use YABA\Domain\Budget\AccountId;

class AccountSpec extends ObjectBehavior
{
    const TEST_ACCOUNT_ID = 'test account id';
    const TEST_ACCOUNT_NAME = 'test account';

    public function let(): void
    {
        $this->beConstructedThrough('create', [
            new AccountId(self::TEST_ACCOUNT_ID),
            self::TEST_ACCOUNT_NAME,
        ]);
    }

    public function it_can_be_instantiated(): void
    {
        $this->shouldBeAnInstanceOf(Account::class);
    }
}
