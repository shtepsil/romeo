<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 11.07.2018
 * Time: 18:27
 *
 * Операции с числами штрихкода
 *
 */

namespace common\components;

class Barcodes
{
    /**
     * Генерирует строку штрихкода
     * @return string
     */
    public static function barcodeGenEanSum($ean){
        $even=true; $esum=0; $osum=0;
        for ($i=strlen($ean)-1;$i>=0;$i--){
            if ($even) $esum+=$ean[$i];	else $osum+=$ean[$i];
            $even=!$even;
        }
        $ean_sum = (10-((3*$esum+$osum)%10))%10;
        return $ean.$ean_sum;
    }

    /**
     * Инкрементирует порядковый номер штрихкода
     * @return string
     */
    public static function arcadeNumberIncrement($arr){

        $serial_numbers = array();

        foreach($arr as $bc){
            // выбираем все поярдковые номера
            $serial_numbers[] = substr($bc['barcode'], 7, 5);
        }

        // берем максимальное значение
        $numbers = max($serial_numbers);

        // Инкрементируем число
        $number = ($numbers+1);

        // собираем число из пяти символов с ведущими нулями
        switch(strlen($number)){
            case 1:
                $serial_number = '0000'.$number;
                break;
            case 2:
                $serial_number = '000'.$number;
                break;
            case 3:
                $serial_number = '00'.$number;
                break;
            case 4:
                $serial_number = '0'.$number;
                break;
            default: $serial_number = $number;
        }

        return $serial_number;
    }
}