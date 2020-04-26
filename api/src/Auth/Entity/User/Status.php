<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

class Status
{
    private const WAIT = 'wait';

    private const ACTIVE = 'active';

    /**
     * @var string
     */
    private string $name;

    /**
     * @param string $name
     */
    private function __construct(string $name)
    {
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
}
