<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title ='Пользователи';
$this->params['breadcrumbs'][] =$this->title;


?>
<h1>Добро пожаловать в Админ панель</h1>

<div class="row">
    <div class="col-md-6">
        <h2>Пользователи</h2>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Удалить/Редактировать пользователя', ['users-list'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="col-md-6">
        <h2>Разделы</h2>
        <?= Html::a('Создать раздел', ['create-section'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Перейти к разделам', ['section/admin'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Настройка доступа к разделам', ['configure-section'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>



