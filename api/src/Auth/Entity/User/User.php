<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use DomainException;

class User
{
    /**
     * @var Id
     */
    private Id $id;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $date;

    /**
     * @var Email
     */
    private Email $email;

    /**
     * @var string
     */
    private string $passwordHash;

    /**
     * @var Status
     */
    private Status $status;

    /**
     * @var Token|null
     */
    private ?Token $joinConfirmToken;

    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Email $email
     * @param string $passwordHash
     * @param Token|null $joinConfirmToken
     */
    public function __construct(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        ?Token $joinConfirmToken
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = Status::wait();
        $this->passwordHash = $passwordHash;
        $this->joinConfirmToken = $joinConfirmToken;
    }

    /**
     * @param string $token
     * @param DateTimeImmutable $date
     */
    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new DomainException('Confirmation is not required.');
        }

        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return Token|null
     */
    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }
}
