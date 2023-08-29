<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var \app\models\DeveloperForm $model
 * @var \app\models\DeveloperForm $developer
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Разработчики';
?>
    <div class="site-developer">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-dev-modal">Добавить
            разработчика
        </button>
        <div class="col-6 mt-3">
            <?php Pjax::begin(['id' => 'dev-pjax']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => false,
                'columns' => [
                    [
                        'content' => function ($model) {
                            return $model->name;
                        },
                        'header' => 'Студия',
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
<div class="d-none upd-dev-id"></div>
<?php
$form = ActiveForm::begin(['id' => 'add-dev-form']);
Modal::begin([
    'id' => 'add-dev-modal',
    'title' => 'Добавление разработчика',
    'footer' => Html::submitButton('Добавить', ['class' => 'btn btn-success'])
]);
echo $form->field($model, 'name')->textInput(['placeholder' => 'Название студии'])->label(false);
Modal::end();
ActiveForm::end();

Pjax::begin(['id' => 'update-dev-pjax']);
$form = ActiveForm::begin(['id' => 'update-dev-form']);
Modal::begin([
    'id' => 'update-dev-modal',
    'title' => 'Изменение разработчика',
    'footer' => Html::submitButton('Изменить', ['class' => 'btn btn-success'])
]);
echo $form->field($developer, 'name')->textInput(['placeholder' => 'Название студии'])->label(false);
Modal::end();
ActiveForm::end();
Pjax::end();

$this->registerJS(<<<JS
    $(document).on('hidden.bs.modal', '#add-dev-modal', function () {
        $('#add-dev-form')[0].reset();
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#add-dev-form', function() {
        var data = $(this).serialize();
        $.ajax({
            url: '/site/developer',
            method: 'POST',
            data: data,
            success: function (result) {
                $('#add-dev-modal').modal('hide');
                $.pjax.reload({container: '#dev-pjax'});
            }
        })
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('click', '.update-btn', function() {
        var idU = this.id.split('-')[0];
        $.pjax.reload({container: '#update-dev-pjax', data: {idU: idU}, replace: false});
        $('.upd-dev-id').html(idU);
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('pjax:success', '#update-dev-pjax', function() {
        $('#update-dev-modal').modal('show');
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('beforeSubmit', '#update-dev-form', function() {
        var data = $(this).serialize();
        data += '&idU=' + $('.upd-dev-id').html();
        $.ajax({
            url: '/site/developer',
            method: 'POST',
            data: data,
            success: function (result) {
                $('#update-dev-modal').modal('hide');
                $.pjax.reload({container: '#dev-pjax'});
            }
        })
        return false;
    })
JS
);

$this->registerJS(<<<JS
    $(document).on('click', '.delete-btn', function() {
        var idD = this.id.split('-')[0];
        $.pjax.reload({container: '#dev-pjax', data: {idD: idD}, replace: false});
    })
JS
);
