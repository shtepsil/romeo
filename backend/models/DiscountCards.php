<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 19.06.2018
 * Time: 15:14
 */

namespace backend\models;

use yii\db\ActiveRecord;
use Yii;

class DiscountCards extends ActiveRecord{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discount_cards';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [
//                [
//                    'barcode',
//                    'name_of_the_holder',
//                    'phone_number',
//                    'accumulation_previous_year',
//                    'accumulation_current_year',
//                    'return_exchange_by_card',
//                    'discount'
//                ],
//                'required'
//            ],

            [['barcode'], 'unique'],
            [
                ['barcode'],
                'string',
                'max' => 13
            ],

//            [
//                ['name_of_the_holder'],
//                'string',
//                'max' => 64
//            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barcode' => 'Штрихкод дисконтной карты',
            'name_of_the_holder' => 'ФИО держателя',
            'phone_number' => 'Номер телефона',
            'accumulation_previous_year' => 'Накопление по карте за предыдущий год',
            'accumulation_current_year' => 'Накопление по карте за текущий год',
            'return_exchange_by_card' => 'Возврат, обмен по карте - суммы возврата и обмена зачисляются с минусом при обработке документов, с началом года сумма накопления за позапрошлый год зачисляется в плюс, при этом значение регистра устанавливается не более ноля.',
            'discount' => 'Скидка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getDocuments()
//    {
//        return $this->hasMany(Document::className(), ['discount_card' => 'id']);
//    }

}