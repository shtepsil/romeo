<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files_excel".
 *
 * @property int $id
 * @property string $name имя файла и одновременно unix метка
 * @property string $ext расширение файла
 */
class FilesExcel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files_excel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['ext'], 'required'],
//            [['name'], 'string', 'max' => 30],
//            [['ext'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ext' => 'Ext',
        ];
    }
}
