<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Alert;

/* @var $this yii\web\View */
/* @var $section app\models\Section */

$this->title = $section->name;
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
// Отображение загруженного документа
if (!empty($section->document->file_path)) {
    echo "<p>Загруженный документ: <a href='" . Yii::$app->getUrlManager()->getBaseUrl() . "/" . $section->document->file_path . "' download>Скачать документ</a></p>";
}

// Форма загрузки документа (для администратора)
if (Yii::$app->user->can('admin')) {
    echo $this->render('_upload_document', ['model' => $model]);
}
?>







<div class="section-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <dl>
                <dt>Описание:</dt>
                <dd><?= Html::encode($section->description) ?></dd>
                <dt>Время создания:</dt>
                <dd><?= Yii::$app->formatter->asDatetime($section->created_at) ?></dd>
                <!-- Другие атрибуты раздела, которые вы хотите отобразить -->
            </dl>


        </div>
    </div>
    <?php if (!empty($documents)): ?>
        <?php foreach ($documents as $document): ?>
            <p><?= Html::a('Скачать документ', ['download', 'id' => $document->id]) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>




    <?php foreach ($documents as $document): ?>
        <p>
            <?= Html::encode($document->name) ?> (Загружен: <?= Yii::$app->formatter->asDatetime($document->uploaded_at) ?>,
            Изменен: <?= Yii::$app->formatter->asDatetime($document->updated_at) ?>)
            <?= Html::a('Скачать', ['download-document', 'id' => $document->id], ['class' => 'btn btn-primary']) ?>
        </p>
    <?php endforeach; ?>

    <!-- Форма для загрузки документов -->
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($model, 'uploadedFile')->fileInput() ?>
    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end() ?>

    <?php


    // Вывод флеш-сообщения об успешной загрузке
    if (!empty($successMessage)) {
        echo yii\bootstrap5\Alert::widget([
            'options' => ['class' => 'alert-success'],
            'body' => $successMessage,
        ]);
    }

    // Вывод флеш-сообщения об ошибке, если оно не пустое
    if (!empty($errorMessage)) {
        echo yii\bootstrap5\Alert::widget([
            'options' => ['class' => 'alert-danger'],
            'body' => $errorMessage,
        ]);
    }
    ?>
</div>