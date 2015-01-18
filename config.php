<?php
return [
    'projectName' => 'База E-mail адресов',
    'siteUrl' => 'http://127.0.0.1:8000/sendMail',
    'db' => [
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'emails',
        'charset'  => 'utf8',
    ],
    'view' => new \Slim\Views\Twig(),
    'templates.path' => __DIR__ . '/templates',
    'dir1' => 'folder_input',
    'dir2' => 'folder_output',
];
