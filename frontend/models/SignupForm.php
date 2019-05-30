<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use backend\controllers\MainController as d;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $fio;
    public $email;
    public $password;
    public $status;
    public $role;
    public $active;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'validateAttr'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => ' уже занят'],

            ['username', 'string', 'min' => 2, 'max' => 255],
            ['status', 'integer', 'max' => 1],
            ['role', 'string', 'min' => 2, 'max' => 6],
            ['active', 'integer', 'max' => 1],
            ['fio', 'string', 'min' => 2, 'max' => 255,'message' => ' должно содержать минимум 2 и максимум 255 символов'],
            ['fio', 'validateAttr'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Email уже занят'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password', 'validateAttr'],
        ];
    }

    public function validateAttr($attribute, $params)
    {
        switch($attribute){
            case 'username':
                if (strpos($this->$attribute, 0x20) !== false) {
                    $this->addError($attribute, ' не может содержать в себе пробелов');}
                preg_match("/[^a-z\d-_]/iu",$this->$attribute,$matches);
                if ($matches) {
                    $this->addError($attribute, ' может содержать только буквы, цифры, дефиз и нижнее подчеркивание');
                }
                break;
            case 'password':
                preg_match("/[^a-z\d-_!@\+=\{\}\[\]#\$%\*]/iu",$this->$attribute,$matches);
                if ($matches) {
                    $this->addError($attribute, ' содержит запрещенные символы');
                }
                break;
            case 'fio':
                preg_match("/[^а-яё ]/iu",$this->$attribute,$matches);
                if ($matches) {
                    $this->addError($attribute, ' может содержать только русские символы');
                }
                break;
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->fio = $this->fio;
        $user->status = $this->status;
        $user->role = $this->role;
        $user->active = $this->active;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'fio' => 'ФИО пользователя',
            'email' => 'Email',
            'password' => 'Пароль',
            'status' => 'Статус',
            'role' => 'Права администратора',
            'active' => 'Активность',
        ];
    }
}
