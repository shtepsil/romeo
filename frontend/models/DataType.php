<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "data_type".
 *
 * @property int $id
 * @property string $first_name Имя пользователя или Имя и Отчество
 * @property string $last_name Фамилия пользователя
 * @property string $phone Телефон пользователя
 * @property string $email Email пользователя
 * @property string $discound_card Дисконтная карта
 * @property string $basket Корзина
 * @property string $confirmation Подтверждение
 */
class DataType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'discound_card', 'basket', 'confirmation'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя пользователя или Имя и Отчество',
            'last_name' => 'Фамилия пользователя',
            'phone' => 'Телефон пользователя',
            'email' => 'Email пользователя',
            'discound_card' => 'Дисконтная карта',
            'basket' => 'Корзина',
            'confirmation' => 'Подтверждение',
        ];
    }
}
