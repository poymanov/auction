<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

class Command
{
    /**
     * @var string
     */
    public string $id = '';

    /**
     * @var string
     */
    public string $email = '';
}
