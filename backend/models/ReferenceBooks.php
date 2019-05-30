<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reference_books".
 *
 * @property int $id
 * @property string $name имя справочника
 * @property string $value Значение value, совпадающее с именем таблицы в БД
 * @property int $sort Сортировка
 */
class ReferenceBooks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reference_books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['name', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'имя справочника',
            'value' => 'Значение value, совпадающее с именем таблицы в БД',
            'sort' => 'Сортировка',
        ];
    }
}
