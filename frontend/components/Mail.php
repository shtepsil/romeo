<?php

namespace frontend\components;

use Yii;

class Mail
{

	private $prop = [];
	private $response = true;

    public function tpl($template = false,$params = []){
		if($template){
			$this->prop['body'] = Yii::$app->view->renderFile(
				'@app/views/ajax/shortcodes/email/'.$template.'.php',$params);
		}
        return $this;
    }

//    // Куда направить ответ
//    public function reply_to($reply_to = false){
//        if(!$reply_to) $reply_to = Yii::$app->params['admin_email'];
//        $this->prop['reply_to'] = "Reply-To: $reply_to\r\n";
//		return $this;
//    }

//    // Email - от кого
//    public function from($from = false){
//        if(!$from) $from = Yii::$app->params['admin_email'];
//        $this->prop['from'] = "From: Магазин Ромео <$from>\r\n"; 
//        return $this;
//    }

    // Email - от кого
    public function from($from = false){
        if(!$from) $from = Yii::$app->params['admin_email'];
        $this->prop['from'] = $from;
        return $this;
    }

    // Кому отправляем
    public function to($to){
        $this->prop['to'] = $to;
		return $this; 
    }

    // Заголовок письма
    public function subject($subject){
        $this->prop['subject'] = $subject;
		return $this;
    }

    // Отправка письма
    public function send(){

        $return['errors'] = false;
		// Подключаем класс PHPMailer
		require_once $_SERVER['DOCUMENT_ROOT'].
            '/common/libraries/phpmailer/class.phpmailer.php';

        // Если шаблон не задан
        if($this->prop['body'] == '') $this->tpl('empty');
        // Если не задано "от кого"
        if($this->prop['from'] == '') $this->from(); 
        
		// Пользовательские настройки
		$from = $this->prop['from'];
		$from_name = Yii::$app->name;
		$to = $this->prop['to'];
		$subject = $this->prop['subject'];
		$msg = $this->prop['body'];
		
		// Настройки PHPMailer
		$mail             = new \PHPMailer();
		$mail->IsSMTP();
		$mail->Host       = Yii::$app->params['smtp_server'];
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "ssl";
		$mail->Port       = 465;
		$mail->Username   = Yii::$app->params['post_server_login'];
		$mail->Password   = Yii::$app->params['post_server_password']; 
		$mail->CharSet    = 'utf8';
					
		$mail->SetFrom($from, $from_name);
		$mail->AddReplyTo($from, $from_name);
		$mail->Subject = $subject;
		$mail->MsgHTML($msg);
					
		$mail->AddAddress($to);

		if(!$mail->Send()){
//			$return['errors'] = 'Ошибка отправки письма: ' . $mail->ErrorInfo;
            $this->response = false;
		}

        return $this->response;
    }

}// Class






