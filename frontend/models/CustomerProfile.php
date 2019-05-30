<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "customer_profile".
 *
 * @property int $id
 * @property string $password md5 - хэш пароля пользователя
 * @property string $comment Комментарий
 * @property string $verification_key Код ферификации пользователя_timestemp вермя жизни кода верификации
 * @property int $delete_at Дата удаления аккаунта пользователя
 * @property int $created_at Дата создания записи (дата регистрации)
 */
class CustomerProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['delete_at', 'created_at'], 'integer'],
            [['password'], 'string', 'max' => 32],
            [['verification_key'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'md5 - хэш пароля пользователя',
            'comment' => 'Комментарий',
            'verification_key' => 'Код ферификации пользователя_timestemp вермя жизни кода верификации',
            'delete_at' => 'Дата удаления аккаунта пользователя',
            'created_at' => 'Дата создания записи (дата регистрации)',
        ];
    }
}
