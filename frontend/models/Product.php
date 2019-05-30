<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $barcode штрихкод (уникальный) - генерируется автоматически при поступлении товара см лист алгоритмы
 * @property int $item_code номенклатурный код товара - задается из таблицы номенклатура товара
 * @property int $code_manufacturer_size код размер производителя - справочник, подставляется пользователем в веб интерфейсе
 * @property int $code_size_russian код размер российский - справочник, подставляется пользователем в веб интерфейсе
 * @property int $code_growth_russian код рост российский - справочник, подставляется пользователем в веб интерфейсе
 * @property double $cost_of_goods себестоимость шт - вводится пользователем в веб интерфейсе
 * @property double $retail_price розничная цена - вводится пользователем в веб интерфейсе, может изменяться обработкой  переоценка
 * @property double $action_price цена по акции - заносится из файла excel аналогично полю "скидка"
 * @property int $automatic_discount автоматическая скидка - заносится из файла excel "скидка", см лист Обработки
 * @property string $date_of_promotion_discounts дата начало акции/скидки - заносится из файла excel "скидка", см лист Обработки
 * @property string $end_date_of_promotion_discount дата окончание акции/скидки - заносится из файла excel "скидка", см лист Обработки
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barcode', 'item_code', 'code_manufacturer_size', 'code_size_russian', 'code_growth_russian', 'cost_of_goods', 'retail_price', 'action_price', 'automatic_discount', 'date_of_promotion_discounts', 'end_date_of_promotion_discount'], 'required'],
            [['barcode', 'item_code', 'code_manufacturer_size', 'code_size_russian', 'code_growth_russian', 'automatic_discount'], 'integer'],
            [['cost_of_goods', 'retail_price', 'action_price'], 'number'],
            [['date_of_promotion_discounts', 'end_date_of_promotion_discount'], 'safe'],
            [['barcode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barcode' => 'штрихкод (уникальный) - генерируется автоматически при поступлении товара см лист алгоритмы',
            'item_code' => 'номенклатурный код товара - задается из таблицы номенклатура товара',
            'code_manufacturer_size' => 'код размер производителя - справочник, подставляется пользователем в веб интерфейсе',
            'code_size_russian' => 'код размер российский - справочник, подставляется пользователем в веб интерфейсе',
            'code_growth_russian' => 'код рост российский - справочник, подставляется пользователем в веб интерфейсе',
            'cost_of_goods' => 'себестоимость шт - вводится пользователем в веб интерфейсе',
            'retail_price' => 'розничная цена - вводится пользователем в веб интерфейсе, может изменяться обработкой  переоценка',
            'action_price' => 'цена по акции - заносится из файла excel аналогично полю \"скидка\"',
            'automatic_discount' => 'автоматическая скидка - заносится из файла excel \"скидка\", см лист Обработки',
            'date_of_promotion_discounts' => 'дата начало акции/скидки - заносится из файла excel \"скидка\", см лист Обработки',
            'end_date_of_promotion_discount' => 'дата окончание акции/скидки - заносится из файла excel \"скидка\", см лист Обработки',
        ];
    }
}
