<?php

namespace backend\components;

use frontend\controllers\MainController as d;
use Yii;

class SiteHelper
{
    /*
     * Проверка, где находится сайт
     * на сервере или на локальном компьютере
     */
    public static function amountToDiscountThreshold($data){

        $atdt = $data['sum'];

        // Если сумма не ноль
        if($data['sum'] != 0){
            /**
             * сумма для вычисления скидки
             * ===========================
             * accumulation_current_year ПЛЮС сумма возвратов, обменов
             */
            $preDiscount = (
                $data['sum'] +
                // число уже с минусом. Такое пришло из БД
                $data['return_exchange_by_card']
            );

            /**
             * Если сумма больше либо равно минимальной сумме для скидки,
             * то вычисляем скидку. Иначе скидки нет.
             * Перебираем объект
             * получаем соответствующую скидку
             * =========================================================
             * Если скидки нет, то в БД поле "скидка" не изменится.
             * 5000, 15000, 30000, 50000
             * atdt - amount to discount threshold
             */
            $dt = Yii::$app->params['discount_thresholds'];
            // Остаток до первой скидки - 5%
            if($preDiscount < $dt['d5'][1]) {
                $atdt = ($dt['d5'][1] - $preDiscount);
            }else if($preDiscount >= $dt['d5'][1] && $preDiscount < $dt['d10'][1]){
                $atdt = ($dt['d10'][1] - $preDiscount);
            }// Скидка 10%
            else if($preDiscount >= $dt['d10'][1] && $preDiscount < $dt['d15'][1]){
                $atdt = ($dt['d15'][1] - $preDiscount);
            }// Скидка 15%
            else if($preDiscount >= $dt['d15'][1] && $preDiscount < $dt['d20'][1]){
                $atdt = ($dt['d20'][1] - $preDiscount);
            }// Скидка 20%
            else if($preDiscount >= $dt['d20'][1]){
                $atdt = 0;
            }
        }

//        d::pe('Сумма: '.$preDiscount.'<br>До скидки нужно ещё: '.$atdt);

        return $atdt;
    }
}