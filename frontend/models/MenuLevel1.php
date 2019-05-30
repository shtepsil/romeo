<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "menu_level_1".
 *
 * @property string $id
 * @property string $name Наименование пункта меню
 * @property string $code Код имени (наименование в транслите)
 * @property string $url Адрес ссылки
 * @property int $sort Сортировака. Порядок отображения
 * @property int $visibility Отображение (да-1/нет-0)
 */
class MenuLevel1 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_level_1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'visibility'], 'integer'],
            [['name', 'code'], 'string', 'max' => 100],
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
            'name' => 'Наименование пункта меню',
            'code' => 'Код имени (наименование в транслите)',
            'url' => 'Адрес ссылки',
            'sort' => 'Сортировака. Порядок отображения',
            'visibility' => 'Отображение (да-1/нет-0)',
        ];
    }
}
