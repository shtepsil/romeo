<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id код документа (уникальный) - идентификатор документа, каждому новому документу присваивается по возрастанию автоматически
 * @property int $document_correction_code Код корректируемого документа
 * @property int $document_type код тип документа - справочник, подставляется автоматически
 * @property int $document_date дата документа - вводится автоматически
 * @property int $organization_code код организации - вводится по умолчанию значение 001
 * @property int $vendor_code код поставщика - справочник, подставляется пользователем в веб интерфейсе
 * @property string $counterparty_document_comment документ контрагента, комментарий - вводится пользователем в веб интерфейсе
 * @property int $employee_code код работника - справочник, подставляется пользователем в веб интерфейсе
 * @property int $order_code_on_the_site код заказа на сайте - вводится пользователем в веб интерфейсе
 * @property string $action акция - выбирается из списка доступных акций пользователем в веб интерфейсе
 * @property string $promotional_code промокод - заполняется автоматически, если выбран код заказа на сайте и при заказе был использова промокод
 * @property string $name_buyers_document_comment Название, документ покупателя, комментарий - вводится пользователем в веб интерфейсе
 * @property string $buyer_phone_number телефонный номер покупателя - вводится пользователем в веб интерфейсе (если нужно отправить Чек ККМ)
 * @property string $buyer_email e-mail покупателя - вводится пользователем в веб интерфейсе (если нужно отправить Чек ККМ)
 * @property int $discount_card дисконтная карта - вводится сканером, проверяется по таблице дисконтные карты
 * @property int $payment_method_bank_card способ оплаты банковская карта - вводится пользователем в веб интерфейсе по умолчанию 1 - наличные, 0 - способ оплаты не выбран, 1 - наличные, 2 - банковская карта
 * @property int payment_amount сумма к оплате
 * @property int amount_of_refund_to_bank_card сумма возврата на банковскую карту
 * @property int cash_repayment_amount сумма возврата наличными
 *
 * @property GoodsMovement[] $goodsMovements
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'vendor_code',
                    'document_type',
                ],
                'required',
                'message' => 'обязательно для заполнения'
            ],
            [
                ['counterparty_document_comment'],
                'string',
                'message' => 'должно быть строкой'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID документа',
            'document_correction_code' => 'Код корректируемого документа',
            'document_type' => 'Поле "Тип поставки"',
//            'document_date' => '',
//            'organization_code' => '',
            'vendor_code' => 'Поле "Выберите поставщика"',
            'counterparty_document_comment' => 'Поле "Введите данные о документе поставщика"',
//            'employee_code' => '',
//            'order_code_on_the_site' => 'код заказа на сайте - вводится пользователем в веб интерфейсе',
//            'action' => 'акция - выбирается из списка доступных акций пользователем в веб интерфейсе',
//            'promotional_code' => 'промокод - заполняется автоматически, если выбран код заказа на сайте и при заказе был использова промокод',
//            'name_buyers_document_comment' => 'Название, документ покупателя, комментарий - вводится пользователем в веб интерфейсе',
//            'buyer_phone_number' => 'телефонный номер покупателя - вводится пользователем в веб интерфейсе (если нужно отправить Чек ККМ)',
//            'buyer_email' => 'e-mail покупателя - вводится пользователем в веб интерфейсе (если нужно отправить Чек ККМ)',
//            'discount_card' => 'дисконтная карта - вводится сканером, проверяется по таблице дисконтные карты',
//            'payment_method_bank_card' => 'способ оплаты банковская карта - вводится пользователем в веб интерфейсе по умолчанию 1 - наличные, 0 - способ оплаты не выбран, 1 - наличные, 2 - банковская карта',
//            'payment_amount' => 'сумма к оплате',
//            'amount_of_refund_to_bank_card' => 'сумма возврата на банковскую карту',
//            'cash_repayment_amount' => 'сумма возврата наличными',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getGoodsMovements()
//    {
//        return $this->hasMany(GoodsMovement::className(), ['id_code_dock' => 'id']);
//    }
}
