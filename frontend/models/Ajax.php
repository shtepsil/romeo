<?php
/**
 * Класс для работы с Ajax запросами
 */
namespace frontend\models;

use backend\models\Orders;
use frontend\components\Mess;
use frontend\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use backend\controllers\MainController as d;
use frontend\components\BasketProduct;

use yii\helpers\ArrayHelper;

//sleep(2);

class Ajax extends Model{

    /**
     * Модальное окно "Регистрация"
     * ============================
     * Регистрация нового пользователя
     */
    public static function userRegister($data)
    {

        $return = [];

        /*
         * Перед регистрацией проверяем
         * существует ли такой пользователь
         */
        if(CustomerData::findOne([
            'user_data'=>$data['email'],
            'id_data_type' => 'email',
            'delete_at' => NULL
        ])){
            /*
             * Если пользователь найден, то возвращаем ошибку
             * пользователь с таким Email уже зарегистрирован
             */
            $return['errors'] = Mess::REGISTRATION_ERROR_USER_IS_ALREADY;
        }else {
            /*
             * Если Email не найден,
             * регистрируем нового пользователя
             */
            $cp = new CustomerProfile();
            $cp->password = md5($data['password']);
            $cp->created_at = Yii::$app->getFormatter()->asTimestamp(time());

            /*
             * Если запись удачна
             * Создаем новую запись в таблице "customer_data"
             */
            if ($cp->save()) {

                $last_insert_id = Yii::$app->db->getLastInsertID();

                $cd = new CustomerData();
                $cd->user_data = $data['email'];
                $cd->id_data_type = 'email';
                $cd->id_customer_profile = $last_insert_id;
                $cd->created_at = Yii::$app->getFormatter()->asTimestamp(time());

                $return['user_id'] = $last_insert_id;

                if (!$cd->save()) $return['errors'] = Mess::REGISTRATION_ERROR;

            } else $return['errors'] = Mess::REGISTRATION_ERROR;
        }

        return $return;

    }// function userRegister(...)

    /**
     * Модальное окно "Авторизация"
     * ============================
     * Кнопка "Войти"
     * --------------
     * Проверка пользователя на существование в двух таблицах
     * "customer_data" и "customer_profile"
     * далее проверка совпадения паролей
     * и далее проверка на подтвержденный Email
     * если все проверки пройдены, возвращаем пустой массив $return.
     */
    public static function userAuth($data)
    {

        $return = [];

        /*
         * Перед авторизацией проверяем по Email
         * существует ли такой пользователь.
         * Делаем выборку из таблицы customer_data
         * по Email пользователя
         */
        $return['user_data'] =
            CustomerData::find()
                ->where([
                    'user_data'=>$data['email'],
                    'id_data_type' => 'email',
                    'delete_at' => NULL
                ])
                ->asArray()->one();

        /*
         * Проверяем по Email
         * существует ли такой пользователь
         */
        if(!$return['user_data']){
            // Если пользователь не найден, возвращаем ошибку
            $return['errors'] = Mess::AUTH_ERROR;
        }else{

            /*
             * Делаем выборку из таблицы "customer_profile"
             * получаем все поля по ID пользователя
             */
            $return['user_profile'] =
                CustomerProfile::find()
                    ->where([
                        'id'=>$return['user_data']['id_customer_profile'],
                        'delete_at' => NULL
                    ])
                    ->asArray()->one();

            // Если в "customer_profile" пользователь найден
            if($return['user_profile']){
                // Проверка пароля
                if(md5($data['password']) != $return['user_profile']['password']){
                    // Если пароли не совпадают, возвращаем ошибку
                    $return['errors'] = Mess::AUTH_ERROR;
                }else{
                    // Проверка - подтвержден ли Email пользователя
                    if(!CustomerData::findOne([
                        'id_customer_profile' => $return['user_data']['id_customer_profile'],
                        'id_data_type' => 'confirmation',
                        'user_data' => $return['user_data']['id']
                    ])){
                        $return['send_email'] = true;
                        $return['errors'] =
                            Mess::AUTH_ERROR_EMAIL_VERIFICATION_SENT;
                    }
                }
            }else $return['errors'] = Mess::USER_NOT_FOUND;
        }

        return $return;

    }// function userAuth(...)

    /**
     * Страница "Настройки профиля"
     * ============================
     * Подтвержедние Email
     */
    public static function emailVerification($data)
    {

        $return = [];
        $session = Yii::$app->session;
        $time = time();
        $verification_key = md5($data['email_verification'].$time);

        /*
         * В таблицу "customer_profile"
         * Запишем хэш для ссылки
         * которую отправим на Email пользователя
         */
        $cp = CustomerProfile::findOne(['id'=>$session['user']['id']]);
        $cp->verification_key = $verification_key.'_'.$time;
        if($cp->save()){

            $query_get = array(
                'email' => $data['email_verification'],
                'verification_key' => $verification_key,
            );

            $return['link'] = Yii::$app->request->hostInfo.'/user/?'.http_build_query($query_get, '', '&amp;');
            /*
             * http_build_query - знак @ меняет на %40
             * восстанавливаем знак @
             */
            $return['link'] = str_replace('%40','@',$return['link']);
        }else{
            $return['errors'] = Mess::EMAIL_VERIFICATION_SENT_ERROR;
        }

        return $return;

    }// function emailVerification(...)

    /**
     * Страница "Настройки профиля"
     * ============================
     * Кнопка "Сохранить"
     */
    public static function saveUserData($data)
    {
        $return = [];
        $time = Yii::$app->getFormatter()->asTimestamp(time());
        $not_confirm_email = false;// Переменная для неподтвержденного(второго) Email

        /*
         * Первый Email - текущий Email
         * Второй Email - Email, которым заменили текущий
         * Третий Email - Email, которым заменили второй Email
         */

        // Проверим, введен ли новый пароль
        if ($data['new_password'] != '') {
            // Проверим, правильно ли введен текущий пароль
            $current_password = CustomerProfile::find()
                ->where([
                    'id' => Yii::$app->session['user']['id']
                ])->one();

            // Если что то нашлось
            if ($current_password) {
                /*
                 * Если пароли совпадают
                 * то обновляем пароль в БД
                 */
                if (md5($data['current_password']) === $current_password->password) {
                    $current_password->password = md5($data['new_password']);
                    $current_password->save();
                } else {
                    $return['errors'] = Mess::CURRENT_PASSWORD_ERROR;
                    /*
                     * Если введенный текущий пароль не верный
                     * то останавливаем всю функцию
                     * и возвращаем ошибку
                     */
                    return $return;
                }
            }
            unset($data['current_password']);
            unset($data['new_password']);
            unset($data['confirm_password']);

            if (!count($data)) return $return;
        }

        /*
         * Помещаем неподтвержденный Email(второй) в отдельную переменную,
         * удаляем его из общего массива,
         * чтобы небыло лишней итерации в цикле.
         * Взятый из HTML атрибута "data-value-email"
         */
        if ($data['not_confirm_email']){

            $not_confirm_email =
                d::jsonToArray($data['not_confirm_email'])['not_confirm_email'];
            unset($data['not_confirm_email']);
        }

        $return['errors'] = '';
        $cd_new_rows = [];// Для сбора полей для записи в БД
        $i=0;
        $return['email_already_is'] = false;
        $cancel_recording = false;
        $delete_index = '';
        foreach($data as $key=>$item){

            // JSON пару key:value превращаем в массив
            $ar_item = d::jsonToArray($item);

            if($key != 'email') {
                $cd_query = [
                    'id_customer_profile' => Yii::$app->session['user']['id'],
                    'id_data_type' => $key,
                    'delete_at' => NULL
                ];
            }else{
                /*
                 * Если пользователь РЕДАКТИРОВАЛ
                 * неподтвержденный адрес эл. почты.
                 * То берем неподтвержденный(второй) Email из атрибута "data-value-email",
                 * Поиск в БД должен происходить по (второму) неподтвержденному Email...
                 * Тем самым объект выборки из БД должен быть не пустым
                 * и тем самым становится возможным сделать изменение ячейки user_data.
                 * Неподтвержденный(второй) Email - меняем на новый(третий).
                 * В общей JS выборке с ключем - "email": содержится новый(трейтий) Email.
                 *
                 * Если пользователь РЕДАКТИРОВАЛ, то для выборки берем Email
                 * из артибута "data-value-email"
                 */
                if ($not_confirm_email) {
                    $not_confirmed_email = $not_confirm_email;
                } else {
                    /*
                     * Если пользователь НЕ РЕДАКТИРОВАЛ
                     * неподтвержденный адрес эл. почты.
                     * То берем неподтвержденный(второй) Email из поля ввода,
                     * Берем его из обычного $data
                     *
                     * Если пользователь НЕ РЕДАКТИРОВАЛ, то для выборки берем Email
                     * из общей JS выборки
                     */
                    $not_confirmed_email = $ar_item[key($ar_item)];
                }
                $cd_query = [
                    'id_customer_profile' => Yii::$app->session['user']['id'],
                    'id_data_type' => $key,
                    'user_data' => $not_confirmed_email,
                    'delete_at' => NULL
                ];
            }

            // Получаем данные по типу
            $ud_item = CustomerData::findOne($cd_query);

            /*
             * Если такой тип нашелся
             * значит надо заполнить поле delete_at
             */
            if($ud_item){
                /*
                 * Если Email был редактирован
                 * то старый пока не удаляется.
                 * Старый удалится после подтверждения нового.
                 * По этому старому, вермя удаления, ставить не нужно.
                 * По этому через это условие проходят все поля кроме Email
                 */
                if($key != 'email') {
                    /*
                     * Старой записи ставим дату удаления:
                     * заполняем поле "delete_at"
                     */
                    $ud_item->delete_at = $time;
                    $ud_item->save();
                }else{
                /*
                 * Если пользователь НЕ редактировал
                 * новый НЕ подтвержденный Email(второй)
                 * Значит поле Email в статусе "редактировано" и if($ud_item) - выдаст true,
                 * потому что атрибут "data-change" содержит в себе старый Email(первый)
                 * и не совпадает с тем, который присутствует в поле ввода,
                 * потому что в поле ввода введен новый, неподтвержденный Email(второй)
                 * и запустится этот блок "else"
                 */

                    /*
                     * Если Email(логин) не равняется
                     * новому неподтвержденному Email'у,
                     * значит нужно новый Email,
                     * который отличается
                     * и от старого Email(первого)
                     * и от неподтвержденного Email(второго)
                     * т.е. нужно изменить поле "user_data" там, где
                     * неподтвержденный Email
                     */
                    if(Yii::$app->session['user']['email'] !=
                        $ar_item[Yii::$app->session['user']['email']]){
                        /*
                         * Можно было не делать эту проверку, но пусть лучше будет
                         *
                         * Проверка на случай, когда в БД нет неподтвержденного Email
                         * и есть только один Email(первый)
                         * т.е. чтобы не изменить единственный Email(первый)
                         * делаем проверку - если тот, который хотим изменить не совпадает
                         * с логином, то изменяем, иначе ничего не изменяем.
                         * Оставляем старый Email как есть.
                         */
                        if($ud_item->user_data != Yii::$app->session['user']['email']){

                            /*
                             * Если был редактирован Email(второй)
                             * то нужно убедиться,
                             * не существует ли уже новый Email в БД
                             */
                            $is_email = CustomerData::find()
                                ->where([
                                    'id_data_type' => $key,
                                    'user_data' =>
                                        $ar_item[Yii::$app->session['user']['email']],
                                    'delete_at' => NULL
                                ])
                                ->andWhere([
                                    'NOT',
                                    ['id_customer_profile'=>
                                            Yii::$app->session['user']['id']]])
                                ->one();

                            /*
                             * Если новый Email в БД уже существует
                             * то удалим его из массива $cd_new_rows.
                             */
                            if($is_email){
                                $return['email_already_is'] = true;
                            }else{
                                $ud_item->user_data = $ar_item[key($ar_item)];
                                $ud_item->save();
                            }
                        }
                    }else{
                    /*
                     * А если новый редактированный Email(третий)
                     * совпадает со старым Email(первым)(который является и логином)
                     * то два одинаковых Email нам не нужно.
                     * Значит неподтвержденный Email(второй) нужно просто удалить.
                     */

                        /*
                         * Проверка на случай, когда в БД нет неподтвержденного Email
                         * и есть только один Email(первый)
                         * т.е. чтобы не удалить последний единственный Email(первый)
                         * делаем проверку - если тот, который хотим удалить не совпадает
                         * с логином, то удаляем, иначе ничего не удаляем.
                         * Оставляем старый Email.
                         */
                        if($ud_item->user_data != Yii::$app->session['user']['email']){
                            // Удаляем неподтвержденный Email(второй)
                            $ud_item->delete();
                        }
                    }

                    /*
                     * Флаг - что нужно удалить email
                     * из общего массива для добавления новых записей в БД,
                     * поле Email изменяется тут, в блоке "else"
                     */
                    $cancel_recording = true;
                    $delete_index = $i;
                }
            }else{
                if($key == 'email') {
                    /*
                     * Если был редактирован Email(первый)
                     * то нужно убедиться,
                     * не существует ли уже новый Email в БД
                     */
                    $is_email = CustomerData::findOne([
                        'id_data_type' => $key,
                        'user_data' => $ar_item[Yii::$app->session['user']['email']],
                        'delete_at' => NULL
                    ]);

                    /*
                     * Если новый Email в БД уже существует
                     * то удалим его из массива $cd_new_rows.
                     */
                    if($is_email){
                        $cancel_recording = true;
                        $delete_index = $i;
                        $return['email_already_is'] = true;
                    }
                }
            }

            // ID авторизованного пользователя
            $cd_new_rows[$i]['id_customer_profile'] = Yii::$app->session['user']['id'];
            // Тип данных
            $cd_new_rows[$i]['id_data_type'] = $key;
            // Значение типа данных
            $cd_new_rows[$i]['user_data'] = $ar_item[key($ar_item)];
            // Время создания записи
            $cd_new_rows[$i]['created_at'] = $time;

            $i++;

        }// foreach

//        d::pe(array_keys($cd_new_rows[0]));

        // Удаляем email из общего массива для записи в БД
        if($cancel_recording) unset($cd_new_rows[$delete_index]);

        if($cd_new_rows) {
            $cd_command = Yii::$app->db->createCommand()->batchInsert(
                'customer_data',
            /*
             * Надо разобраться в проблеме
             * почему array_keys выдает ошибку
             */
//                array_keys($cd_new_rows[0]),
            ['id_customer_profile','id_data_type','user_data','created_at'],
                $cd_new_rows
            );

            try {
                $cd_command->execute();
            } catch (Exception $e) {
                $return['errors'] =
                    Mess::DATA_SAVE_ERROR;
//                $return['errors'] .= $e->getMessage();
            }
        }

        $return['user_data'] = User::getUserData();

//        d::pe($return);

        return $return;

    }// function saveUserData(...)

    /**
     * Страница "Настройки профиля"
     * ============================
     * Кнопка "Удалить аккаунт"
     */
    public static function deleteUserProfile()
    {
        $delete_time = Yii::$app->getFormatter()->asTimestamp(time());
        $user = CustomerProfile::findOne(['id'=>Yii::$app->session['user']['id']]);
        $user->delete_at = $delete_time;
        if($user->save()){

            $cd = new CustomerData();
            // Удаляем вообще все строки пользователя из "customer_data"
            $cd->updateAll([
                'delete_at'=>Yii::$app->getFormatter()->asTimestamp(time())],[
                'id_customer_profile'=>Yii::$app->session['user']['id']
            ]);

            $session = Yii::$app->session;
            // Стартуем сессию
            $session->open();
            // Очищаем все сессии
            $session->destroy();

            return true;
        }
        else return false;

    }// function deleteUserProfile()

    /**
     * Страница "single_product" и top_panel Кнопка "Войти"
     * ====================================================
     * Кнопка "Добавить в корзину".
     * Кнопка "Войти" - при авторизации,
     *  добавляем товары из локальной корзины в БД
     */
    public static function addToCard($data)
    {

//        d::pe($data['backet']);

        $return = [];
        $return['backet_products'] = false;
        $backet_products = [];

        if ($data['backet']) {

            foreach ($data['backet'] as $key => $item) {
                // Штрихкод товара
                $backet_products[$key]['user_data'] = $item['barcode'];
                // Тип данных
                $backet_products[$key]['id_data_type'] = 'basket';
                // Время создания записи
                $backet_products[$key]['created_at'] = Yii::$app->getFormatter()->asTimestamp(time());
                // ID авторизованного пользователя
                $backet_products[$key]['id_customer_profile'] = Yii::$app->session['user']['id'];
            }

            if ($backet_products) {
                $backet_products_command = Yii::$app->db->createCommand()->batchInsert(
                    'customer_data',
                    array_keys($backet_products[0]),
                    $backet_products
                );

                try {
                    $backet_products_command->execute();
                } catch (Exception $e) {
                    $return['errors'] = Mess::ITEMS_NOT_ADDED;
//                    $return['errors'] = $e->getMessage();
                }
            }
        }else $return['errors'] = Mess::ITEMS_NOT_ADDED;

        return $return;

    }// function addToCard(...)

    /**
     * Корзина
     * =======
     * Кнопка "Удалить элемент из корзины"
     */
    public static function deleteSingleProduct($data)
    {
//        d::pe($data);

        $cd = CustomerData::findOne(['id'=>$data['product_id']]);
        $cd->delete_at = Yii::$app->getFormatter()->asTimestamp(time());
        if($cd->save()) return true;
        else return false;

    }// function deleteSingleProduct(...)

    /**
     * Корзина
     * =======
     * Кнопка "Подтвердить заказ"
     */
    public static function addOrder($data)
    {
//        d::pe(implode(',',array_keys($data['basket'])));
        $return = [];
        $order_products = [];
        $user_data = [];
        $i=0;
        $time = Yii::$app->getFormatter()->asTimestamp(time());

        // Если пользователь не авторизован
        if(!Yii::$app->session['user']['auth']){

            $profile = new CustomerProfile();
            $profile->created_at = $time;
            $profile->delete_at = $time;

            /*
             * Добавляем нового пользователя с пометкой (удален)
             * Если добавление нового пользователя не удалось
             */
            if(!$profile->save()) return $return['errors'] = Mess::ADD_ORDER_ERROR;

            // ID последней записи таблицы "customer_profile"
            $user_id = Yii::$app->db->getLastInsertID();

        }else {
        // Если пользователь авторизован
            $user_id = Yii::$app->session['user']['id'];
        }

        // Если есть данные о пользователе, запишем их в "customer_data"
        if ($data['user']) {
            foreach ($data['user'] as $key=>$item) {
                $user_data[$i]['id_customer_profile'] = $user_id;
                $user_data[$i]['id_data_type'] = $key;
                $user_data[$i]['user_data'] = $item;
                /*
                 * Если пользователь не авторизован
                 * то поле "delete_at" заполняем временем
                 */
                $user_data[$i]['created_at'] = $time;
                if(!Yii::$app->session['user']['auth'])
                    $user_data[$i]['delete_at'] = $time;
                $i++;
            }

            if ($user_data) {
                $user_data_command = Yii::$app->db->createCommand()->batchInsert(
                    'customer_data',
                    array_keys($user_data[0]),
                    $user_data
                );

                try {
                    $user_data_command->execute();
                } catch (Exception $e) {
                    $return['errors'] = Mess::ADD_ORDER_ERROR;
//                    $return['errors'] = $e->getMessage();
                }
            }
        }

        // В таблицу "orders" добавляем новую строку заказа
        $orders = new Orders();
        $orders->created_at = $time;
        $orders->id_customer_profile = $user_id;

        // Если запись новой строки в таблицу "orders" удачна
        if($orders->save()){

            // ID последней записи таблицы "orders"
            $last_insert_id_orders = Yii::$app->db->getLastInsertID();

            /*
             * Обновим поле "name" в таблице "orders"
             * Из $last_insert_id_orders создадим четырехзначный код заказа
             */
            $oc = $orders::findOne(['id'=>$last_insert_id_orders]);
            $oc->name = self::createOrderCode($last_insert_id_orders);
            if(!$oc->save()) $return['errors'] = Mess::ADD_ORDER_ERROR;

            // Если есть товары для добавления
            if ($data['basket']) {
                // Сбросим счетчик
                $i=0;
                foreach ($data['basket'] as $item) {
                    // ID заказа таблицы "orders"
                    $order_products[$i]['orders_id'] = $last_insert_id_orders;
                    // Штрихкод товара
                    $order_products[$i]['barcode'] = $item;
                    $i++;
                }

                if ($order_products) {
                    $order_products_command = Yii::$app->db->createCommand()->batchInsert(
                        'order_products',
                        array_keys($order_products[0]),
                        $order_products
                    );

                    try {
                        $order_products_command->execute();

                        /*
                         * Если пользователь авторизован
                         * В customer_data у всех товаров
                         * поле "delete_at" заполним (удалим товар из корзины)
                         */
                        if(Yii::$app->session['user']['auth']){
                            // Получим все товары по ID
                            $cd_basket = CustomerData::find()
                                ->where([
                                    'id_customer_profile'=>Yii::$app->session['user']['id'],
                                    'id_data_type'=>'basket',
                                    'delete_at'=>NULL
                                ])
                                ->andWhere(['IN','id',array_keys($data['basket'])])->all();

                            if($cd_basket){
                                // В цикле обновим все строки товаров
                                foreach($cd_basket as $item){
                                    $item->delete_at = $time;
                                    $item->update();
                                }
                            }
                        }

                    } catch (Exception $e) {
                        $return['errors'] = Mess::ADD_ORDER_ERROR;
//                    $return['errors'] = $e->getMessage();
                    }
                }
            }

        }else $return['errors'] = Mess::ADD_ORDER_ERROR;

        return $return;

    }// function addOrder(...)





























    /**
     * Тестовый метод
     */
    public static function test()
    {
//        $cd = CustomerData::findOne(['id'=>$data['product_id']]);
//        $cd->delete_at = Yii::$app->getFormatter()->asTimestamp(time());
//        if($cd->save()) return true;
//        else return false;



    }// function deleteSingleProduct(...)




    /* ===========================================================
                           Местные методы
    ============================================================*/

    /*
     * Из ID записи таблицы "orders"
     * создаем четырехзначный код заказа
     */
    private static function createOrderCode($id){
//    public static function createOrderCode($id){

        $oc = [];
        // Получим количество символов в строке $id
        $lenght = iconv_strlen($id);

        // Если символов 4 или больше четырех
        if($lenght >= 4){
            // Возьмем 4 последних
            $oc = substr($id,-4);
            /*
             * Если первый символ 0
             * то меняем первый символ 0 на 1
             */
            if(substr($oc,0,1) == '0'){
                $oc = '1'.(substr($id,-3));
            }
        }else{
        // Если количество символов меньше четырех
            switch($lenght){
                // Если три символа
                case '3':
                    $oc = '1'.$id;
                    break;
                // Если два символа
                case '2':
                    $oc = '10'.$id;
                    break;
                // По умолчанию один символ
                default:
                    $oc = '100'.$id;
            }
        }

        return $oc;
    }


}// Class Ajax