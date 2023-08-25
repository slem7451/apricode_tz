<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var \app\models\GenreForm $model
 * @var \app\models\GenreForm $genre
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Жанры';
?>
<div class="site-genre">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-genre-modal">Добавить жанр</button>
    <div class="col-6 mt-3">
        <?php Pjax::begin(['id' => 'genre-pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'columns' => [
                [
                    'content' => function ($model) {
                        return $model->name;
                    },
                    'header' => 'Жанр',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center']
                ],
                [
                    'content' => function($model) {
                        return '<button class="btn btn-primary btn-sm update-btn" title="Редактировать" id="' . $model->id . '-upd">Р</button>
                                        <button class="btn btn-danger btn-sm delete-btn" title="Удалить" id="' . $model->id . '-del"' . (count($model->games) ? 'disabled' : '') . '>Х</button>';
                    },
                    'contentOptions' => ['class' => 'w-25 text-center']
                ]
            ]
        ]) ?>
        <?php Pjax::end(); ?>
    </div>
</div>
    <div class="d-none upd-genre-id"></div>
<?php
$form = ActiveForm::begin(['id' => 'add-genre-form']);
Modal::begin([
    'id' => 'add-genre-modal',
    'title' => 'Добавление жанра',
    'footer' => Html::submitButton('Добавить', ['class' => 'btn btn-success'])
]);
echo $form->field($model, 'name')->textInput(['placeholder' => 'Название жанра'])->label(false);
Modal::end();
ActiveForm::end();

Pjax::begin(['id' => 'update-genre-pjax']);
$form = ActiveForm::begin(['id' => 'update-genre-form']);
Modal::begin([
    'id' => 'update-genre-modal',
    'title' => 'Изменение жанра',
    'footer' => Html::submitButton('Изменить', ['class' => 'btn btn-success'])
]);
echo $form->field($genre, 'name')->textInput(['placeholder' => 'Название жанра'])->label(false);
Modal::end();
ActiveForm::end();
Pjax::end();

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#add-genre-modal', function () {
        $('#add-genre-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#add-genre-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/index.php?r=site%2Fgenre',
            method: 'POST',
            data: data,
            success: function (result) {
                $('#add-genre-modal').modal('hide');
                $.pjax.reload({container: '#genre-pjax'});
            }
        })
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('click', '.update-btn', function() {
        var idU = this.id.split('-')[0];
        $.pjax.reload({container: '#update-genre-pjax', data: {idU: idU}, replace: false});
        $('.upd-genre-id').html(idU);
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('pjax:success', '#update-genre-pjax', function() {
        $('#update-genre-modal').modal('show');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-genre-form', function() {
        var data = $(this).serialize();
        data += '&idU=' + $('.upd-genre-id').html();
        $.ajax({
            url: '/index.php?r=site%2Fgenre',
            method: 'POST',
            data: data,
            success: function (result) {
                $('#update-genre-modal').modal('hide');
                $.pjax.reload({container: '#genre-pjax'});
            }
        })
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('click', '.delete-btn', function() {
        var idD = this.id.split('-')[0];
        $.pjax.reload({container: '#genre-pjax', data: {idD: idD}, replace: false});
    })
JS
);
