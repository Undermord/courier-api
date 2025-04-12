<?php

use yii\db\Migration;

/**
 * Класс m240327_000003_create_courier_requests_table
 * Создает таблицу заявок курьеров
 */
class m240327_000003_create_courier_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%courier_requests}}', [
            'id' => $this->primaryKey(),
            'courier_id' => $this->integer()->notNull(),
            'vehicle_id' => $this->integer()->notNull(),
            'status' => "ENUM('started', 'holded', 'finished') NOT NULL",
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-courier_requests-courier_id',
            '{{%courier_requests}}',
            'courier_id',
            '{{%couriers}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-courier_requests-vehicle_id',
            '{{%courier_requests}}',
            'vehicle_id',
            '{{%vehicles}}',
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
        $this->dropForeignKey('fk-courier_requests-courier_id', '{{%courier_requests}}');
        $this->dropForeignKey('fk-courier_requests-vehicle_id', '{{%courier_requests}}');
        $this->dropTable('{{%courier_requests}}');
    }
} 