<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\UserSearch;
use yii\bootstrap5\BootstrapAsset;
$this->title = 'Список пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        Для использование поиска введите данные и нажмите enter
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'full_name',
            'username',
            'email',
            'number',
            'role',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}  {customButton}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="btn btn-primary"">Редактировать</span>', ['admin/edit-user', 'id' => $model->id ], [
                            'title' => 'Редактировать',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="btn btn-danger">Удалить</span>', ['admin/delete-user', 'id' => $model->id], [
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                'method' => 'post',
                            ],
                        ]);
                    },

                ],
            ],

        ],
    ]); ?>

</div>





