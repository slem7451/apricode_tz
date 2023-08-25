<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 *
 * Developer model
 * @property int $id
 * @property string $name
 */
class Developer extends ActiveRecord
{
    public function getToGames()
    {
        return $this->hasMany(DeveloperToGame::class, ['developer_id' => 'id']);
    }
    public function getGames()
    {
        return $this->hasMany(Game::class, ['id' => 'game_id'])->via('toGames');
    }
    public static function findAllDevelopers()
    {
        return self::find()->all();
    }

    public static function findDevelopers()
    {
        return self::find();
    }

    public static function deleteDeveloper($id)
    {
        return self::deleteAll(['id' => $id]);
    }
}