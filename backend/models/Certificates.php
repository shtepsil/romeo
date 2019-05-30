<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "certificates".
 *
 * @property int $id
 * @property string $barcode_unique штрихкод (уникальный) - заполняется вводом из файла excel "сертификаты"
 * @property int $certificate_denomination номинал сертификата - заполняется вводом из файла excel "сертификаты"
 * @property int $accrued оприходован - начальное значение "нет", изменяется при обработке документов
 * @property string $capitalization_date дата оприходования - вводится автоматически
 * @property int $employee_code_capitalization код работника (оприходование) - справочник, подставляется пользователем в веб интерфейсе
 * @property int $sold_out продан - начальное значение "нет", изменяется при обработке документов
 * @property string $date_of_sale дата продажи - вводится автоматически
 * @property int $document_id_sale код документа (продажа) - справочник, подставляется пользователем в веб интерфейсе
 * @property int $cooked отоварен - начальное значение "нет", изменяется при обработке документов
 * @property string $date_of_digestion дата отоваривания - вводится автоматически
 * @property int $document_cid_digestion код документа (отоваривание) - справочник, подставляется пользователем в веб интерфейсе
 *
 * @property Document $documentCodeSale
 * @property Document $documentCodeDigestion
 */
class Certificates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'certificates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['barcode', 'certificate_denomination', 'capitalization_date', 'employee_code_capitalization', 'date_of_sale', 'document_code_sale', 'date_of_digestion', 'document_code_digestion'], 'required'],
//
//            [['certificate_denomination', 'accrued', 'employee_code_capitalization', 'sold_out', 'document_id_sale', 'cooked', 'document_id_digestion'], 'integer'],
            [['barcode'], 'string', 'max' => 13],
//
//            [['capitalization_date', 'date_of_sale', 'date_of_digestion'], 'string', 'max' => 10],
//
            [['barcode'], 'unique'],
//
//            [['document_code_sale'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id_sale' => 'id']],
//
//            [['document_code_digestion'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id_digestion' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barcode' => 'Штрихкод',
            'certificate_denomination' => 'Certificate Denomination',
            'accrued' => 'Accrued',
            'capitalization_date' => 'Capitalization Date',
            'employee_code_capitalization' => 'Employee Code Capitalization',
            'sold_out' => 'Sold Out',
            'date_of_sale' => 'Date Of Sale',
            'document_code_sale' => 'Document Code Sale',
            'cooked' => 'Cooked',
            'date_of_digestion' => 'Date Of Digestion',
            'document_code_digestion' => 'Document Code Digestion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentCodeSale()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id_sale']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentCodeDigestion()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id_digestion']);
    }
}
