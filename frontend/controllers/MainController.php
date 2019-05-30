<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 24.04.2019
 * Time: 14:06
 */

namespace frontend\controllers;

use frontend\components\BasketProduct;
use Yii;
use frontend\models\User;

class MainController extends \backend\controllers\MainController
{

    // Получение сообщений из общего текстового массива
    public static function getMessage($name, $aReplace=null)
    {
        global $MESS;
        if(isset($MESS[$name])){
            $s = $MESS[$name];
            if($aReplace!==null && is_array($aReplace))
                foreach($aReplace as $search=>$replace)
                    $s = str_replace($search, $replace, $s);
            return $s;
        }else return $name;
    }

    // Получение товаров корзины
    public static function preAction(){
        // Получаем список товаров из корзины
        Yii::$app->params['basket_products'] =
            BasketProduct::getDataForBacketProducts();

        /**
         * Если пользователь авторизован
         * Запишем в голобальны массив $params данные о пользователе
         */
        Yii::$app->params['user'] = User::getUserData();;
    }
}

// Получаем товары из корзины
MainController::preAction();