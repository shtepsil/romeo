<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vendor_code".
 *
 * @property int $id
 * @property int $brand_id ID бренда соответствующего артикула
 * @property string $name Наименование артикула
 */
class VendorCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vendor_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            // валидация по нескольким полям
            [['brand_id', 'name'], 'unique', 'targetAttribute' => ['brand_id', 'name'], 'message' => 'Обнаружено совпадение'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Бренд соответствующего артикула',
            'name' => 'Наименование артикула',
        ];
    }
}
