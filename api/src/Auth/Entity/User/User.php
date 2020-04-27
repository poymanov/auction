<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Auth\Service\PasswordHasher;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="auth_users")
 */
class User
{
    /**
     * @ORM\Column(type="auth_user_id")
     * @ORM\Id
     * @var Id
     */
    private Id $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $date;

    /**
     * @ORM\Column(type="auth_user_email", unique=true)
     * @var Email
     */
    private Email $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private ?string $passwordHash = null;

    /**
     * @ORM\Column(type="auth_user_status", length=16)
     * @var Status
     */
    private Status $status;

    /**
     * @ORM\Embedded(class="Token")
     * @var Token|null
     */
    private ?Token $joinConfirmToken = null;

    /**
     * @ORM\Embedded(class="Token")
     * @var Token|null
     */
    private ?Token $passwordResetToken = null;

    /**
     * @ORM\Column(type="auth_user_email", nullable=true)
     * @var Email|null
     */
    private ?Email $newEmail = null;

    /**
     * @ORM\Embedded(class="Token")
     * @var Token|null
     */
    private ?Token $newEmailToken = null;

    /**
     * @ORM\Column(type="auth_user_role", length=16)
     * @var Role
     */
    private Role $role;

    /**
     * @ORM\OneToMany(targetEntity="UserNetwork", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     * @var Collection
     */
    private Collection $networks;

    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Email $email
     * @param Status $status
     */
    private function __construct(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        Status $status
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Email $email
     * @param Network $network
     * @return User
     */
    public static function joinByNetwork(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        Network $network
    ): User {
        $user = new User($id, $date, $email, Status::active());
        $user->networks->add(new UserNetwork($user, $network));

        return $user;
    }

    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Email $email
     * @param string $passwordHash
     * @param Token $joinConfirmToken
     * @return User
     */
    public static function requestJoinByEmail(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        Token $joinConfirmToken
    ): User {
        $user = new User($id, $date, $email, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->joinConfirmToken = $joinConfirmToken;

        return $user;
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
     * @param Network $network
     */
    public function attachNetwork(Network $network): void
    {
        /** @var UserNetwork $existing */
        foreach ($this->networks as $existing) {
            if ($existing->getNetwork()->isEqualTo($network)) {
                throw new DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new UserNetwork($this, $network));
    }

    /**
     * @param Token $token
     * @param DateTimeImmutable $date
     */
    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }

        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested.');
        }

        $this->passwordResetToken = $token;
    }

    /**
     * @param string $token
     * @param DateTimeImmutable $date
     * @param string $hash
     */
    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }

        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $hash;
    }

    /**
     * @param string $current
     * @param string $new
     * @param PasswordHasher $hasher
     */
    public function changePassword(string $current, string $new, PasswordHasher $hasher): void
    {
        if ($this->passwordHash === null) {
            throw new DomainException('User does not have an old password.');
        }

        if (!$hasher->validate($current, $this->passwordHash)) {
            throw new DomainException('Incorrect current password.');
        }

        $this->passwordHash = $hasher->hash($new);
    }

    /**
     * @param Token $token
     * @param DateTimeImmutable $date
     * @param Email $email
     */
    public function requestEmailChanging(Token $token, DateTimeImmutable $date, Email $email): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }

        if ($this->email->isEqualTo($email)) {
            throw new DomainException('Email is already same.');
        }

        if ($this->newEmailToken !== null && !$this->newEmailToken->isExpiredTo($date)) {
            throw new DomainException('Changing is already requested.');
        }

        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token, DateTimeImmutable $date): void
    {
        if ($this->newEmail === null || $this->newEmailToken === null) {
            throw new DomainException('Changing is not requested.');
        }

        $this->newEmailToken->validate($token, $date);
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    /**
     * @param Role $role
     */
    public function changeRole(Role $role): void
    {
        $this->role = $role;
    }

    public function remove(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('Unable to remove active user.');
        }
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
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getPasswordHash(): ?string
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

    /**
     * @return Token|null
     */
    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    /**
     * @return Email|null
     */
    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    /**
     * @return Token|null
     */
    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        /** @var Network[] */
        return $this->networks->map(static function ($network) {
            /** @var UserNetwork $network */
            return $network->getNetwork();
        })->toArray();
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
        if ($this->joinConfirmToken && $this->joinConfirmToken->isEmpty()) {
            $this->joinConfirmToken = null;
        }

        if ($this->passwordResetToken && $this->passwordResetToken->isEmpty()) {
            $this->passwordResetToken = null;
        }

        if ($this->newEmailToken && $this->newEmailToken->isEmpty()) {
            $this->newEmailToken = null;
        }
    }
}
