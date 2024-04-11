<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section_access".
 *
 * @property int $id
 * @property int $section_id
 * @property int $user_id
 * @property string $creadet_at
 * @property string $updated_at
 *
 * @property Section $section
 * @property User $user
 */
class SectionAccess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'user_id', 'creadet_at', 'updated_at'], 'required'],
            [['section_id', 'user_id'], 'integer'],
            [['creadet_at', 'updated_at'], 'safe'],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::class, 'targetAttribute' => ['section_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'User ID',
            'creadet_at' => 'Creadet At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::class, ['id' => 'section_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
