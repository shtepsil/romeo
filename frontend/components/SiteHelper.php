<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 13.05.2019
 * Time: 18:23
 */

namespace frontend\components;

use frontend\controllers\MainController as d;
use Yii;

class SiteHelper
{
    /*
     * Проверка, где находится сайт
     * на сервере или на локальном компьютере
     */
    public static function isLocal(){
        preg_match('/\./',Yii::$app->request->hostName,$matshes);
        /*
         * Если в адресе сайта найдена точка
         * то сайт на сервере
         */
        if($matshes) return false;
        // Иначе это локалка
        else return true;
    }
}