<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use function Symfony\Component\String\s;

/**
 *
 * Game model
 * @property int $id
 * @property string $name
 */
class Game extends ActiveRecord
{
    public $dev;
    public $gens;
    public function fields()
    {
        return [
            'id',
            'name',
            'developer' => function ($model) {
                return $model->developer;
            },
            'genres' => function ($model) {
                return $model->genres;
            }
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required'],

            ['dev', 'required'],

            ['gens', 'required']
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        DeveloperToGame::deleteAll(['game_id' => $this->id]);
        GenreToGame::deleteAll(['game_id' => $this->id]);
        $developerToGame = new DeveloperToGame();
        $developerToGame->game_id = $this->id;
        $developerToGame->developer_id = $this->dev;
        $developerToGame->save();
        foreach ($this->gens as $genre) {
            $genreToGame = new GenreToGame();
            $genreToGame->genre_id = $genre;
            $genreToGame->game_id = $this->id;
            $genreToGame->save();
        }
    }

    public function beforeDelete()
    {
        DeveloperToGame::deleteAll(['game_id' => $this->id]);
        GenreToGame::deleteAll(['game_id' => $this->id]);
        return parent::beforeDelete();
    }

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

    public static function findGames($genre_id)
    {
        $games = self::find()->joinWith('toGenres');
        if ($genre_id) {
            $games->andWhere(['genre_id' => $genre_id]);
        }
        return $games;
    }

    public static function findAllGamesByGenreId($genre_id)
    {
        return self::find()->joinWith('toGenres')->andWhere(['genre_id' => $genre_id])->all();
    }

    public static function deleteGame($id)
    {
        return DeveloperToGame::deleteAll(['game_id' => $id]) && GenreToGame::deleteAll(['game_id' => $id]) && self::deleteAll(['id' => $id]);
    }
}