<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $full_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $number
 * @property int $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
   public $password_repeat;
   public $check;


    public static function tableName()
    {
        return 'user';
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }
    public static function findByUsername($username)
    {
        return User::findOne(['username'=>$username]);
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
    public function beforeSave($insert)
    {
       $this->password = md5($this->password);
       return parent::beforeSave($insert);
    }
    public function isAdmin(){
        return $this->role ===1;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'username', 'email', 'password', 'number'], 'required'],
            [['check'], 'compare','compareValue'=>1,
                'message'=>'Нужно согласиться на обработку персональных данных'],
            ['password_repeat','compare', 'compareAttribute'=>'password'],
            ['full_name','match','pattern'=>'/^[а-яА-Я -]*$/u',
                'message'=>'В ФИО разрещены символы кирилицы,пробелы и дефисы'],
            ['username','match','pattern'=>'/^[a-zA-Z]\w*$/u',
                'message'=>'В логине разрешены символы латиницы'],
            ['number','match','pattern'=>'/^(?:\+7|8)\d{10}$/',
                'message'=>'Номер должен начинаться с +7 или с 8 и иметь в сумме 11 цифр'],
            [['role'], 'integer'],
            [['role'],'default','value'=>0],
            [['full_name', 'email'], 'string', 'max' => 50],
            [['username'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 255],
            [['number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'ФИО',
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'number' => 'Номер',
            'password_repeat'=>'Повторение пароля',
            'check'=>'Согласие на обработку персональных данных',
            'role' => 'Роль',
        ];
    }
}
