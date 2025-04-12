<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCookieValidation' => false,
            'baseUrl' => '',
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\Courier',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                
                // Тестовый контроллер
                'test' => 'test/index',
                
                // REST API правила
                'courier' => 'courier/index',
                'courier/<id:\d+>' => 'courier/view',
                'courier/create' => 'courier/create',
                'courier/update/<id:\d+>' => 'courier/update',
                'courier/delete/<id:\d+>' => 'courier/delete',
                
                'vehicle' => 'vehicle/index',
                'vehicle/<id:\d+>' => 'vehicle/view',
                'vehicle/create' => 'vehicle/create',
                'vehicle/update/<id:\d+>' => 'vehicle/update',
                'vehicle/delete/<id:\d+>' => 'vehicle/delete',
                
                'request' => 'request/index',
                'request/<id:\d+>' => 'request/view',
                'request/create' => 'request/create',
                'request/update/<id:\d+>' => 'request/update',
                'request/delete/<id:\d+>' => 'request/delete',
                
                // Общие правила для контроллеров
                '<controller>/<action>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
]; 