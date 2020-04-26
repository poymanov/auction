<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Service\PasswordHasher;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $hasher = $this->createHasher(true, $hash = 'new-hash');

        $user->changePassword(
            'old-password',
            'new-password',
            $hasher
        );

        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testWrongCurrent(): void
    {
        $user = (new UserBuilder())->active()->build();

        $hasher = $this->createHasher(false, $hash = 'new-hash');

        $this->expectExceptionMessage('Incorrect current password.');
        $user->changePassword(
            'old-password',
            'new-password',
            $hasher
        );
    }

    public function testByNetwork(): void
    {
        $user = (new UserBuilder())->viaNetwork()->build();

        $hasher = $this->createHasher(false, $hash = 'new-hash');

        $this->expectExceptionMessage('User does not have an old password.');
        $user->changePassword(
            'old-password',
            'new-password',
            $hasher
        );
    }

    /**
     * @param bool $valid
     * @param string $hash
     * @return PasswordHasher
     */
    private function createHasher(bool $valid, string $hash): PasswordHasher
    {
        $hasher = $this->createStub(PasswordHasher::class);
        $hasher->method('validate')->willReturn($valid);
        $hasher->method('hash')->willReturn($hash);

        return $hasher;
    }
}
