<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Section;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use app\models\Document;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use app\models\SectionSearch;
use app\models\SectionAccess;
class SectionController extends Controller
{
    /**
     * Отображает отдельную страницу для раздела.
     * @param int $id Идентификатор раздела
     * @param int $sectionId идентификатор раздела
     *  @param int $userId идентификатор пользователя
     * @return string Возвращает вид страницы с информацией о разделе
     *
     * @return mixed
     *
     */
    public function actionView($id)
    {
        // Находим раздел по id
        $section = Section::findOne($id);

        // Получаем список документов для данного раздела
        $documents = $section->documents;

        // Создаем экземпляр модели Document
        $model = new Document();

        // Проверяем, была ли отправлена форма для загрузки файла
        if (Yii::$app->request->isPost) {
            // Получаем экземпляр загруженного файла
            $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
            if ($model->saveUploadedFile('C:\xampp\htdocs\web\documents')) { // Замените 'your_directory_path' на реальный путь к директории
                // Установка связи с разделом
                $model->section_id = $section->id;
                // Установка ID пользователя, загрузившего документ
                $model->uploaded_by = Yii::$app->user->id;
                // Установка времени загрузки
                $model->uploaded_at = date('Y-m-d H:i:s');
                // Установка размера файла
                $model->size = $model->uploadedFile->size;
                // Сохраняем информацию о документе
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Файл успешно загружен.');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка при сохранении информации о документе.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Произошла ошибка загрузки файла.');
            }
        }

        return $this->render('view', [
            'section' => $section,
            'documents' => $documents,
            'model' => $model,
        ]);
    }

    public function actionUploadDocument($id)
    {
        $section = Section::findOne($id);
        if ($section === null) {
            throw new NotFoundHttpException("Раздел с ID $id не найден.");
        }

        $model = new Document();

        if (Yii::$app->request->isPost) {
            $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
            if ($model->saveUploadedFile('C:\xampp\htdocs\web\documents')) { // Замените 'your_directory_path' на реальный путь к директории
                $model->section_id = $section->id; // Установка связи с разделом
                $model->uploaded_by = Yii::$app->user->id; // Установка ID пользователя, загрузившего документ
                $model->uploaded_at = date('Y-m-d H:i:s'); // Установка времени загрузки
                $model->size = $model->uploadedFile->size; // Установка размера файла
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Документ успешно загружен.');
                    return $this->redirect(['view', 'id' => $id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка при сохранении информации о документе.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла.');
            }
        }

        return $this->render('upload-document', [
            'model' => $model,
            'section' => $section,
        ]);
    }



    public function actionDownloadDocument($id)
    {
        $document = Document::findOne($id);
        if ($document === null) {
            throw new NotFoundHttpException("Документ с ID $id не найден.");
        }

        $filePath = $document->getFilePath(); // Метод, который возвращает путь к файлу на сервере
        if (!is_file($filePath) || !file_exists($filePath)) {
            throw new NotFoundHttpException("Файл документа не найден на сервере.");
        }

        // Отправляем файл пользователю для скачивания
        return Yii::$app->response->sendFile($filePath, $document->name);
    }


    public function actionCreate()
    {
        $model = new Section();

        if ($model->load(Yii::$app->request->post())) {
            // Проверяем, был ли загружен файл
            $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
            if ($model->uploadedFile) {
                // Создаем экземпляр модели Document
                $document = new Document();
                // Устанавливаем атрибуты документа
                $document->section_id = $model->id;
                $document->name = $model->uploadedFile->name;
                // Сохраняем документ
                if ($document->save()) {
                    // Сохраняем загруженный файл
                    $document->saveUploadedFile('/web/documents');
                }
            }

            // Проверяем, было ли выбрано закрытие раздела
            if ($model->restricted) {
                // Если раздел должен быть закрытым, устанавливаем значение поля restricted как 1
                $model->restricted = 1;
            } else {
                // Если раздел не должен быть закрытым, устанавливаем значение поля restricted как 0
                $model->restricted = 0;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Раздел успешно создан.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionAdmin()
    {
        $searchModel = new SectionSearch(); // Создание объекта модели поиска

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); // Получение данных с использованием модели поиска

        return $this->render('@app/views/admin/section/section-admin', [
            'searchModel' => $searchModel, // Передача модели поиска в представление
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $section = Section::findOne($id);
        if (!$section) {
            throw new NotFoundHttpException("Раздел с ID $id не найден.");
        }

        $section->delete();

        // Перенаправляем пользователя на страницу с административной панелью разделов
        return $this->redirect(['admin']);
    }


    public function actionUpdate($id)
    {
        $section = Section::findOne($id);
        if (!$section) {
            throw new NotFoundHttpException("Раздел с ID $id не найден.");
        }

        if ($section->load(Yii::$app->request->post()) && $section->save()) {
            return $this->redirect(['admin']);
        }

        return $this->render('@app/views/admin/section/update', [
            'section' => $section,
        ]);
    }

    public function actionGrantAccess($sectionId, $userId)
    {
        // Проверяем, что пользователь авторизован
        if (Yii::$app->user->isGuest) {
            // Если пользователь не авторизован, перенаправляем его на страницу входа
            return $this->redirect(['site/login']);
        }

        // Ваша логика для предоставления доступа к разделу
        // Например, установка соответствующего значения в базе данных

        Yii::$app->session->setFlash('success', 'Доступ к разделу успешно предоставлен.');

        // После предоставления доступа перенаправляем пользователя обратно на страницу, с которой был отправлен запрос
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    public function actionConfigureAccess($id)
    {
        // Получаем экземпляр раздела с указанным id
        $section = Section::findOne($id);

        // Получаем список пользователей, которым еще не предоставлен доступ к этому разделу
        $usersWithoutAccess = User::find()
            ->where(['not in', 'id', $section->getUsersWithAccess()->select('user_id')])
            ->all();

        // Обработка отправки формы
        if (Yii::$app->request->isPost) {
            $userIds = Yii::$app->request->post('userIds');

            // Предоставляем доступ выбранным пользователям
            foreach ($userIds as $userId) {
                $access = new SectionAccess();
                $access->section_id = $id;
                $access->user_id = $userId;
                $access->save();
            }

            Yii::$app->session->setFlash('success', 'Доступ к разделу успешно предоставлен.');
            return $this->redirect(['view', 'id' => $id]);
        }

        // Отображаем список пользователей с формой для предоставления доступа
        return $this->render('configure-section-access', [
            'section' => $section,
            'usersWithoutAccess' => $usersWithoutAccess,
        ]);
    }







}