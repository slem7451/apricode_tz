<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 *
 * Genre model
 * @property int $id
 * @property string $name
 */
class Genre extends ActiveRecord
{
    public function getToGames()
    {
        return $this->hasMany(GenreToGame::class, ['genre_id' => 'id']);
    }
    public function getGames()
    {
        return $this->hasMany(Game::class, ['id' => 'game_id'])->via('toGames');
    }

    public static function findAllGenres()
    {
        return self::find()->all();
    }

    public static function findGenres()
    {
        return self::find();
    }

    public static function deleteGenre($id)
    {
        return self::deleteAll(['id' => $id]);
    }
}