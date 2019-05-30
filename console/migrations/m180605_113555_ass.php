<?php

use yii\db\Migration;

/**
 * Class m180605_113555_ass
 */
class m180605_113555_ass extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $userRole = Yii::$app->authManager->getRole('user');
        Yii::$app->authManager->assign($userRole, '7');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180605_113555_ass cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180605_113555_ass cannot be reverted.\n";

        return false;
    }
    */
}
