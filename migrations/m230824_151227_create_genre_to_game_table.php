<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%genre_to_game}}`.
 */
class m230824_151227_create_genre_to_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%genre_to_game}}', [
            'genre_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('genre_to_game-pk', '{{%genre_to_game}}', ['genre_id', 'game_id']);
        $this->addForeignKey('genre_to_game-to-game-fk',
            '{{%genre_to_game}}',
            'game_id',
            '{{%game}}',
            'id');
        $this->addForeignKey('genre_to_game-to-genre-fk',
            '{{%genre_to_game}}',
            'genre_id',
            '{{%genre}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%genre_to_game}}');
    }
}
