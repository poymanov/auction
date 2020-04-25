<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class Token
{
    /**
     * @var string
     */
    private string $value;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $expires;

    /**
     * @param string $value
     * @param DateTimeImmutable $expires
     */
    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }
}
