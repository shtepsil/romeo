<?php
namespace frontend\controllers;

use frontend\components\Mess;
use frontend\models\CustomerData;
use frontend\models\CustomerProfile;
use frontend\models\DataType;
use frontend\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\controllers\MainController as d;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * User controller
 */
class UserController extends d
{

    /**
     * Настройки профиля пользователя
     */
    public function actionIndex()
    {

        $get = d::secureEncode(Yii::$app->request->get());
        /*
         * $ce_params - Confirm Email Params
         * Массив для отправки в представление confirm-email
         */
        $ce_params = [];
        $ce_params['error'] = false;
        $user_data = [];
        $user_data_old_email = [];
        $time = time();
        /*
         * Флаг для направления пользователя
         * на представление confirm-email.
         * т.е. если пользователь перешел по ссылке подтверджения Email
         */
        $ce_params['email_confirm'] = false;

        /*
         * ПОДТВЕРЖДЕНИЕ EAMIL
         * ===================
         * Если в GET одновременно существуют параметры
         * "email" и "verification_key"
         * проверим время жизни "verification_key"
         */
        if($get['email'] AND $get['verification_key']){

            /*
             * Если пользователь перешел по ссылке подтверждения Email
             * то запишем флаг $ce_params[email_confirm] в true
             * Чтобы направить пользователя на представление confirm-email
             */
            $ce_params['email_confirm'] = true;

            // Получим запись по Email из GET
            $cd_user_data = CustomerData::find()
                ->where([
                    'user_data' => $get['email'],
                    'id_data_type' => 'email',
                    'delete_at' => NULL
                ])->asArray()->one();

            // Если по Email из GET что то нашлось
            if($cd_user_data){
                /*
                 * По ID пользователя из "customer_data",
                 * из таблицы "customer_profile"
                 * получим "verification_key"
                 */
                $cp_user_profile = CustomerProfile::find()
                    ->where([
                        'id' => $cd_user_data['id_customer_profile'],
                        'delete_at' => NULL
                    ])->asArray()->one();

                // Если пользователь найден и не удален
                if($cp_user_profile){
                    // Если поле "verification_key" не пусто
                    if($cp_user_profile['verification_key'] != NULL){
                        /*
                         * Проверяем корректность "verification_key"
                         * Сравниваем "verification_key" из БД -
                         *  с "verification_key" созданным из Email GET
                         *
                         * "verification_key" который пришел в GET сравниваем
                         * с заново сгенерированным хэшэм из get[email] и времени создания хэша из БД
                         * md5(
                         *     get[email] . $time(время создания get[verification_key] взятое из БД)
                         * ) - если сгенерирован другой хэш: значит
                         * либо не верный email
                         * либо не верный get[verification_key]
                         */
                        if(
                            $get['verification_key'] ==
                            md5($get['email'].(explode('_', $cp_user_profile['verification_key'])[1]))
                        ){
                            /*
                             * Из таблицы "customer_data" получаем все email'ы
                             * пользователя, запросившего подтверждение,
                             * где поле delete_at равно NULL
                             * Если Email подтверждается после регистрации, то найдется одна запись
                             * а если Email подтверждается после смены Email в настройках аккаунта
                             * то должно быть найдено две записи.
                             */
                            $cd_user_data_query = CustomerData::find()
                                ->where([
                                    'id_customer_profile' => $cd_user_data['id_customer_profile'],
                                    'id_data_type' => 'email',
                                    'delete_at' => NULL
                                ])->asArray()->all();

                            /*
                             * Из общей выборки выбираем массив
                             * где Email равен Email'у из GET
                             */
                            foreach($cd_user_data_query as $item){
                                if($item['user_data'] == $get['email']) $user_data = $item;
                                else $user_data_old_email = $item;
                            }

                            /*
                             * Проверяем вермя жизни "verification_key" 900сек - 15 минут
                             * Если "verification_key" действителен
                             */
                            if (
                                ((explode('_', $cp_user_profile['verification_key'])[1] + 900) - time())
                                >= 0
                            ) {

//                            // Тест устаревшего "verification_key"
//                            if(
//                                ((explode('_',$user_profile['verification_key'])[1]) - time())
//                                >= 0
//                            ){

                                $crda = new CustomerData();
                                // ID строки, в которой хранится Email
                                $crda->user_data = $user_data['id'];
                                // Тип данных 7 - "Подтвержден"
                                $crda->id_data_type = 'confirmation';
                                // Время создания записи
                                $crda->created_at = Yii::$app->getFormatter()->asTimestamp(time());
                                // ID пользователя, которому принадлежит Email
                                $crda->id_customer_profile = $user_data['id_customer_profile'];

                                /*
                                 * Добавляем новую запись в "customer_data"
                                 * запись о том, что Eamil подтвержден
                                 */
                                if (!$crda->save()) {
//                        if (0) {
                                    $ce_params['email_confirm_status'] =
                                        Mess::EMAIL_CONFIRM_ERROR;
                                } else {
                                    $ce_params['email_confirm_status'] =
                                        Mess::EMAIL_CONFIRM;

                                    /*
                                     * Обновляем поле "verification_key"
                                     * Ставим занчение поля в NULL
                                     */
                                    $urpe = CustomerProfile::findOne([
                                        'id' => $user_data['id_customer_profile']]);
                                    $urpe->verification_key = NULL;
                                    $urpe->save();

                                    /*
                                     * Если был подтвержден новый Email
                                     * то у старого Email
                                     * и у строки подтверждения старого Email
                                     * delete_at заполняем текущим временем
                                     */
                                    if(count($user_data_old_email)){

                                        $cd_query = "UPDATE `customer_data` SET 
`delete_at`= CASE 
    WHEN 
        `id_customer_profile`='{$user_data_old_email['id_customer_profile']}' AND 
        `user_data`='{$user_data_old_email['user_data']}'
    THEN '{$time}' 
    ELSE `delete_at` END, 
`delete_at`= CASE 
    WHEN 
        `id_customer_profile`='{$user_data_old_email['id_customer_profile']}' AND 
        `user_data`='{$user_data_old_email['id']}' 
    THEN '{$time}' 
    ELSE `delete_at` END 
    WHERE 
      `id_customer_profile`='{$user_data_old_email['id_customer_profile']}' AND 
      `user_data` IN('{$user_data_old_email['user_data']}','{$user_data_old_email['id']}')";

                                        $update_cd = Yii::$app->db->createCommand($cd_query);
                                        try {
                                            $update_cd->execute();
                                        }catch (\Exception $e){
                                            $ce_params['error'] = $e->getMessage();
                                        }
                                    }
                                }

                            }else {
                                // Устаревший "verification_key" по вермени
                                $ce_params['error'] = Mess::VERIFICATION_KEY_ERROR;
                            }

                        }else{
                            /*
                             * Устаревший "verification_key"
                             * из-за не совпадения GET с БД
                             */
                            $ce_params['error'] = Mess::VERIFICATION_KEY_ERROR;
                        }
                    }else {
                        /*
                         * Устаревший "verification_key"
                         * из-за пустого значения "verification_key" в БД
                         */
                        $ce_params['error'] = Mess::VERIFICATION_KEY_ERROR;
                    }
                }else {
                    // Пользователь не найден
                    $ce_params['error'] = Mess::USER_NOT_FOUND;
                }
            }else {
                // Пользователь не найден
                $ce_params['error'] = Mess::USER_NOT_FOUND;
            }



        /*
         * // END ПОДТВЕРЖДЕНИЕ EMAIL ============
         */

        }// if GET get[email] и get[verification_key]

        // Если пользователь не авторизован
        if(!Yii::$app->getView()->params['user_auth']){
            /*
             * Если пользователь перешел по ссылке подтверждения Email
             * то направляем пользователя на представление "confirm-email"
             */
            if($ce_params['email_confirm']){

//                // Если Email не подтвержден
//                if(!Yii::$app->getView()->params['email_confirm']){
//                    $ce_params[]
//                }
                /*
                 * Подключаем представление "confirm-email"
                 * где будем показывать не авторизованному пользователю
                 * те или иные сообщения о процессе подтверждения Email
                 */
                return $this->render('confirm-email',[
                    'params' => $ce_params
                ]);
            }else{
                /*
                 * Если пользователь не аворизован,
                 * но Email уже подтвержден, то
                 * редиректим пользователья на главную.
                 */
                Yii::$app->response->redirect(Url::to('/'));
            }
        }else{
            $user_data = User::getUserData();
            return $this->render('index',[
                'alerts' => Yii::$app->view->renderFile(
                    '@app/views/site/shortcodes/alerts.php'),
//                'verification_key' => $verification_key,
                'user_data' => $user_data,
//            'types' => $user_data_query
            ]);
        }
    }

    /**
     * Запуск сессии
     * Проверка пользователя на авторизацию
     * Проверка подтверждения Email
     */
    public function beforeAction($action)
    {
        $session = Yii::$app->session;
        // Стартуем сессию
        $session->open();

        // По умолчанию считается что Email пользователя не подтвержден
        Yii::$app->getView()->params['email_confirm'] = false;

        // Если пользователь не авторизован
        if(!$session['user']['auth']){
            // Редиректим пользователя на главную
//            Yii::$app->response->redirect(Url::to('/'));
            Yii::$app->getView()->params['user_auth'] = false;
        }else{

            // Получаем Email пользователя
            $user_email = CustomerData::find()
                ->where([
                    // Где ID авторизованного пользователя
                    'id_customer_profile' => $session['user']['id'],
                    // Где тип данных Email
                    'id_data_type'=>'email',
                    // Где поле "удален" равно NULL
                    'delete_at'=>NULL
                ])->asArray()->one();

            // Проверка - подтвержден ли Email пользователя
            if($user_data = CustomerData::findOne([
                /*
                 * Где данные - ID строки таблицы "CustomerData"
                 * в которой хранится Email с типом данных "Email"
                 */
                'user_data' => $user_email['id'],
                // Где ID авторизованного пользователя
                'id_customer_profile' => $session['user']['id'],
                // Где тип данных "Подтвержден"
                'id_data_type'=>'confirmation',
                // Где поле "удален" равно NULL
                'delete_at'=>NULL
            ])){
                /*
                 * Если Email пользователя подтвержден
                 * то передаем в представление params[email_confirm] = true
                 */
                Yii::$app->getView()->params['email_confirm'] = true;

            }

            // Получаем Email пользователя
            Yii::$app->getView()->params['email'] = $user_email['user_data'];
            Yii::$app->getView()->params['user_auth'] = true;
        }

        return parent::beforeAction($action);
    }




}// End Class
