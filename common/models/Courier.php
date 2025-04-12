<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * Модель для таблицы "couriers".
 *
 * @property int $id
 * @property string $role
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string|null $patronymic
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Vehicle[] $vehicles
 * @property CourierRequest[] $courierRequests
 */
class Courier extends ActiveRecord implements IdentityInterface
{
    const ROLE_MAIN = 'main';
    const ROLE_BASIC = 'basic';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%couriers}}';
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
            [['role', 'email', 'first_name', 'last_name'], 'required'],
            [['role'], 'in', 'range' => [self::ROLE_MAIN, self::ROLE_BASIC]],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['first_name', 'last_name', 'patronymic'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Роль',
            'email' => 'Email',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patronymic' => 'Отчество',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Получает запрос для связи с Vehicle.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVehicles()
    {
        return $this->hasMany(Vehicle::class, ['courier_id' => 'id']);
    }

    /**
     * Получает запрос для связи с CourierRequest.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourierRequests()
    {
        return $this->hasMany(CourierRequest::class, ['courier_id' => 'id']);
    }

    // Методы интерфейса IdentityInterface

    /**
     * Находит идентификатор пользователя по ID.
     *
     * @param int|string $id ID пользователя
     * @return IdentityInterface|null Объект идентификации пользователя или null, если не найден
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Находит идентификатор пользователя по токену доступа.
     *
     * @param mixed $token Токен доступа для поиска
     * @param mixed $type Тип токена (не используется в этой реализации)
     * @return IdentityInterface|null Объект идентификации пользователя или null, если не найден
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Проверяем, совпадает ли токен с параметром apiKey
        if ($token === Yii::$app->params['apiKey']) {
            // Для примера возвращаем первого курьера с ролью 'main'
            return static::findOne(['role' => self::ROLE_MAIN]);
        }
        return null;
    }

    /**
     * Получает ID пользователя.
     *
     * @return int|string ID пользователя
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Получает ключ аутентификации.
     *
     * @return string Ключ аутентификации
     */
    public function getAuthKey()
    {
        // Не используется в API аутентификации
        return null;
    }

    /**
     * Проверяет корректность ключа аутентификации.
     *
     * @param string $authKey Ключ аутентификации
     * @return bool Является ли ключ аутентификации корректным
     */
    public function validateAuthKey($authKey)
    {
        // Не используется в API аутентификации
        return false;
    }
} 