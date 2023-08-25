<?php

namespace app\models;

use yii\db\ActiveRecord;
use function Symfony\Component\String\s;

/**
 *
 * Game model
 * @property int $id
 * @property string $name
 */
class Game extends ActiveRecord
{
    public function getToDeveloper()
    {
        return $this->hasOne(DeveloperToGame::class, ['game_id' => 'id']);
    }
    public function getDeveloper()
    {
        return $this->hasOne(Developer::class, ['id' => 'developer_id'])->via('toDeveloper');
    }
    public function getToGenres()
    {
        return $this->hasMany(GenreToGame::class, ['game_id' => 'id']);
    }
    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['id' => 'genre_id'])->via('toGenres');
    }
    public static function findGames()
    {
        return self::find();
    }

    public static function deleteGame($id)
    {
        return DeveloperToGame::deleteAll(['game_id' => $id]) && GenreToGame::deleteAll(['game_id' => $id]) && self::deleteAll(['id' => $id]);
    }
}