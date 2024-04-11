<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Разделы';
$this->params['breadcrumbs'][] = $this->title;
?>
<link  rel="stylesheet" href="/web/css/site.css">
<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
    <?php foreach ($sections as $section): ?>
        <div class="col-md-4 section-container">
            <a href="<?= Yii::$app->urlManager->createUrl(['site/section', 'id' => $section->id]) ?>" class="section-link">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2><?= Html::encode($section->name) ?></h2>
                    <p><?= Html::encode($section->description) ?></p>
                    <p><strong>Время создания:</strong> <?= Yii::$app->formatter->asDatetime($section->updated_at) ?></p>
                    <?= Html::a('Подробнее', ['section/view', 'id' => $section->id], ['class' => 'btn btn-primary']); ?>
                    <!-- Другие данные раздела -->
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>