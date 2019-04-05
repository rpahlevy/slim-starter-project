<?php

// load .env
$dotenv = new Dotenv\Dotenv(__DIR__ .'/../../');
$dotenv->load();

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'debugMode' => true,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../View/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'riri',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database
        'db' => [
            'medoo' => [
                'database_type' => 'mysql',
                'server'        => $_ENV['DBHOST'],
                'username'      => $_ENV['DBUSER'],
                'password'      => $_ENV['DBPASS'],
                'database_name' => $_ENV['DBNAME'],
                'port'          => $_ENV['DBPORT'],

                // 'prefix'        => 'tbl_',
            ],
            'mysql' => [
                'host'      => $_ENV['DBHOST'],
                'user'      => $_ENV['DBUSER'],
                'pass'      => $_ENV['DBPASS'],
                'dbname'    => $_ENV['DBNAME'],
                'port'      => $_ENV['DBPORT']
            ]
        ],
    ],
];
