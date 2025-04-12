<?php

// настройка окружения для тестов
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/../../common/config/bootstrap.php';
require_once __DIR__ . '/../config/bootstrap.php';

// загрузка конфигурации приложения
$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

// создание экземпляра приложения
new \yii\web\Application($config); 