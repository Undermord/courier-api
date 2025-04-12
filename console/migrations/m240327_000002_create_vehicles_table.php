<?php

use yii\db\Migration;

/**
 * Класс m240327_000002_create_vehicles_table
 * Создает таблицу транспортных средств
 */
class m240327_000002_create_vehicles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vehicles}}', [
            'id' => $this->primaryKey(),
            'courier_id' => $this->integer()->notNull(),
            'type' => "ENUM('car', 'scooter') NOT NULL",
            'serial_number' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-vehicles-courier_id',
            '{{%vehicles}}',
            'courier_id',
            '{{%couriers}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-vehicles-courier_id', '{{%vehicles}}');
        $this->dropTable('{{%vehicles}}');
    }
} 