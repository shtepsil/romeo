<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id код тип документа
 * @property string $code
 * @property string $name тип документа
 */
class DocumentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_type';
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
            [['name'], 'unique', 'message' => 'Обнаружен дубликат поля "Значение справочника"'],
            [['code'], 'unique', 'message' => 'Обнаружен дубликат поля "Код значения"'],
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
            'code' => 'Код документа',
            'name' => 'Наименование типа документа',
        ];
    }

    /**
     * @return array
     * Получаем одну строку таблицы по ID
     */
    public static function getOne($id){
        return self::find()
            ->where(['id' => $id])
            ->one();
    }
}
