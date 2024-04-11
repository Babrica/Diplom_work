<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Настройка доступа к разделам';
$this->params['breadcrumbs'][] = $this->title;

?>

    <h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'name',
        'description',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{configure}',
            'buttons' => [
                'configure' => function ($url, $model, $key) {
                    return Html::a('Настроить', ['configure-access', 'id' => $model->id], ['class' => 'btn btn-primary']);
                },
            ],
        ],
    ],
]); ?>