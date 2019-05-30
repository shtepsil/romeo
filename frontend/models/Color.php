<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "color".
 *
 * @property int $id
 * @property string $code код цвет
 * @property string $name цвет
 */
class Color extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'color';
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
            'code' => 'код цвет',
            'name' => 'цвет',
        ];
    }
}
