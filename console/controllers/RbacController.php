<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\rbac\UserRoleRule;

class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //удаляем старые данные

        //Создадим для примера права для доступа к админке
        $dashboard = $auth->createPermission('adminPanel');
        $dashboard->description = 'Админ панель';
        $auth->add($dashboard);

        //Добавляем объект определяющий правила для ролей пользователей, он будет сохранен в файл rules.php
        $rule = new UserRoleRule();
        $auth->add($rule);

        //Добавляем роли
        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $user->ruleName = $rule->name;
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $admin->description = 'Модератор';
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        //Добавляем потомков
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $dashboard);


        $sadmin = $auth->createRole('sadmin');
        $sadmin->description = 'Администратор';
        $sadmin->ruleName = $rule->name;
        $auth->add($sadmin);
        $auth->addChild($sadmin, $admin);
    }

}