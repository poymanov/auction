<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
    }

    /**
     * @return Id
     */
    public static function generate(): Id
    {
        return new Id(Uuid::uuid4()->toString());
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
