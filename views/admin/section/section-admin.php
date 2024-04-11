<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\BootstrapAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Административная панель разделов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-admin">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'name',

            // Другие необходимые атрибуты раздела
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="btn btn-primary">Перейти</span>', ['view', 'id' => $model->id]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="btn btn-warning">Редактировать</span>', ['update', 'id' => $model->id]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="btn btn-danger">Удалить</span>', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>