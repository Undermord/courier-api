<?php

namespace api\controllers;

use Yii;
use common\models\Courier;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class CourierController extends BaseController
{
    public $modelClass = Courier::class;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        
        // Настраиваем подготовку провайдера данных с фильтрацией
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        return $actions;
    }

    /**
     * Подготавливает провайдер данных с фильтрацией
     */
    public function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        
        $query = Courier::find();
        
        // Применяем фильтр по роли, если он указан
        if (isset($requestParams['role'])) {
            $query->andWhere(['role' => $requestParams['role']]);
        }
        
        return new ActiveDataProvider([
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
} 