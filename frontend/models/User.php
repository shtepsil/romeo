<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 26.03.2019
 * Time: 16:32
 */

namespace frontend\models;

use backend\controllers\MainController as d;
use Yii;


class User extends d
{

    /*
     * Получение данных о пользователе
     */
    public static function getUserData(){

        // Массив для сбора информации о пользователе
        $user_data = [];
        $user_id = Yii::$app->session['user']['id'];

        /*
         * Получаем из БД вообще все данные пользователя
         * где есть его ID
         */
        $user_data_query = CustomerData::find()
            ->where([
                // Где ID авторизованного пользователя
                'id_customer_profile' => $user_id,
            ])->asArray()->all();

//        d::jtdfa($user_data_query);

        if($user_data_query){
            // Перебираем все выбранные данные о пользователе
            foreach($user_data_query as $dt){

                // Массив типов данных, которые должны быть в выборке
                $arr_user_types = [
                    'first_name','last_name','phone','email',
                    'discount_card'
                ];
                // Если тип текущего элемента есть в массиве типов данных
                if(in_array($dt['id_data_type'],$arr_user_types)){

                    /*
                     * Выбираем только те данные, которые актуальны
                     * т.е. которые не удалены
                     * (у которых delete_at не равно NULL)
                     */
                    if($dt['delete_at'] == NULL) {
                        /*
                         * Если тип "email",
                         * то нужно проверить, подтвержден ли адрес электронной почты
                         */
                        if ($dt['id_data_type'] == 'email') {
                            /*
                             * В поле ввода Email нужно вставить неподтвержденный Email(второй)
                             * потому что в выборке есть две строки, где delete_at равно NULL
                             * это строка с Email который совпадает с логином
                             * и строка с неподтвержденным Email(вторым)
                             */
                            if(Yii::$app->session['user']['email'] != $dt['user_data']) {
                                $user_data[$dt['id_data_type']]['text'] = $dt['user_data'];
                                $user_data[$dt['id_data_type']]['confirm'] =
                                    (CustomerData::find()
                                        ->where([
                                            /*
                                             * Где данные - ID строки таблицы "CustomerData"
                                             * в которой хранится "Email" с типом данных "email"
                                             */
                                            'user_data' => $dt['id'],
                                            // Где ID авторизованного пользователя
                                            'id_customer_profile' => $user_id,
                                            // Где тип данных "Подтвержден"
                                            'id_data_type' => 'confirmation',
                                            // Где поле "удален" равно NULL
                                            'delete_at' => NULL
                                        ])->asArray()->one()) ? '1' : '0';
                            }
                        }elseif($dt['id_data_type'] == 'phone'){
                        /*
                         * Если тип "phone",
                         * то нужно проверить, подтвержден ли телефон
                         */
                            $user_data[$dt['id_data_type']]['text'] = $dt['user_data'];
                            $user_data[$dt['id_data_type']]['confirm'] =
                                (CustomerData::find()
                                    ->where([
                                        /*
                                         * Где данные - ID строки таблицы "CustomerData"
                                         * в которой хранится "Phone" с типом данных "phone"
                                         */
                                        'user_data' => $dt['id'],
                                        // Где ID авторизованного пользователя
                                        'id_customer_profile' => $user_id,
                                        // Где тип данных "Подтвержден"
                                        'id_data_type' => 'confirmation',
                                        // Где поле "удален" равно NULL
                                        'delete_at' => NULL
                                    ])->asArray()->one())?'1':'0';

                        }else $user_data[$dt['id_data_type']] = $dt['user_data'];
                    }
                }
            }
        }

        /*
         * Если в массиве нет информации о Email
         * значит нужно взять Логин
         * и значение "confirm" пометить как true.
         */
        if(!$user_data['email']){
            $user_data['email'] =[
                'text' => Yii::$app->session['user']['email'],
                'confirm' => 1
            ];
        }

//        d::jtdfa($user_data);

        return $user_data;

    }// function getUserData(...)

}