<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order_products".
 *
 * @property string $id
 * @property int $orders_id ID таблицы orders
 * @property string $barcode Штрихкод товара из таблицы product
 */
class OrderProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orders_id', 'barcode'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orders_id' => 'ID таблицы orders',
            'barcode' => 'Штрихкод товара из таблицы product',
        ];
    }
}
