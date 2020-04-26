<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

class Command
{
    /**
     * @var string
     */
    public string $id = '';

    /**
     * @var string
     */
    public string $current = '';

    /**
     * @var string
     */
    public string $new = '';
}
