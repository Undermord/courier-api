<?php

namespace api\controllers;

use Yii;
use common\models\CourierRequest;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class RequestController extends BaseController
{
    public $modelClass = CourierRequest::class;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        
        // Настраиваем подготовку провайдера данных с фильтрацией
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        // Настраиваем действие удаления для мягкого удаления
        $actions['delete'] = [
            'class' => 'yii\rest\DeleteAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'findModel' => function($id) {
                $model = CourierRequest::findOne($id);
                if ($model === null) {
                    throw new NotFoundHttpException('Запрашиваемая страница не существует.');
                }
                $model->deleted = true;
                if ($model->save()) {
                    return $model;
                }
                return null;
            }
        ];
        
        return $actions;
    }

    /**
     * Подготавливает провайдер данных с фильтрацией и сортировкой
     */
    public function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        
        $query = CourierRequest::find()->where(['deleted' => false]);
        
        // Применяем фильтр по courier_id, если он указан
        if (isset($requestParams['courier_id'])) {
            $query->andWhere(['courier_id' => $requestParams['courier_id']]);
        }
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'courier_id',
                    'status',
                    'created_at',
                ],
            ],
        ]);
    }
} 