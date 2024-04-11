<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактировать раздел: ' . $section->name;
$this->params['breadcrumbs'][] = ['label' => 'Административная панель разделов', 'url' => ['admin']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="section-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($section, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($section, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>