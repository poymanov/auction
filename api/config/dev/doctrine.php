<?php

declare(strict_types=1);

use App\Data\Doctrine\FixDefaultSchemaSubscriber;

return [
    'config' => [
        'doctrine' => [
            'dev_mode' => false,
            'cache_dir' => null,
            'proxy_dir' => __DIR__ . '/../../var/cache/' . PHP_SAPI . '/doctrine/proxy',
            'subscribers' => [
                FixDefaultSchemaSubscriber::class,
            ],
        ],
    ]
];
