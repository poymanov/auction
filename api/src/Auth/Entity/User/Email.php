<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Email
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
        Assert::notEmpty($value);
        Assert::email($value);
        $this->value = mb_strtolower($value);
    }

    /**
     * @param Email $other
     * @return bool
     */
    public function isEqualTo(Email $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
