<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "ads".
 *
 * @property string $id
 * @property string $name Наименование рекламы
 * @property string $link Ссылка рекламы
 * @property int $counter Счетчик переходов
 */
class Ads extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['counter'], 'integer'],
            [['link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование рекламы',
            'link' => 'Ссылка рекламы',
            'counter' => 'Счетчик переходов',
        ];
    }
}
