<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

class Command
{
    /**
     * @var string
     */
    public string $email = '';

    /**
     * @var string
     */
    public string $network = '';

    /**
     * @var string
     */
    public string $identity = '';
}
