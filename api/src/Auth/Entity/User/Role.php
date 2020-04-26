<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Role
{
    public const USER = 'user';

    public const ADMIN = 'admin';

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
            self::USER,
            self::ADMIN
        ]);

        $this->name = $name;
    }

    /**
     * @return Role
     */
    public static function user(): Role
    {
        return new Role(self::USER);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
