<?php

return [
    'settings' => [
        // Slim
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,

        // Database
        'pdo' => [
            'dsn' => 'pgsql:host=localhost;dbname=horusapp;port=5432',
            'username' => 'admin',
            'password' => 'senha123',
        ],
    ],
];
