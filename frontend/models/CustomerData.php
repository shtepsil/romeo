<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "customer_data".
 *
 * @property int $id
 * @property int $id_customer_profile ID таблицы customer_profile
 * @property int $id_data_type ID таблицы data_type
 * @property string $email Email пользователя
 * @property int $created_at Дата создания строки
 * @property int $delete_at Дата удаления информации в интерфейсе пользователя
 */
class CustomerData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_customer_profile', 'created_at', 'delete_at'], 'integer', 'message' => 'Поля "id_customer_profile" "created_at" "delete_at" должны быть числом'],
            [['user_data', 'id_data_type'], 'string', 'max' => 100, 'message' => 'Поля "id_data_type" "user_data" должны быть строкой'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_customer_profile' => 'ID таблицы customer_profile',
            'id_data_type' => 'ID таблицы data_type',
            'user_data' => 'Разные данные пользователя',
            'created_at' => 'Дата создания строки',
            'delete_at' => 'Дата удаления информации в интерфейсе пользователя',
        ];
    }
}
