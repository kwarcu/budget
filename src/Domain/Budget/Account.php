<?php declare(strict_types=1);

namespace YABA\Domain\Budget;

class Account
{
    /** @var AccountId */
    protected $id;
    /** @var string */
    protected $name;

    protected function __construct(
        AccountId $id,
        string $name
    ) {

        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param AccountId|null $accountId
     * @param string $name
     * @return Account
     * @throws \Exception Ramsey\Uuid\Uuid
     */
    public static function create(
        ?AccountId $accountId = null,
        string $name
    ): self {
        $id = $accountId ?? AccountId::create();

        return new static($id, $name);

    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
