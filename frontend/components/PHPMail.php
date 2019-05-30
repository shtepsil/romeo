<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 13.05.2019
 * Time: 18:14
 */

namespace frontend\components;

use frontend\controllers\MainController as d;
use Yii;
use yii\base\Exception;

class PHPMail
{
    private $prop = [];
    private $response = true;

    public function tpl($template = false,$params = []){
        $this->prop['body'] = Yii::$app->view->renderFile(
            '@app/views/ajax/shortcodes/email/'.$template.'.php',$params);
        return $this;
    }

    // Куда направить ответ
    public function reply_to($reply_to = false){
        if(!$reply_to) $reply_to = Yii::$app->params['admin_email'];
        $this->prop['reply_to'] = "Reply-To: $reply_to\r\n"; return $this;
    }

    // Email - от кого
    public function from($from = false){
        if(!$from) $from = Yii::$app->params['admin_email'];
        $this->prop['from'] = "From: ".Yii::$app->name." <$from>\r\n";
        return $this;
    }

    // Кому отправляем
    public function to($to){
        $this->prop['to'] = $to; return $this;
    }

    // Заголовок письма
    public function subject($subject){
        $this->prop['subject'] = $subject; return $this;
    }

    // Отправка письма
    public function send(){

        // Если шаблон не задан
        if($this->prop['body'] == '') $this->tpl('empty');
        // Адрес - куда направлять ответ
        if($this->prop['reply_to'] == '') $this->reply_to();
        // Если не задано "от кого"
        if($this->prop['from'] == '') $this->from();
        /*
         * Если Email отправителя не заполнен
         * то устанавливаем его по умолчанию
         */
        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= $this->prop['from'];
        $headers .= $this->prop['reply_to'];

        try{
            mail($this->prop['to'], $this->prop['subject'], $this->prop['body'], $headers);
        }catch(Exception $e){
//            $this->message = $e->getMessage();
            $this->response = false;
        }

        return $this->response;
    }

}// End Class