<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 07.05.2019
 * Time: 15:28
 */

namespace backend\components;

use backend\controllers\MainController as d;
use Yii;
use backend\models\Orders;

class GetData
{
    public static function getOrders($type = ''){

        $orders = Orders::find();

        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            // Если в POST запросе существует параметр "ready_orders"
            if($post['ready_orders']) $type = 'ready_orders';
        }

        // Если нужно получить "Готовые" заказы
        if($type == 'ready_orders'){
            $orders->where(['complete_time'=>NULL,'cancel_time'=>NULL])
                ->andWhere(['NOT',['ready_time'=>NULL]]);
        }else{
            $orders->where(['ready_time'=>NULL]);
        }

        if($orders) return $orders->orderBy('id')->asArray()->all();
        else return false;

    }
}