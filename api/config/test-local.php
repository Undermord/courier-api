<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=courier_service',
            'username' => 'yii',
            'password' => 'yii',
            'charset' => 'utf8mb4',
        ],
    ],
]; 