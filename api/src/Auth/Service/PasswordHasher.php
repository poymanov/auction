<?php

namespace App\Auth\Service;

use RuntimeException;
use Webmozart\Assert\Assert;

class PasswordHasher
{
    /**
     * @var int
     */
    private int $memoryCost;

    /**
     * @param int $memoryCost
     */
    public function __construct(int $memoryCost = PASSWORD_ARGON2_DEFAULT_MEMORY_COST)
    {
        $this->memoryCost = $memoryCost;
    }

    /**
     * @param string $password
     * @return string
     */
    public function hash(string $password): string
    {
        Assert::notEmpty($password);

        /** @var string|false|null $hash */
        $hash = password_hash($password, PASSWORD_ARGON2I, ['memory_cost' => $this->memoryCost]);

        if ($hash === null) {
            throw new RuntimeException('Invalid hash algorithm');
        }

        if ($hash === false) {
            throw new RuntimeException('Unable to generate hash.');
        }

        return $hash;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
