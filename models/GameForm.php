<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class GameForm extends Model
{
    public $name;
    public $developer;
    public $genres;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения'],

            ['developer', 'required', 'message' => 'Обязательно для заполнения'],

            ['genres', 'required', 'message' => 'Обязательно для заполнения'],
        ];
    }

    public function addGame()
    {
        $success = true;
        $game = new Game();
        $game->name = $this->name;
        $success *= $game->save();
        if ($success) {
            $developerToGame = new DeveloperToGame();
            $developerToGame->game_id = $game->id;
            $developerToGame->developer_id = $this->developer;
            $success *= $developerToGame->save();
            foreach ($this->genres as $genre) {
                $genreToGame = new GenreToGame();
                $genreToGame->genre_id = $genre;
                $genreToGame->game_id = $game->id;
                $success *= $genreToGame->save();
            }
        }
        return $success;
    }

    public function loadFromDB($id)
    {
        $game = Game::findOne(['id' => $id]);
        $this->name = $game->name;
        $this->developer = $game->developer->id;
        $this->genres = ArrayHelper::getColumn($game->genres, 'id');
    }

    public function updateGame($id)
    {
        $success = true;
        $game = Game::findOne(['id' => $id]);
        $game->name = $this->name;
        $success *= $game->save();
        if ($success) {
            $success *= DeveloperToGame::deleteAll(['game_id' => $id]);
            $success *= GenreToGame::deleteAll(['game_id' => $id]);
            $developerToGame = new DeveloperToGame();
            $developerToGame->game_id = $game->id;
            $developerToGame->developer_id = $this->developer;
            $success *= $developerToGame->save();
            foreach ($this->genres as $genre) {
                $genreToGame = new GenreToGame();
                $genreToGame->genre_id = $genre;
                $genreToGame->game_id = $game->id;
                $success *= $genreToGame->save();
            }
        }
        return $success;
    }
}