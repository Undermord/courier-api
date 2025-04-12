<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\Cors;

/**
 * TestController реализует тестовый REST API для проверки работоспособности
 */
class TestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Добавляем CORS фильтр
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];
        
        // Настраиваем формат ответа
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => \yii\web\Response::FORMAT_JSON,
        ];
        
        return $behaviors;
    }

    /**
     * Тестовый метод для проверки работоспособности API
     */
    public function actionIndex()
    {
        return [
            'status' => 'success',
            'message' => 'API работает!',
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => [
                'couriers' => 3,
                'vehicles' => 3,
                'requests' => 2,
            ],
        ];
    }
} 