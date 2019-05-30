<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $code код пол
 * @property string $name пол
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
//            [['code'], 'required', 'message' => 'Поле "Код значения" обязательно для заполнения'],
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
            'id_customer_profile'=>'ID пользователя, сделавшего заказ',
            'discount_card'=>'Штрихкод дисконтной карты',
            'promotional_code'=>'Промокод',
            'employee_code_ready'=>'ID работника, установившего статус заказа в "ready"',
            'ready_time'=>'Время изменения в интерфейсе статуса на ready',
            'employee_code_complete'=>'ID работника, установившего статус заказа "completed"',
            'complete_time'=>'Время изменения в интерфейсе статуса на completed',
            'employee_code_cancel'=>'ID работника, установившего статус заказа "canceled"',
            'cancel_time'=>'Время изменения в интерфейсе статуса на canceled',
            'comment'=>'Комментарий',
            'created_at'=>'Время создания записи',
        ];
    }
}
