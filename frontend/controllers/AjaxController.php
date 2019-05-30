<?php
/**
 * Контроллер для работы с Ajax зпросами
 * Отсюда запускаются методы из модели Ajax.php
*/
namespace frontend\controllers;

use app\models\FilesExcel;
use frontend\components\Mess;
use frontend\components\Mail;
use frontend\components\PHPMail;
use frontend\controllers\MainController as d;
use backend\libraries\barcode\BarcodeImage;
use frontend\models\Ajax;
use common\models\User;
use frontend\models\CustomerData;
use frontend\models\CustomerProfile;
use frontend\models\Test;
use yii\helpers\BaseHtml;
use frontend\models\SignupForm;
use frontend\components\BasketProduct;
use Yii;
use frontend\components\SiteHelper as SH;

class AjaxController extends d{

    /**
     *
     */
    public function actionIndex(){

        return $this->render('index');

    }

    /**
     * Модальное окно "Регистрация"
     * ============================
     * Регистрируем нового пользователя
     */
    public function actionUserRegister(){

//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;
        $time = time();

        $post = d::secureEncode(Yii::$app->request->post());

        // Делаем Email пользователя в нижний регистр
        $post['email'] = strtolower($post['email']);

        // Регистрируем пользователя
        $result_reg = Ajax::userRegister($post);

        if(!$result_reg['errors']){
            $data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
            $data['message'] = Mess::REGISTRATION_SUCCESSFUL;

            $verification_key = md5($post['email'].$time);

            $query_get = array(
                'email' => $post['email'],
                'verification_key' => $verification_key,
            );

            $link = Yii::$app->request->hostInfo.'/user/?'.http_build_query($query_get, '', '&amp;');
            /*
             * http_build_query - знак @ меняет на %40
             * восстанавливаем знак @
             */
            $link = str_replace('%40','@',$link);

            // Записываем код верификации в поле "verification_key"
            $cp = CustomerProfile::find()
                ->where(['id' => $result_reg['user_id']])->one();
            $cp->verification_key = $verification_key.'_'.$time;
            $cp->save();

            if(SH::isLocal()){
                $mail = new PHPMail();
            }else {
                $mail = new Mail();
            }
            $send_mail = $mail->tpl('verification-email',[
                'admin_email' => Yii::$app->params['admin_email'],
                'link' => $link
            ])
                ->to($post['email'])
                ->subject('Подтверждение Email')
                ->send();

            if(!$send_mail['errors']){
                $data['header'] = Mess::HEADER_SUCCESS;
                $data['message'] =
                    Mess::REGISTRATION_SUCCESSFUL_EMAIL_VERIFICATION_SENT;
            }else{
                $data['type_message'] = Mess::TYPE_WARNING;
                $data['header'] = Mess::HEADER_WARNING;
                $data['message'] = Mess::REGISTRATION_ERROR;
                /*
                 * Если возникла ошибка отправки письма
                 * со ссылкой подтверждения Email
                 * Удаляем зарегистрированного пользователя
                 */
                $cp_d = CustomerProfile::find()
                    ->where(['id' => $result_reg['user_id']])->one();
                $cd_d = CustomerData::find()
                    ->where(['id_customer_profile' => $result_reg['user_id']])->one();

                // удаляем строку
                // Из "customer_profile"
                $cp_d->delete();
                // И из "customer_data"
                $cd_d->delete();
            }

        }else{
            $data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;
            $data['message'] = $result_reg['errors'];
        }

        d::echoAjax($data);

    }

    /**
     * Модальное окно "Авторизация"
     * ============================
     * Кнопка "Войти"
     */
    public function actionUserAuth(){

//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());

        // Делаем Email пользователя в нижний регистр
        $post['email'] = strtolower($post['email']);

        $time = time();

        // Авторизация пользователя
        $user_data = Ajax::userAuth($post);

        if(!$user_data['errors']){
            $data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
            $data['message'] = Mess::AUTH_SUCCESSFUL;

            $session = Yii::$app->session;
            $session->open();
            if ($session->isActive){
                $session['user'] = [
                    'auth' => true,
                    'id'=>$user_data['user_data']['id_customer_profile'],
                    'email'=>$post['email']
                ];
            }

            // Если в корзине есть товары, добавим их в БД
            if($post['backet']){
                $add_to_card_result = Ajax::addToCard($post);
                if($add_to_card_result['errors']){
                    $data['status'] = Mess::AJAX_STATUS_ERROR;
                    $data['type_message'] = Mess::TYPE_WARNING;
                    $data['header'] = Mess::HEADER_WARNING;
                    $data['message'] = $add_to_card_result['errors'];
                }
            }

            /*
             * Получим массив со всеми необходимыми данными
             * для шаблона элемента товара корзины
             */
            if($backet_products = BasketProduct::getDataForBacketProducts()){
                $data['backet_products'] = '';
                foreach($backet_products as $item){
                    $data['backet_products'] .= $data['item_product'] =
                        Yii::$app->view->renderFile(
                            '@app/views/catalog/shortcodes/js-templates/cart-single-procuct.php',
                            ['pt'=>$item]
                        );
                }

                /*
                 * Если страница "корзина"
                 * то соберем список товаров по шаблону для страницы
                 */
                if(preg_match('/cart/',$post['request_url'])){
                    $data['list_backet_products'] = '';
                    foreach($backet_products as $item){
                        if(preg_match('/ajax/',Yii::$app->request->url)) $item['js'] = false;
                        $data['list_backet_products'] .=
                            Yii::$app->view->renderFile(
                                '@app/views/site/shortcodes/js-templates/cart-single-product.php',
                                ['pt'=>$item]
                            );
                    }
                }
            }

            $data['user_id'] = $user_data['user_data']['id_customer_profile'];

        }else{
        /*
         * Если значение массива $user_data[errors] не пусто,
         * то проверяем существует ли в массиве флаг - 'send_email',
         * если существует, значит Email надо подтвердить,
         * отправляем письмо со ссылкой подтверждения на почту.
         */
            $data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;

            if($user_data['send_email']){
                $verification_key = md5($post['email'].$time);

                $query_get = array(
                    'email' => $post['email'],
                    'verification_key' => $verification_key,
                );

                $link = Yii::$app->request->hostInfo.'/user/?'.http_build_query($query_get, '', '&amp;');
                /*
                 * http_build_query - знак @ меняет на %40
                 * восстанавливаем знак @
                 */
                $link = str_replace('%40','@',$link);

                // Записываем код верификации в поле "verification_key"
                $cp = CustomerProfile::find()
                    ->where(['id' => $user_data['user_profile']['id']])->one();
                $cp->verification_key = $verification_key.'_'.$time;
                $cp->save();

                if(SH::isLocal()){
                    $mail = new PHPMail();
                }else {
                    $mail = new Mail();
                }
                $send_result = $mail->tpl('verification-email',[
                    'admin_email' => Yii::$app->params['admin_email'],
                    'link' => $link
                ])
                    ->to($post['email'])
                    ->subject('Подтверждение Email')
                    ->send();

                if(!$send_result){
                    $data['header'] = Mess::HEADER_SUCCESS;
                    $data['message'] =
                        Mess::AUTH_ERROR_EMAIL_VERIFICATION_SENT;
                }else{
                    $data['type_message'] = Mess::TYPE_WARNING;
                    $data['header'] = Mess::HEADER_WARNING;
                    $data['message'] = Mess::AUTH_ERROR;
                }
            }

            $data['message'] = $user_data['errors'];
        }

//        d::pe($data);

        d::echoAjax($data);

    }// function actionUserAuth()

    /**
     * Кнопка "Выйти"
     */
    public function actionLogout(){

//        sleep(9);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_SUCCESS;

        $session = Yii::$app->session;
        // Стартуем сессию
        $session->open();
        // Очищаем все сессии
        $session->destroy();

        d::echoAjax($data);

    }// function actionLogout()

    /**
     * Страница "Настройки профиля"
     * ============================
     * Подтвержедние Email
     */
    public function actionEmailVerification(){

//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());

        // Подтверждение Email
        $result = Ajax::emailVerification($post);

        if(!$result['errors']){

            $mail = new Mail;
            $send_mail = $mail->tpl('verification-email',[
                    'admin_email' => Yii::$app->params['admin_email'],
                    'link' => $result['link']
                ])
                ->to($post['email_verification'])
                ->subject('Подтверждение Email')
                ->send();

            if($send_mail){
                $data['status'] = Mess::AJAX_STATUS_SUCCESS;
                $data['header'] = Mess::HEADER_SUCCESS;
                $data['message'] = Mess::EMAIL_VERIFICATION_SENT;
            }else{
                $data['type_message'] = Mess::TYPE_WARNING;
                $data['header'] = Mess::HEADER_WARNING;
                $data['message'] = Mess::EMAIL_VERIFICATION_SENT_ERROR;
            }

        }else{
            $data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;
            $data['message'] = $result['errors'];
        }

        d::echoAjax($data);

    }// function actionEmailVerification()

    /**
     * Страница "Настройки профиля"
     * ============================
     * Кнопка "Сохранить"
     */
    public function actionSaveUserData(){
//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());
        unset($post[Yii::$app->request->csrfParam]);

        // Сохранение/добавление информации о пользователе
        $result = Ajax::saveUserData($post);

        if(!$result['errors']){

            $data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
            $data['message'] = Mess::DATA_SAVE_SUCCESS;
            $data['user_data'] = $result['user_data'];
            if($result['email_already_is']) {
                $data['email_already_is'] = Mess::EMAIL_ALREADY_IS;
            }

        }else{
            $data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;
            $data['message'] = $result['errors'];
//            $data['message'] = Mess::DATA_SAVE_ERROR;
        }

        d::echoAjax($data);

    }// function actionSaveUserData()

    /**
     * Страница "Настройки профиля"
     * ============================
     * Кнопка "Удалить аккаунт"
     */
    public function actionDeleteUserProfile(){
//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());
        unset($post[Yii::$app->request->csrfParam]);

        // Удаление профиля пользователя
        if(Ajax::deleteUserProfile()){
            $data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
        }else{
            $data['header'] = Mess::HEADER_WARNING;
            $data['message'] = Mess::USER_DELETE_ERROR;
        }

        d::echoAjax($data);

    }// function actionDeleteUserProfile()

    /**
     * Страница "single_product"
     * ============================
     * Кнопка "Добавить в корзину"
     */
    public function actionAddToCard(){
//        sleep(2);
        $data = [];
        $data['backet_products'] = '';
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());
        unset($post[Yii::$app->request->csrfParam]);

        // Добавление товара в корзину
        $result = Ajax::addToCard($post);
        if(!$result['errors']){
            $data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
            $data['message'] = Mess::ITEM_ADDED;

            /*
             * Получим массив со всеми необходимыми данными
             * для шаблона элемента товара корзины
             */
            if($backet_products = BasketProduct::getDataForBacketProducts()){
                $data['backet_products'] = '';
                foreach($backet_products as $item){
                    $data['backet_products'] .= $data['item_product'] =
                        Yii::$app->view->renderFile(
                            '@app/views/catalog/shortcodes/js-templates/cart-single-procuct.php',
                            ['pt'=>$item]
                        );
                }
            }

        }else{
            $data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;
            $data['message'] = Mess::ITEMS_NOT_ADDED;
        }

        d::echoAjax($data);

    }// function actionAddToCard()

    /**
     * Корзина
     * =======
     * Кнопка "Удалить элемент из корзины"
     */
    public function actionDeleteSingleProduct(){
//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());
        unset($post[Yii::$app->request->csrfParam]);

        // Удаление товара из корзины
        if(Ajax::deleteSingleProduct($post)){
            $data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
            $data['message'] = Mess::ITEM_DELETED;
        }else{
            $data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;
            $data['message'] = Mess::ERROR_ITEM_DELETED;
        }

        d::echoAjax($data);

    }// function actionDeleteSingleProduct()

    /**
     * Корзина
     * =======
     * Кнопка "Подтвердить заказ"
     */
    public function actionAddOrder(){
//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());
        unset($post[Yii::$app->request->csrfParam]);

        // Добавление заказанных товаров корзины в табилцу "orders"
        $result = Ajax::addOrder($post);

        if(!$result['errors']){
            $data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
            $data['message'] = Mess::ADD_ORDER_SUCCESS;
        }else{
            $data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;
            $data['message'] = $result['errors'];
        }

        d::echoAjax($data);

    }// function actionDeleteSingleProduct()

    /**
     * Страница "Отправка Email"
     * =========================
     * Кнопка "Отправить Email"
     */
    public function actionSendMailTest(){
//        sleep(2);
        $data = [];
        $data['status'] = Mess::AJAX_STATUS_ERROR;

        $post = d::secureEncode(Yii::$app->request->post());
        unset($post[Yii::$app->request->csrfParam]);
		
		$post['email'] = $post['send_mail'];
		
		$link = 'http://romeo-man.ru/user/?email=akvarius_84@mail.ru&verification_key=78b53532a5b785c1a2e58ef16ac9e75f';
		
		$mail = new Mail;
		
		if($post['type'] == 'empty'){
			$send_mail = $mail->tpl('empty',[
				'admin_email' => Yii::$app->params['admin_email'],
				'link' => $link
			])
				->to($post['email'])
				->subject('Тестовое сообщение')
				->send();
		}
		if($post['type'] == 'tpl'){
			$send_mail = $mail->tpl('verification-email',[
				'admin_email' => Yii::$app->params['admin_email'],
				'link' => $link
			])
				->to($post['email'])
				->subject('Подтверждение Email')
				->send();
		}
		
		if(!$send_mail['errors']){
			$data['status'] = Mess::AJAX_STATUS_SUCCESS;
            $data['header'] = Mess::HEADER_SUCCESS;
            $data['message'] = 'Сообщение отправлено';
		}else{
			$data['type_message'] = Mess::TYPE_WARNING;
            $data['header'] = Mess::HEADER_WARNING;
			$data['message'] = $send_mail['errors'];
		}

        d::echoAjax($data);

    }// function actionSendMailTest()




    /*
     * ==============================================
     *     ОБЩИЕ МЕТОДЫ ДЛЯ ВСЕХ МЕСТНЫХ ЭКШЕНОВ
     * ==============================================
     */
//    public function afterAction($action, $result)
//    {
//        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
//    }

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }













    /*
     * ==============================================
     *                 TEST ACTIONS
     * ==============================================
     */


    /**
     *
     */
    public function actionTestRequest(){

        d::echoAjax($_POST);

    }



















    /** ==================================================================
     * тестовый метод Debug
     */
    public function actionDebug(){

        $data = [];
        $test = new Test();
        $test->name = $_POST['name'];
        $test->save();


//        d::pe(Mess::$registration_error);






//        $send_mail = true;
//
//        $ajax = new Ajax;
//        $data = [];
//        $data['status'] = Mess::AJAX_STATUS_ERROR');
//        $data['status'] = Mess::AJAX_STATUS_SUCCESS');

//        Ajax::createOrderCode();

//        if(!$result['errors']){
//            $data['status'] = Mess::AJAX_STATUS_SUCCESS');
//            $data['header'] = Mess::HEADER_SUCCESS');
//            $data['message'] = 'Сообщение отправлено';
//        }else{
//            $data['type_message'] = Mess::TYPE_WARNING');
//            $data['header'] = Mess::HEADER_WARNING');
//            $data['message'] = Mess::USER_DELETE_ERROR');
//            $data['message'] = 'Сообщение отправлено';
//        }

        d::echoAjax($data);
    }

}// End Class
