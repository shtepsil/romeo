<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $barcode штрихкод (уникальный) - генерируется автоматически при поступлении товара см лист алгоритмы
 * @property int $item_code номенклатурный код товара - задается из таблицы номенклатура товара
 * @property int $code_manufacturer_size код размер производителя - справочник, подставляется пользователем в веб интерфейсе
 * @property int $code_size_russian код размер российский - справочник, подставляется пользователем в веб интерфейсе
 * @property int $code_growth_russian код рост российский - справочник, подставляется пользователем в веб интерфейсе
 * @property int $cost_of_goods себестоимость шт - вводится пользователем в веб интерфейсе
 * @property int $retail_price розничная цена - вводится пользователем в веб интерфейсе, может изменяться обработкой  переоценка
 * @property int $action_price цена по акции - заносится из файла excel аналогично полю "скидка"
 * @property int $automatic_discount автоматическая скидка - заносится из файла excel "скидка", см лист Обработки
 * @property string $date_of_promotion_discounts дата начало акции/скидки - заносится из файла excel "скидка", см лист Обработки
 * @property string $end_date_of_promotion_discount дата окончание акции/скидки - заносится из файла excel "скидка", см лист Обработки
 *
 * @property ProductNomenclature $itemCode
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
            [
                ['barcode', 'item_code', 'code_manufacturer_size', 'code_growth_russian', 'cost_of_goods', 'retail_price'],
                'required',
                'message' => 'обязательно для заполнения'
            ],
            [
                ['barcode', 'item_code', 'code_manufacturer_size', 'code_size_russian', 'code_growth_russian', 'action_price', 'automatic_discount'],
                'integer',
                'message' => 'должно содержать только цифры'],
            [['barcode'], 'unique','message' => 'обнаружено совпадение значения'],
            [
                ['cost_of_goods', 'retail_price'],
                'double',// значения с плавающей точкой
                'message' => 'должно содержать только цифры и запятые'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
//            'id' => 'ID',
            'barcode' => 'Значение "штрихкод"',
            'item_code' => 'Значение "Наименование клавиатуры"',
            'code_manufacturer_size' => 'Поле "Размер производителя"',
            'code_size_russian' => 'Поле "Размер российский"',
//            'code_growth_russian' => 'код рост российский - справочник, подставляется пользователем в веб интерфейсе',
            'cost_of_goods' => 'Поле "Себестоимость"',
            'retail_price' => 'Поле "Розничная цена"',
//            'action_price' => 'цена по акции - заносится из файла excel аналогично полю \"скидка\"',
//            'automatic_discount' => 'автоматическая скидка - заносится из файла excel \"скидка\", см лист Обработки',
//            'date_of_promotion_discounts' => 'дата начало акции/скидки - заносится из файла excel \"скидка\", см лист Обработки',
//            'end_date_of_promotion_discount' => 'дата окончание акции/скидки - заносится из файла excel \"скидка\", см лист Обработки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCode()
    {
        return $this->hasOne(ProductNomenclature::className(), ['id' => 'item_code']);
    }
}
