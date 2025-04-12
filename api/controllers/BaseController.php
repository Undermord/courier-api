<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\AccessControl;
use common\models\Courier;
use yii\web\Response;

class BaseController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Добавляем формат ответа
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON,
        ];

        // Добавляем CORS фильтр
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        // Добавляем аутентификацию по API ключу
        $behaviors['authenticator'] = [
            'class' => HttpHeaderAuth::class,
            'header' => 'X-Api-Key',
            'optional' => ['index', 'view', 'options'], // Некоторые методы могут быть доступны без авторизации
        ];

        // Добавляем контроль доступа
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['?', '@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete'],
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $action) {
                        $courier = Courier::findOne(Yii::$app->user->id);
                        if (!$courier) {
                            return false;
                        }

                        // Разрешаем POST, PUT, DELETE только для роли main
                        return $courier->role === Courier::ROLE_MAIN;
                    },
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        
        // Customize the data provider preparation with additional parameters
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        return $actions;
    }

    /**
     * Prepares the data provider that should return the requested collection of models.
     */
    public function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;
        
        $query = $modelClass::find();
        
        return new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // Настройка обработки ошибок для возврата в формате JSON
        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            
            if ($response->data !== null && $response->statusCode >= 400) {
                $response->data = [
                    'status' => 'error',
                    'message' => $response->data['message'] ?? 'Произошла ошибка',
                ];
            }
        });
    }
} 