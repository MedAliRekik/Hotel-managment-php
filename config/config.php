<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'dbname' => 'hotel_management',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_url' => '/index.php',
        'upload_dir' => __DIR__ . '/../uploads/rooms/',
        'max_upload_size' => 2 * 1024 * 1024,
    ],
];
