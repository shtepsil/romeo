<?php
namespace backend\controllers;

use frontend\components\BasketProduct;
use frontend\models\CustomerData;
use frontend\models\User;
use yii\web\Controller;
use Yii;

class MainController extends Controller{
    /*
     * Скрипты для разработки
     */
    public static function pri($arr){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
    public static function pre($str){
        self::s();
        echo '<pre>';
        print_r($str);
        echo '</pre>';
        self::e();
    }

    public static function prebl($str){
        self::sbl();
        echo '<pre>';
        print_r($str);
        echo '</pre>';
        self::e();
    }

    public static function pretr($str){
        self::s_tr();
        echo '<pre>';
        print_r($str);
        echo '</pre>';
        self::e();
    }

    public static function prebr($str){
        self::s_br();
        echo '<pre>';
        print_r($str);
        echo '</pre>';
        self::e();
    }

    private static function s(){
        echo '<div
        style="
            position: fixed;
            top: 60px;
            left: 0px;
            padding: 15px;
            background-color: black;
            min-width: 265px;
            z-index: 99999999;
            color: white;
            overflow: auto;
        ">
        ';
    }

    private static function sbl(){
        echo '<div
          style="
            position: fixed;
            bottom: 5px;
            left: 0px;
            padding: 15px;
            background-color: black;
            min-width: 265px;
            z-index: 99999999;
            color: white;
            overflow: auto;
          ">
        ';
    }

    private static function s_tr(){
        echo '<div
          style="
            position: fixed;
            top: 60px;
            right: 0px;
            padding: 15px;
            background-color: black;
            min-width: 265px;
            z-index: 99999999;
            color: white;
            overflow: auto;
          ">
        ';
    }

    private static function s_br(){
        echo '<div
          style="
            position: fixed;
            bottom: 5px;
            right: 0px;
            padding: 15px;
            background-color: black;
            min-width: 265px;
            z-index: 99999999;
            color: white;
            overflow: auto;
          ">
        ';
    }

    private static function e(){
        echo '</div>';
    }

    /**
     * Получение расширения файла.
     *
     * @return string
     */
    public static function getExtension($string){
        $revstr = strrev($string);
        $position = strpos($revstr, '.');
        $str_itog_rev = substr($revstr,0,$position);
        $str_itog = strrev($str_itog_rev);

        return $str_itog;
    }

    /**
     * Получение части строки
     * после последнего вхождения символа $haracter
     *
     * @return string
     */
    public static function getPartStrByCharacter($string,$haracter,$start = false,$last = false,$all_from_first = false){

        // Получаем всю строку, до ПЕРВОГО символа $haracter
        if($start) {
            $pos = strpos($string, $haracter);
            if($pos != '') $str = substr($string, 0, $pos);
            else $str = $string;
            return $str;
        }
        // Получаем всю строку, до ПОСЛЕДНЕГО символа $haracter
        if($last) {
            $pos = mb_strripos($string, $haracter);
            if($pos != '') $str = substr($string, 0, $pos);
            else $str = $string;
            return $str;
        }
        // ОТ первого символа $haracter - берем всю строку до конца
        if($all_from_first) {
            $pos = strpos($string, $haracter);
            if($pos != '') $str = substr($string, $pos+1);
            else $str = $string;
            return $str;
        }

        $revstr = strrev($string);
        $position = strpos($revstr, $haracter);
        $str_itog_rev = substr($revstr,0,$position);
        $str_itog = strrev($str_itog_rev);

        return $str_itog;
    }

    /*
     * Обезопасиваем данные
    */
    public static function secureEncode($data,$array = true) {

        // По умолчанию обрабатывается массив

        if(!$array) {

            // если нужно обработать строку
            $data = trim($data);
//            $data = htmlspecialchars($data, ENT_QUOTES);
            $data = htmlspecialchars($data, ENT_NOQUOTES);
            $data = addslashes($data);
            $data = str_replace('\\r\\n', '<br>', $data);
            $data = str_replace('\\r', '<br>', $data);
            $data = str_replace('\\n\\n', '<br><br>', $data);
            $data = str_replace('\\n\\n\\n', '<br><br><br>', $data);
            $data = str_replace('\\n', '<br>', $data);
            $data = stripslashes($data);
            $data = str_replace('&amp;#', '&#', $data);
            $data = str_replace('&amp;', '&', $data);
        }else{
            // если нужно обработать массив
            $response_array = array();
            foreach($data as $key=>$value){
                /*
                 * Если массив многомерный
                 * и в рекурсии вместо строки пришел массив
                 */
                if(is_array($value)) $response_array[$key] = self::secureEncode($value);
                else $response_array[$key] = self::secureEncode($value,false);
            }
            $data = $response_array;
        }

        return $data;
    }

    /*
     * Перевод языка онлайн
    */
    public static function translation($str, $lang_from, $lang_to) {

        $query_data = array(
            'client' => 'x',
            'q' => $str,
            'sl' => $lang_from,
            'tl' => $lang_to
        );
        $filename = 'http://translate.google.ru/translate_a/t';
        $options = array(
            'http' => array(
                'user_agent' => 'Mozilla/5.0 (Windows NT 6.0; rv:26.0) Gecko/20100101 Firefox/26.0',
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($query_data)
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents($filename, false, $context);

        return $response;
    }

    public static function active($action){
        return (self::getPartStrByCharacter(Yii::$app->request->pathInfo,'/',true) == $action);
    }

    public static function activeMainMenu($action){
//        if((self::getPartStrByCharacter(Yii::$app->request->pathInfo,'/',false,false,true) == $action))
//            self::tdfa(self::getPartStrByCharacter(Yii::$app->request->pathInfo,'/',false,false,true) .'=='. $action);
        return (self::getPartStrByCharacter(Yii::$app->request->pathInfo,'/',false,false,true) == $action);
    }

    public static function activeForMultiLevelMenu($action){

        if(self::getPartStrByCharacter(Yii::$app->request->url,'?') != '') {

            /*
             * Берем из строки всё, что идет после вопроса
             * т.е. весь get
             */
            $uri = self::getPartStrByCharacter(Yii::$app->request->url, '?');

            if(Yii::$app->request->get()['id'] != ''){
                /*
                 * От начала берем часть строки,
                 * до последнего вхождения символа: '&'
                 */
                $tpl = self::getPartStrByCharacter($uri,'&',false,true);
            }else{
                // От начала uri отсекаем всё лишнее
                $tpl_pre = self::getPartStrByCharacter($uri,'&',false,false,true);
                // От последнего вхождения отсекаем всё лишнее
                $tpl = self::getPartStrByCharacter($tpl_pre,'&',false,true);

            }

            // Строка, в которой производим поиск
            $string = self::getPartStrByCharacter($action, '?');

//            self::tdfa($tpl.'--'.$string);

            preg_match('/'.$tpl.'/',$string, $res);

            if($res) return true;
//            if($res) self::tdfa('find');
//            else self::tdfa('no');
        }
//        return (self::getPartStrByCharacter(Yii::$app->request->url,'?',true) == $action);
    }

    /*
     * Получение ошибок из validation models
     * $model - модель валидатора
     * $arr_error - массив ошибок: $model->errors
    */
    public static function getErrors($model,$arr_error) {
		$error_string = '';
		foreach($arr_error as $key=>$val){
			$arr_labels = $model->attributeLabels();
			$error_string .= '<b>'.$arr_labels[$key].'</b> - '.$val[0].'<br>';
		}
		return $error_string;
    }

    /*
     * Возврат json строки
     * для отладки
    */
    public static function eje($arr) {
        echo json_encode($arr);
        exit();
    }

    /*
     * Возврат json строки
     * для отладки
    */
    public static function pj($arr) {
        print_r(json_encode($arr));
    }

    /*
     * Распечатка массива
     * для отладки в Ajax
    */
    public static function pe($arr) {
        echo '<br>';
        echo self::toString($arr);
        exit();
    }

    /*
     * Распечатка массива
     * для отладки в Ajax
    */
    public static function jpe($arr) {
        $arr = json_encode($arr);
        print_r($arr);
        exit();
    }

    /*
     * Запись результатов в файл debug.txt
     * для отладки в Ajax
    */
    public static function arrToStr($data) {
        $str = '';
        $i = 0;
        if(is_array($data) OR is_object($data)){
            foreach($data as $key=>$value){
                if(is_array($value) OR is_object($value)){
                    $str .= $key.'=='.self::arrToStr($value).' ';
                }else {
                    $str .= (($i == 0) ? '>' : '').$key.'=>'.$value.', ';
                }
                $i++;
            }
        }else $str = $data;

        return $str;
    }

    /*
     * Запись результатов в файл debug.txt
     * для отладки в Ajax
    */
    public static function tdArrStr($data) {
        $str = self::arrToStr($data);
        file_put_contents('debug.txt',$str);
    }

    /*
     * Запись результатов в файл debug.txt
     * для отладки в Ajax
    */
    public static function td($data) {
        file_put_contents('debug.txt',$data);
    }

    /*
     * Запись результатов в файл debug.txt
     * для отладки в Ajax
    */
    public static function tdfa($data) {
        file_put_contents('debug.txt',PHP_EOL.$data,FILE_APPEND);
    }

    /*
     * Запись результатов в файл debug.txt
     * для отладки в Ajax
    */
    public static function jtd($data) {
        $data = json_encode($data);
        file_put_contents('debug.txt',$data);
    }

    /*
     * Запись результатов в файл debug.txt
     * для отладки в Ajax
    */
    public static function jtdfa($data) {
        $data = json_encode($data);
        file_put_contents('debug.txt',PHP_EOL.$data,FILE_APPEND);
    }

    /*
     * Преобразвание массива в строку
     * для отладки в Ajax
    */
    public static function strpe($arr,$field=false) {
        $str = '<br>';
        foreach($arr as $key=>$value){
            if($field) $str .= $key.'=>'.$value[$field].'<br>';
            else $str .= $key.'=>'.$value.'<br>';
        }
        print_r($str);
        exit();
    }

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

    // Из Json в Array
    public static function jsonToArray($data){
        $array = [];
        if(self::isJson($data)){
            $data = json_decode($data);
        }
        foreach($data as $key=>$value){
            if(is_object($value)) $array[$key] = self::jsonToArray($value);
            else $array[$key] = $value;
        }
        return $array;
    }

    // Проверка на Json
    public static function isJson($string) {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    // Делаем строку из массива/объекта
    public static function toString($data){
        $str = '';
        $i = 0;
        if(is_array($data) OR is_object($data)){
            foreach($data as $key=>$value){
                if(is_array($value) OR is_object($value)){
                    $str .= '<br>' .
                        '<span style=\'color: red;\'>'.$key.'</span>'.
                        '==<span style=\'color: blue;\'>'.
                        self::toString($value).'</span>'.' ';
                }else {
                    $str .=
                        (($i == 0) ? '>' : '') .
                        '<span style=\'color: red;\'>'.$key.'</span>'.
                        '=><span style=\'color: blue;\'>'.$value.'</span>'.', ';
                }
                $i++;
            }
        }else $str = '<span style=\'color: blue;\'>'.$data.'</span>';
        return $str.'<br>';
    }

    /*
     * Обрабатываем имя файла
     * Удаляем не нужные знаки
     */
    public static function clearStr($str){
        $from = array('%5B','%5D');
        $to = array('[',']');

        return str_replace($from, $to, $str);
    }

    /*
     * Преобразование объекта выборки в массив
     */
    public static function objectToArray($obj){
        $arr = [];
        foreach($obj as $key=>$item){
            if(is_object($item)) $arr[$key] = self::objectToArray($item);
            else $arr[$key] = $item;
        }
        return $arr;
    }

    // меняем форматы дат
    public static function changeDate($date,$to = '',$format=false){

        if($to === 'rus'){
            // Берем только день
            if($format == 'd') $d = date( 'd', strtotime($date));
            // Берем только месяц
            if($format == 'm') $d = date( 'F', strtotime($date));
            // Берем только день и месяц
            if($format == 'dm') $d = date( 'd F', strtotime($date));
            // Берем день, месяц и год
            else  $d = date( 'd F Y', strtotime($date));

            $d = explode(' ',$d);
            $months = [
                'January'=>'Января',
                'February'=>'Февраля',
                'March'=>'Марта',
                'April'=>'Апреля',
                'May'=>'Мая',
                'June'=>'Июня',
                'July'=>'Июля',
                'August'=>'Августа',
                'September'=>'Сентября',
                'October'=>'Октября',
                'November'=>'Ноября',
                'December'=>'Декабря'
            ];
            foreach($months as $key=>$val){
                if($key == $d[1])$d[1] = $val;
            }
            return implode(' ',$d);
        }elseif($to === 'number'){

            $d = explode(' ',$date);
            $months_num = [
                'Января'=>'01',
                'Февраля'=>'02',
                'Марта'=>'03',
                'Апреля'=>'04',
                'Мая'=>'05',
                'Июня'=>'06',
                'Июля'=>'07',
                'Августа'=>'08',
                'Сентября'=>'09',
                'Октября'=>'10',
                'Ноября'=>'11',
                'Декабря'=>'12'
            ];
            $d[1] = $months_num[$d[1]];
            return date('Y-m-d', strtotime(implode('-',$d)));

        }elseif($to === 'format'){

            // Удаляем все цифры из формата даты
            $separators = preg_replace('/[A-Za-z]+/', '', $format);
            /*
             * Получаем разделитель
             * который нам нужен
             */
            $s = $separators[0];

            // Удаляем все цифры из формата входящей даты
            $separators_in = preg_replace('/[0-9]+/', '', $date);
            // Получаем разделитель входящей даты
            $s_in = $separators_in[0];

            // делаем массив из элементов входящей даты
            $date_elements = explode($s_in,$date);
            /*
             * Делаем дату, которая будет форматироваться
             * функцией strtotime
             * тем самым определим(узнаем) элементы
             * какой элемент есть год
             * какой элемент есть месяц
             * какой элемент есть день
             */
            $time = strtotime(
                $date_elements[0].'-'.
                $date_elements[1].'-'.
                $date_elements[2]
            );

            if($format == 'yyyy'.$s.'mm'.$s.'dd')
                return date('Y'.$s.'m'.$s.'d', $time);
            if($format == 'dd'.$s.'mm'.$s.'yyyy')
                return date('d'.$s.'m'.$s.'Y', $time);
            if($format == 'mm'.$s.'dd'.$s.'yyyy')
                return date('m'.$s.'d'.$s.'Y', $time);
        }elseif($to === 'date'){
            return self::changeDate(date( 'Y-m-d', $date),'rus');
        }
    }

    // Ответ Ajax запроса
    public static function echoAjax($data){
        header('Content-type: text/json');
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    /* Количество заказов */

}// Class

