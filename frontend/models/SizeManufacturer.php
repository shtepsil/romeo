<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "size_manufacturer".
 *
 * @property int $id
 * @property string $code код размер производителя
 * @property string $name размер производителя
 */
class SizeManufacturer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'size_manufacturer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'код размер производителя',
            'name' => 'размер производителя',
        ];
    }
}
