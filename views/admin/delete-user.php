<h1>Удалить пользователя: <?= Html::encode($model->full_name) ?></h1>

<p>Вы уверены, что хотите удалить этого пользователя?</p>

<?= Html::a('Да', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
        'method' => 'post',
    ],
]) ?>
<?= Html::a('Нет', ['index'], ['class' => 'btn btn-default']) ?>