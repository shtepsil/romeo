<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_group".
 *
 * @property int $id
 * @property string $code код товарной группы
 * @property string $name товарная группа
 * @property int $catalog_line_number номер строки каталога
 * @property string $section_name_first наименование раздела первый уровень
 * @property string $section_name_second наименование раздела второй уровень
 * @property string $section_name_third наименование раздела третий уровень
 */
class ProductGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required', 'message' => 'Поле "Код значения" обязательно для заполнения'],
            [['name'], 'required', 'message' => 'Поле "Значение справочника" не может быть пустым'],
            [['code'], 'integer', 'message' => 'В поле "Код значения" должны быть только цифры'],
            [['code'], 'unique', 'message' => 'Обнаружен дубликат поля "Код значения"'],
            [['name'], 'unique', 'message' => 'Обнаружен дубликат поля "Значение справочника"'],
            [['name'], 'string', 'max' => 100, 'message' => 'Масимальая длинна поля 100 символа'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'код товарной группы',
            'name' => 'товарная группа',
            'catalog_line_number' => 'номер строки каталога',
            'section_name_first' => 'наименование раздела первый уровень',
            'section_name_second' => 'наименование раздела второй уровень',
            'section_name_third' => 'наименование раздела третий уровень',
        ];
    }
}
