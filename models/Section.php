<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $restricted
 *
 * @property AccessRequest[] $accessRequests
 * @property User $createdBy
 * @property Document[] $documents
 * @property UserSectionAccess[] $userSectionAccesses
 */
class Section extends \yii\db\ActiveRecord
{


    public $uploadedFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'restricted'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['uploadedFile'], 'file', 'extensions' => ['pdf', 'doc', 'docx']], // Правила валидации для загруженного файла
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'restricted' => 'Restricted',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * Gets query for [[AccessRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccessRequests()
    {
        return $this->hasMany(AccessRequest::class, ['section_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['section_id' => 'id']);
    }

    /**
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[UserSectionAccesses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSectionAccesses()
    {
        return $this->hasMany(UserSectionAccess::class, ['section_id' => 'id']);
    }

    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['section_id' => 'id']);
    }
}
