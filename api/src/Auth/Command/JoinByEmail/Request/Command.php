<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

class Command
{
    /**
     * @var string
     */
    public string $email = '';

    /**
     * @var string
     */
    public string $password = '';
}
