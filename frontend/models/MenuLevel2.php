<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "menu_level_2".
 *
 * @property string $id
 * @property int $id_menu_level_1 ID основного меню
 * @property string $name Намиенование группы каталога
 * @property string $link Ссылка
 * @property int $sort Сортировка
 * @property int $visibility Отображанть (да-1/нет-0)
 */
class MenuLevel2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_level_2';
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
            'id_menu_level_1' => 'ID основного меню',
            'name' => 'Намиенование группы каталога',
            'url' => 'Адрес ссылки',
            'sort' => 'Сортировка',
            'visibility' => 'Отображанть (да-1/нет-0)',
        ];
    }
}
