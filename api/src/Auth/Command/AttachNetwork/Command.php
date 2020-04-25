<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

class Command
{
    /**
     * @var string
     */
    public string $id = '';

    /**
     * @var string
     */
    public string $network = '';

    /**
     * @var string
     */
    public string $identity = '';
}
