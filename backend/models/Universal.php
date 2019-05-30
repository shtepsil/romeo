<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provider".
 *
 * @property int $id
 * @property string $code код поставщик
 * @property string $name наименование поставщика
 */
class Universal extends \yii\db\ActiveRecord
{

    public static $tn;

    public function __construct($tn)
    {
        self::$tn = $tn;
//        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        $tnn = self::$tn;
        return $tnn;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
//        return [
//            [['code'], 'required'],
//            [['code'], 'integer'],
//            [['name'], 'string', 'max' => 32],
//        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
//        return [
//            'id' => 'ID',
//            'code' => 'код поставщик',
//            'name' => 'наименование поставщика',
//        ];
    }
}
