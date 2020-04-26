<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordResetTokenSender;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;

class Handler
{
    /**
     * @var UserRepository
     */
    private UserRepository $users;

    /**
     * @var Tokenizer
     */
    private Tokenizer $tokenizer;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @var PasswordResetTokenSender
     */
    private PasswordResetTokenSender $sender;

    /**
     * @param UserRepository $users
     * @param Tokenizer $tokenizer
     * @param Flusher $flusher
     * @param PasswordResetTokenSender $sender
     */
    public function __construct(
        UserRepository $users,
        Tokenizer $tokenizer,
        Flusher $flusher,
        PasswordResetTokenSender $sender
    ) {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        $user = $this->users->getByEmail($email);

        $date = new DateTimeImmutable();

        $user->requestPasswordReset(
            $token = $this->tokenizer->generate($date),
            $date
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
