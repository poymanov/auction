<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DomainException;

interface UserRepository
{
    /**
     * @param Email $email
     * @return bool
     */
    public function hasByEmail(Email $email): bool;

    /**
     * @param NetworkIdentity $identity
     * @return bool
     */
    public function hasByNetwork(NetworkIdentity $identity): bool;

    /**
     * @param string $token
     * @return User|null
     */
    public function findByJoinConfirmToken(string $token): ?User;

    /**
     * @param string $token
     * @return User|null
     */
    public function findByPasswordResetToken(string $token): ?User;

    /**
     * @param string $token
     * @return User|null
     */
    public function findByNewEmailToken(string $token): ?User;

    /**
     * @param Id $id
     * @return User
     * @throws DomainException
     */
    public function get(Id $id): User;

    /**
     * @param Email $email
     * @return User
     * @throws DomainException
     */
    public function getByEmail(Email $email): User;

    /**
     * @param User $user
     */
    public function add(User $user): void;

    /**
     * @param User $user
     */
    public function remove(User $user): void;
}
