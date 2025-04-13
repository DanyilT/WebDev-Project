<?php

return [
    'host' => 'localhost',
    'dbname' => '<database_name>',
    'user' => '<database_user>',
    'password' => '<database_password>',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
