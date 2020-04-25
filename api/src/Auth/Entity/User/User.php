<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;

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
        $this->passwordHash = $passwordHash;
        $this->joinConfirmToken = $joinConfirmToken;
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
