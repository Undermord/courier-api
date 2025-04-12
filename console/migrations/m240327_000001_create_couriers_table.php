<?php

use yii\db\Migration;

/**
 * Класс m240327_000001_create_couriers_table
 * Создает таблицу курьеров
 */
class m240327_000001_create_couriers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%couriers}}', [
            'id' => $this->primaryKey(),
            'role' => "ENUM('main', 'basic') NOT NULL",
            'email' => $this->string()->notNull()->unique(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'patronymic' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%couriers}}');
    }
} 