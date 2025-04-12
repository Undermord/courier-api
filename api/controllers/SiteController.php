<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\Cors;
use yii\helpers\Url;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@app/views/site/error',
            ],
        ];
    }

    /**
     * Отображает главную страницу API.
     *
     * @return array
     */
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        return [
            'name' => 'Courier and Vehicle Management API',
            'version' => '1.0.0',
            'description' => 'API для управления курьерами, транспортными средствами и заявками',
            'endpoints' => [
                '/courier' => 'Управление курьерами',
                '/vehicle' => 'Управление транспортом',
                '/request' => 'Управление заявками'
            ],
            'documentation' => '/swagger-ui.html'
        ];
    }
} 