<?php

namespace frontend\models;

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
            [['code'], 'required'],
            [['code', 'catalog_line_number'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['section_name_first', 'section_name_second', 'section_name_third'], 'string', 'max' => 32],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'catalog_line_number' => 'Catalog Line Number',
            'section_name_first' => 'Section Name First',
            'section_name_second' => 'Section Name Second',
            'section_name_third' => 'Section Name Third',
        ];
    }
}
