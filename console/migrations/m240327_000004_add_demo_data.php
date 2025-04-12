<?php

use yii\db\Migration;

/**
 * Класс m240327_000004_add_demo_data
 * Добавляет демонстрационные данные в таблицы
 */
class m240327_000004_add_demo_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем демо-курьеров
        $this->insert('{{%couriers}}', [
            'role' => 'main',
            'email' => 'admin@example.com',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'patronymic' => 'Ivanovich',
        ]);

        $this->insert('{{%couriers}}', [
            'role' => 'basic',
            'email' => 'courier1@example.com',
            'first_name' => 'Alex',
            'last_name' => 'Sidorov',
            'patronymic' => 'Petrovich',
        ]);

        $this->insert('{{%couriers}}', [
            'role' => 'basic',
            'email' => 'courier2@example.com',
            'first_name' => 'Petr',
            'last_name' => 'Petrov',
            'patronymic' => 'Petrovich',
        ]);

        // Добавляем демо-транспорт
        $this->insert('{{%vehicles}}', [
            'courier_id' => 1,
            'type' => 'car',
            'serial_number' => 'CAR-IVAN-001',
        ]);

        $this->insert('{{%vehicles}}', [
            'courier_id' => 2,
            'type' => 'scooter',
            'serial_number' => 'SCOOTER-ALEX-001',
        ]);

        $this->insert('{{%vehicles}}', [
            'courier_id' => 3,
            'type' => 'car',
            'serial_number' => 'CAR-PETR-001',
        ]);

        // Добавляем демо-заявки
        $this->insert('{{%courier_requests}}', [
            'courier_id' => 1,
            'vehicle_id' => 1,
            'status' => 'started',
        ]);

        $this->insert('{{%courier_requests}}', [
            'courier_id' => 2,
            'vehicle_id' => 2,
            'status' => 'holded',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%courier_requests}}', ['courier_id' => [1, 2]]);
        $this->delete('{{%vehicles}}', ['courier_id' => [1, 2, 3]]);
        $this->delete('{{%couriers}}', ['email' => ['admin@example.com', 'courier1@example.com', 'courier2@example.com']]);
    }
} 