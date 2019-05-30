<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property string $id
 * @property int $name Номер заказа
 * @property int $id_customer_profile ID пользователя, сделавшего заказ
 * @property string $discount_card Штрихкод дисконтной карты
 * @property string $promotional_code Промокод
 * @property int $employee_code_ready ID работника, установившего статус заказа в "ready"
 * @property int $ready_time Время изменения в интерфейсе статуса на ready
 * @property int $employee_code_complete ID работника, установившего статус заказа "completed"
 * @property int $complete_time Время изменения в интерфейсе статуса на completed
 * @property int $employee_code_cancel ID работника, установившего статус заказа "canceled"
 * @property int $cancel_time Время изменения в интерфейсе статуса на canceled
 * @property string $comment Комментарий
 * @property int $created_at Время создания записи
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'id_customer_profile', 'discount_card', 'employee_code_ready', 'ready_time', 'employee_code_complete', 'complete_time', 'employee_code_cancel', 'cancel_time', 'created_at'], 'integer'],
            [['comment'], 'string'],
            [['promotional_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Номер заказа',
            'id_customer_profile' => 'ID пользователя, сделавшего заказ',
            'discount_card' => 'Штрихкод дисконтной карты',
            'promotional_code' => 'Промокод',
            'employee_code_ready' => 'ID работника, установившего статус заказа в \"ready\"',
            'ready_time' => 'Время изменения в интерфейсе статуса на ready',
            'employee_code_complete' => 'ID работника, установившего статус заказа \"completed\"',
            'complete_time' => 'Время изменения в интерфейсе статуса на completed',
            'employee_code_cancel' => 'ID работника, установившего статус заказа \"canceled\"',
            'cancel_time' => 'Время изменения в интерфейсе статуса на canceled',
            'comment' => 'Комментарий',
            'created_at' => 'Время создания записи',
        ];
    }
}
