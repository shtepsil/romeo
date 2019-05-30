<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "menu_level_3".
 *
 * @property string $id
 * @property int $id_menu_level_1 ID строки меню 2 (принадлежность к группе каталога)
 * @property string $name Намиенование группы товаров единицы каталога
 * @property string $link Ссылка
 * @property int $sort Сортировка
 * @property int $visibility Отображение (да-1/нет-0)
 */
class MenuLevel3 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_level_3';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_menu_level_1', 'sort', 'visibility'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_menu_level_1' => 'ID строки меню 2 (принадлежность к группе каталога)',
            'name' => 'Намиенование группы товаров единицы каталога',
            'url' => 'Адрес ссылки',
            'sort' => 'Сортировка',
            'visibility' => 'Отображение (да-1/нет-0)',
        ];
    }
}
