<?php

namespace app\controllers;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\User;
use app\models\UserSearch;
use app\models\Section;
use app\models\SectionSearch;
use  yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



class AdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=> AccessControl::className(),
                'rules'=>[
                    [
                      'allow'=>true,
                        'roles'=>['@'],
                        'matchCallback'=>function($rule,$action){
                                return \Yii::$app->user->identity->isAdmin();
                        }

                    ],

                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Пользователь успешно добавлен.');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


// Действие для отображения списка пользователей
    public function actionUsersList()
    {
        // Создаем экземпляр модели поиска
        $searchModel = new UserSearch();

        // Получаем данные для отображения
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Отображаем представление с данными
        return $this->render('users-list', [

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
// Действие для редактирования пользователя
    public function actionEditUser($id)
    {

        $model = User::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен.');
            return $this->redirect(['users-list']);
        }

        return $this->render('edit-user', [
            'model' => $model,
        ]);
    }

    // Действие для удаления пользователя
    public function actionDeleteUser($id)
    {
        $model = User::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Пользователь успешно удален.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка удаления пользователя.');
        }

        return $this->redirect(['users-list']);
    }

        //Поиск пользователей
    public function actionSerchUsers()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('users-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateSection()
    {
        $model = new Section();
        $users = User::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            // Заполняем обязательные поля перед сохранением модели
            $model->updated_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->id; // Предполагается, что у вас есть авторизованный пользователь
            $model->restricted = 0; // Или устанавливайте значение в зависимости от вашей логики

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Раздел успешно создан.');
                return $this->redirect(['index']); // Перенаправляем пользователя на главную страницу админ-панели
            }
        }

        return $this->render('create-section', [
            'model' => $model,
            'existingSections' => Section::find()->all(),
            'users' => $users,
        ]);
    }


    public function actionConfigureSection()
    {
        // Получаем экземпляр раздела с закрытым доступом
        $restrictedSections = Section::find()->where(['restricted' => 1])->all();

        // Создаем провайдер данных на основе полученного списка разделов
        $dataProvider = new ActiveDataProvider([
            'query' => Section::find()->where(['restricted' => 1]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('section/configure-section', [
            'dataProvider' => $dataProvider, // Передаем провайдер данных в представление
            'restrictedSections' => $restrictedSections,
        ]);
    }


}
