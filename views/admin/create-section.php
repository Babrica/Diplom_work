<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создать раздел';
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'uploadedFile')->fileInput() ?>

        <?= $form->field($model, 'restricted')->dropDownList(['0' => 'Нет', '1' => 'Да'], [

            'prompt' => 'Выберите статус...',
            'onchange' => '
                if ($(this).val() == 1) {
                    $("#userTable").show();
                } else {
                    $("#userTable").hide();
                }
            ',

        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <?= $form->errorSummary($model) ?>
    </div>
