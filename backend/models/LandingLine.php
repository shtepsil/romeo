<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "landing_line".
 *
 * @property int $id
 * @property int $code код линия посадки
 * @property string $name линия посадки
 */
class LandingLine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'landing_line';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required', 'message' => 'Поле "Код значения" обязательно для заполнения'],
            [['name'], 'required', 'message' => 'Поле "Значение справочника" не может быть пустым'],
            [['name'], 'unique', 'message' => 'Обнаружен дубликат поля "Значение справочника"'],
            [['code'], 'integer', 'message' => 'В поле "Код значения" должны быть только цифры'],
//            [['code'], 'unique', 'message' => 'Обнаружен дубликат поля "Код значения"'],
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
            'code' => 'код линия посадки',
            'name' => 'линия посадки',
        ];
    }
}
