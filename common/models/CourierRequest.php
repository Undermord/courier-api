<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Модель для таблицы "courier_requests".
 *
 * @property int $id
 * @property int $courier_id
 * @property int $vehicle_id
 * @property string $status
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Courier $courier
 * @property Vehicle $vehicle
 */
class CourierRequest extends ActiveRecord
{
    const STATUS_STARTED = 'started';
    const STATUS_HOLDED = 'holded';
    const STATUS_FINISHED = 'finished';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%courier_requests}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['courier_id', 'vehicle_id', 'status'], 'required'],
            [['courier_id', 'vehicle_id'], 'integer'],
            [['status'], 'in', 'range' => [self::STATUS_STARTED, self::STATUS_HOLDED, self::STATUS_FINISHED]],
            [['deleted'], 'boolean'],
            [['courier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Courier::class, 'targetAttribute' => ['courier_id' => 'id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::class, 'targetAttribute' => ['vehicle_id' => 'id']],
            ['courier_id', 'validateActiveRequests'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Проверяет, что у курьера не может быть больше одной активной заявки
     */
    public function validateActiveRequests($attribute, $params)
    {
        if (!$this->hasErrors() && $this->status === self::STATUS_STARTED) {
            $count = self::find()
                ->where(['courier_id' => $this->courier_id, 'status' => self::STATUS_STARTED])
                ->andWhere(['<>', 'id', $this->id ?? 0])
                ->andWhere(['deleted' => false])
                ->count();

            if ($count > 0) {
                $this->addError($attribute, 'У курьера уже есть активная заявка.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'courier_id' => 'ID Курьера',
            'vehicle_id' => 'ID Транспорта',
            'status' => 'Статус',
            'deleted' => 'Удалено',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Получает запрос для связи с Courier.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourier()
    {
        return $this->hasOne(Courier::class, ['id' => 'courier_id']);
    }

    /**
     * Получает запрос для связи с Vehicle.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::class, ['id' => 'vehicle_id']);
    }
} 