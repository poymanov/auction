<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;

use App\Auth\Entity\User\UserRepository;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    /**
     * @var UserRepository
     */
    private UserRepository $users;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param UserRepository $users
     * @param Flusher $flusher
     */
    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByJoinConfirmToken($command->token)) {
            throw new DomainException('Incorrect token.');
        }

        $user->confirmJoin($command->token, new DateTimeImmutable());

        $this->flusher->flush();
    }
}
