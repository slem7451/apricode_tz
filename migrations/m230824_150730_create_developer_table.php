<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%developer}}`.
 */
class m230824_150730_create_developer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%developer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%developer}}');
    }
}
