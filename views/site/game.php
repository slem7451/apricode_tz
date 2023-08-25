<?php

use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var \app\models\GameForm $model
 * @var \app\models\Developer $developers
 * @var \app\models\Genre $genres
 * @var \app\models\GameForm $game
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Игры';
?>
    <div class="site-game">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-game-modal">Добавить
            игру
        </button>
        <div class="col-3 mt-3">
        <?= Html::dropDownList('select-genre', null, ArrayHelper::map($genres, 'id', 'name'), [
            'prompt' => 'Все жанры',
            'class' => 'form-control',
            'id' => 'select-genre-dl'
        ]) ?>
        </div>
        <div class="mt-3">
            <?php Pjax::begin(['id' => 'game-pjax']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => false,
                'columns' => [
                    [
                        'content' => function ($model) {
                            return $model->name;
                        },
                        'header' => 'Название игры',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center']
                    ],
                    [
                        'content' => function ($model) {
                            return $model->developer->name;
                        },
                        'header' => 'Разработчик',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center']
                    ],
                    [
                        'content' => function ($model) {
                            return implode(', ', ArrayHelper::map($model->genres, 'id', 'name'));
                        },
                        'header' => 'Жанры',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center']
                    ],
                    [
                        'content' => function ($model) {
                            return '<button class="btn btn-primary btn-sm update-btn" title="Редактировать" id="' . $model->id . '-upd">Р</button>
                                        <button class="btn btn-danger btn-sm delete-btn" title="Удалить" id="' . $model->id . '-del">Х</button>';
                        },
                        'contentOptions' => ['class' => 'w-25 text-center']
                    ]
                ]
            ]) ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
    <div class="d-none upd-game-id"></div>
<?php
$form = ActiveForm::begin(['id' => 'add-game-form']);
Modal::begin([
    'id' => 'add-game-modal',
    'title' => 'Добавление игры',
    'footer' => Html::submitButton('Добавить', ['class' => 'btn btn-success'])
]);
echo $form->field($model, 'name')->textInput(['placeholder' => 'Название игры'])->label(false);
echo $form->field($model, 'developer')
    ->dropDownList(ArrayHelper::map($developers, 'id', 'name'), [
        'prompt' => [
            'text' => 'Разработчик',
            'options' => [
                'selected' => true,
                'disabled' => true
            ]
        ]
    ])->label(false);
echo $form->field($model, 'genres')->widget(Select2::class, [
    'data' => ArrayHelper::map($genres, 'id', 'name'),
    'size' => Select2::SMALL,
    'showToggleAll' => false,
    'options' => [
        'placeholder' => 'Жанры',
        'multiple' => true,
        'id' => 'add-genres',
    ],
    'pluginOptions' => [
        'allowClear' => true
    ]
])->label(false);
Modal::end();
ActiveForm::end();

Pjax::begin(['id' => 'update-game-pjax']);
$form = ActiveForm::begin(['id' => 'update-game-form']);
Modal::begin([
    'id' => 'update-game-modal',
    'title' => 'Изменение игры',
    'footer' => Html::submitButton('Изменить', ['class' => 'btn btn-success'])
]);
echo $form->field($game, 'name')->textInput(['placeholder' => 'Название игры'])->label(false);
echo $form->field($game, 'developer')
    ->dropDownList(ArrayHelper::map($developers, 'id', 'name'), [
        'prompt' => [
            'text' => 'Разработчик',
            'options' => [
                'selected' => true,
                'disabled' => true
            ]
        ]
    ])->label(false);
echo $form->field($game, 'genres')->widget(Select2::class, [
    'data' => ArrayHelper::map($genres, 'id', 'name'),
    'size' => Select2::SMALL,
    'showToggleAll' => false,
    'options' => [
        'placeholder' => 'Жанры',
        'multiple' => true,
        'id' => 'update-genres',
    ],
    'pluginOptions' => [
        'allowClear' => true
    ]
])->label(false);
Modal::end();
ActiveForm::end();
Pjax::end();

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#add-game-modal', function () {
        $('#add-game-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#add-game-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=site%2Fgame',
            method: 'POST',
            data: data,
            success: function (result) {
                $('#add-game-modal').modal('hide');
                $.pjax.reload({container: '#game-pjax'});
            }
        })
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('click', '.update-btn', function() {
        var idU = this.id.split('-')[0];
        $.pjax.reload({container: '#update-game-pjax', data: {idU: idU}, replace: false});
        $('.upd-game-id').html(idU);
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('pjax:success', '#update-game-pjax', function() {
        $('#update-game-modal').modal('show');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-game-form', function() {
        var data = $(this).serialize();
        data += '&idU=' + $('.upd-game-id').html();
        $.ajax({
            url: '/index.php?r=site%2Fgame',
            method: 'POST',
            data: data,
            success: function (result) {
                $('#update-game-modal').modal('hide');
                $.pjax.reload({container: '#game-pjax'});
            }
        })
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('click', '.delete-btn', function() {
        var idD = this.id.split('-')[0];
        $.pjax.reload({container: '#game-pjax', data: {idD: idD}, replace: false});
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('change', '#select-genre-dl', function() {
        $.pjax.reload({container: '#game-pjax', data: {genre: this.value}, replace: false});
    })
JS
);
