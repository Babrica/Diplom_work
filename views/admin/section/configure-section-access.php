<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Настройка доступа к разделу';
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['section/index']];
$this->params['breadcrumbs'][] = ['label' => $section->name, 'url' => ['section/view', 'id' => $section->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="section-access-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($section, 'name')->textInput(['readonly' => true]) ?>

    <h3>Выберите пользователей для предоставления доступа:</h3>

    <?php foreach ($usersWithoutAccess as $user): ?>
        <div class="checkbox">
            <label>
                <?= Html::checkbox('userIds[]', false, ['value' => $user->id]) ?>
                <?= Html::encode($user->username) ?>
            </label>
        </div>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton('Предоставить доступ', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>