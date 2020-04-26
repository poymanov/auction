<?php

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;

interface NewEmailConfirmTokenSender
{
    /**
     * @param Email $email
     * @param Token $token
     */
    public function send(Email $email, Token $token): void;
}
