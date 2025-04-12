<?php
return [
    'id' => 'app-api-tests',
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\Courier',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
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
]; 