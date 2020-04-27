<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Status
{
    public const WAIT = 'wait';

    public const ACTIVE = 'active';

    /**
     * @var string
     */
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::WAIT,
            self::ACTIVE,
        ]);

        $this->name = $name;
    }

    /**
     * @return Status
     */
    public static function wait(): Status
    {
        return new Status(self::WAIT);
    }

    /**
     * @return Status
     */
    public static function active(): Status
    {
        return new Status(self::ACTIVE);
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
