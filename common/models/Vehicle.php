<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Модель для таблицы "vehicles".
 *
 * @property int $id
 * @property int $courier_id
 * @property string $type
 * @property string $serial_number
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Courier $courier
 * @property CourierRequest[] $courierRequests
 */
class Vehicle extends ActiveRecord
{
    const TYPE_CAR = 'car';
    const TYPE_SCOOTER = 'scooter';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vehicles}}';
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
            [['courier_id', 'type', 'serial_number'], 'required'],
            [['courier_id'], 'integer'],
            [['type'], 'in', 'range' => [self::TYPE_CAR, self::TYPE_SCOOTER]],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['courier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Courier::class, 'targetAttribute' => ['courier_id' => 'id']],
            ['courier_id', 'validateVehicleLimit'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Проверяет, что у курьера не может быть больше одного транспортного средства каждого типа
     */
    public function validateVehicleLimit($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $count = self::find()
                ->where(['courier_id' => $this->courier_id, 'type' => $this->type])
                ->andWhere(['<>', 'id', $this->id ?? 0])
                ->count();

            if ($count > 0) {
                $this->addError($attribute, 'У курьера уже есть транспортное средство этого типа.');
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
            'type' => 'Тип',
            'serial_number' => 'Серийный номер',
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
     * Получает запрос для связи с CourierRequests.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourierRequests()
    {
        return $this->hasMany(CourierRequest::class, ['vehicle_id' => 'id']);
    }
} 