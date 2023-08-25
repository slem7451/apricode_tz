<?php

namespace app\models;

use yii\base\Model;

class GenreForm extends Model
{
    public $name;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения']
        ];
    }

    public function addGenre()
    {
        $genre = new Genre();
        $genre->name = $this->name;
        return $genre->save();
    }

    public function loadFromDB($id)
    {
        $genre = Genre::findOne(['id' => $id]);
        $this->name = $genre->name;
    }

    public function updateGenre($id)
    {
        $genre = Genre::findOne(['id' => $id]);
        $genre->name = $this->name;
        return $genre->save();
    }
}