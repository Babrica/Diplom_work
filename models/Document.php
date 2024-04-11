<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\base\Model;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property int $section_id
 * @property string $name
 * @property string $file_path
 * @property int $uploaded_by
 * @property string $uploaded_at
 * @property int $updated_by
 * @property string $updated_at
 * @property int $size
 *
 * @property Comment[] $comments
 * @property Section $section
 * @property User $updatedBy
 * @property User $uploadedBy
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * The attribute to hold the uploaded file.
     */
    public $uploadedFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'name', 'uploaded_by', 'uploaded_at', 'size'], 'required'],
            [['section_id', 'uploaded_by', 'updated_by', 'size'], 'integer'],
            [['uploaded_at', 'updated_at'], 'safe'],
            [['name', 'file_path'], 'string', 'max' => 255],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::class, 'targetAttribute' => ['section_id' => 'id']],
            [['uploaded_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['uploaded_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['uploadedFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx'], // Validation rule for uploaded file
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_id' => 'Section ID',
            'name' => 'Name',
            'file_path' => 'File Path',
            'uploaded_by' => 'Uploaded By',
            'uploaded_at' => 'Uploaded At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'size' => 'Size',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['document_id' => 'id']);
    }

    /**
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[UploadedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedBy()
    {
        return $this->hasOne(User::class, ['id' => 'uploaded_by']);
    }

    /**
     * Saves the uploaded file to the specified directory.
     *
     * @param string $directory the directory to save the uploaded file
     * @return bool whether the file is successfully saved
     */
    public function saveUploadedFile($directory)
    {
        if ($this->uploadedFile) {
            $fileName = Yii::$app->security->generateRandomString() . '.' . $this->uploadedFile->extension;
            $filePath = $directory . '/' . $fileName;
            if ($this->uploadedFile->saveAs($filePath)) {
                $this->file_path = $filePath;
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getFilePath()
    {
        // Проверяем, есть ли файл
        if (!empty($this->file_path) && is_file($this->file_path)) {
            return $this->file_path;
        }

        return null;
    }



}