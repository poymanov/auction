<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

class Command
{
    /**
     * @var string
     */
    public string $token = '';

    /**
     * @var string
     */
    public string $password = '';
}
