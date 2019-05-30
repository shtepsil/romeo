<?php

use yii\db\Migration;

/**
 * Class m180605_105447_rbac
 */
class m180605_105447_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $role = Yii::$app->authManager->createRole('sadmin');
//        $role->description = 'Супер Админ';
//        Yii::$app->authManager->add($role);
//
//        $role = Yii::$app->authManager->createRole('admin');
//        $role->description = 'Админ';
//        Yii::$app->authManager->add($role);
//
//        $role = Yii::$app->authManager->createRole('user');
//        $role->description = 'Пользователь';
//        Yii::$app->authManager->add($role);

        $userRole = Yii::$app->authManager->getRole('user');
        Yii::$app->authManager->assign($userRole, Yii::$app->user->getId());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180605_105447_rbac cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180605_105447_rbac cannot be reverted.\n";

        return false;
    }
    */
}
