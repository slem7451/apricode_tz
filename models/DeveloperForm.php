<?php

namespace app\models;

use yii\base\Model;

class DeveloperForm extends Model
{
    public $name;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Обязательно для заполнения'],
        ];
    }

    public function addDeveloper()
    {
        $developer = new Developer();
        $developer->name = $this->name;
        return $developer->save();
    }

    public function loadFromDB($id)
    {
        $developer = Developer::findOne(['id' => $id]);
        $this->name = $developer->name;
    }

    public function updateDeveloper($id)
    {
        $developer = Developer::findOne(['id' => $id]);
        $developer->name = $this->name;
        return $developer->save();
    }
}