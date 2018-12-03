<?php declare(strict_types=1);

namespace YABA\Common;

use Ramsey\Uuid\Uuid;

abstract class Uuid4Id
{
    /** @var string */
    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return Uuid4Id
     * @throws \Exception Ramsey\Uuid\Uuid
     */
    public static function create(): self
    {
        $id = Uuid::uuid4()->toString();
        return new static($id);
    }

    public function id(): string
    {
        return $this->id;
    }
}