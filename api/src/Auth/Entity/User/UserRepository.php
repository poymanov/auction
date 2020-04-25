<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

interface UserRepository
{
    /**
     * @param Email $email
     * @return bool
     */
    public function hasByEmail(Email $email): bool;

    /**
     * @param User $user
     */
    public function add(User $user): void;
}
