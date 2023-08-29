<?php

namespace app\controllers;

use app\models\Game;
use yii\rest\ActiveController;

class GameController extends ActiveController
{
    public $modelClass = 'app\models\Game';

    public function actionGenre($genre_id)
    {
        return Game::findAllGamesByGenreId($genre_id);
    }
}