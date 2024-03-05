<?php
return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'your_database',
    'port' => 3306,
    'tables' => [
        'users' => [
            'columns' => ['id', 'username', 'email', 'password'],
            'primary_key' => 'id'
        ],
    ]
];