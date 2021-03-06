<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Token
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private string $value;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $expires;

    /**
     * @param string $value
     * @param DateTimeImmutable $expires
     */
    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new DomainException('Token is invalid.');
        }

        if ($this->isExpiredTo($date)) {
            throw new DomainException('Token is expired.');
        }
    }

    /**
     * @param DateTimeImmutable $date
     * @return bool
     */
    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}
