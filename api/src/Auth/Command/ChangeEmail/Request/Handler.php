<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\NewEmailConfirmTokenSender;
use App\Auth\Service\Tokenizer;
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
     * @var Tokenizer
     */
    private Tokenizer $tokenizer;

    /**
     * @var NewEmailConfirmTokenSender
     */
    private NewEmailConfirmTokenSender $sender;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @param UserRepository $users
     * @param Tokenizer $tokenizer
     * @param NewEmailConfirmTokenSender $sender
     * @param Flusher $flusher
     */
    public function __construct(
        UserRepository $users,
        Tokenizer $tokenizer,
        NewEmailConfirmTokenSender $sender,
        Flusher $flusher
    ) {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('Email is already in use.');
        }

        $date = new DateTimeImmutable();

        $user->requestEmailChanging(
            $token = $this->tokenizer->generate($date),
            $date,
            $email
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
