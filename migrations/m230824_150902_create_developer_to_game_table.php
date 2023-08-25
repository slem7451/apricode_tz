<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%developer_to_game}}`.
 */
class m230824_150902_create_developer_to_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%developer_to_game}}', [
            'developer_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('developer_to_game-pk', '{{%developer_to_game}}', ['developer_id', 'game_id']);
        $this->addForeignKey('developer_to_game-to-game-fk',
            '{{%developer_to_game}}',
            'game_id',
            '{{%game}}',
            'id');
        $this->addForeignKey('developer_to_game-to-developer-fk',
            '{{%developer_to_game}}',
            'developer_id',
            '{{%developer}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%developer_to_game}}');
    }
}
