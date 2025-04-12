<?php

namespace api\controllers;

use Yii;
use common\models\Vehicle;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class VehicleController extends BaseController
{
    public $modelClass = Vehicle::class;

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
     * Подготавливает провайдер данных с фильтрацией и расширенной функциональностью
     */
    public function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        
        $query = Vehicle::find();
        
        // Применяем фильтр по courier_id, если он указан
        if (isset($requestParams['courier_id'])) {
            $query->andWhere(['courier_id' => $requestParams['courier_id']]);
        }
        
        // Применяем фильтр по типу, если он указан
        if (isset($requestParams['type'])) {
            $query->andWhere(['type' => $requestParams['type']]);
        }
        
        // Добавляем связь с courier, если указан параметр expand
        if (isset($requestParams['expand']) && $requestParams['expand'] === 'courier') {
            $query->with('courier');
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

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        
        // Добавляем данные о курьере, если указан параметр expand
        if (Yii::$app->request->get('expand') === 'courier') {
            if (is_array($result)) {
                foreach ($result as &$item) {
                    if (isset($item['courier'])) {
                        $item['courier'] = $item['courier'];
                    }
                }
            } elseif (is_object($result) && isset($result->courier)) {
                $result->courier = $result->courier;
            }
        }
        
        return $result;
    }
} 