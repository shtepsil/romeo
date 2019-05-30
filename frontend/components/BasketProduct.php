<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 10.04.2019
 * Time: 21:16
 */

namespace frontend\components;

use frontend\controllers\MainController as d;
use frontend\models\CustomerData;
use frontend\models\Product;
use frontend\models\ProductNomenclature;
use Yii;

class BasketProduct
{
    public static function getDataForBacketProducts(){

        $return = [];

        $backet_products = CustomerData::find()
            ->where([
                // ID авторизованного пользователя
                'id_customer_profile'=>Yii::$app->session['user']['id'],
                // Тип данных "корзина"
                'id_data_type' => 'basket',
                // У которых время удаления NULL
                'delete_at' => NULL
            ])->asArray()->all();

        if($backet_products){
            /*
             * Собираем необходимые данные для товаров корзины.
             * Сборка массива для сборки строк корзины по шаблону
             */
            foreach($backet_products as $item){
                // По штрихкоду получаем строку из таблицы "product"
                $product = Product::findOne(['barcode'=>$item['user_data']]);
                // По коду номенклатуры получаем строку из таблицы "product_nomenclature"
                $ptne = ProductNomenclature::findOne(['id'=>$product['item_code']]);

                /*
                 * Формируем путь до файлов текущей номенклатуры.
                 * ==============================================
                 * Путь до файла preview
                 * photos / «code бренда» / «id номенклатуры /
                 */
                $path_img = '/'.$ptne['brand_code'].'/'.$ptne['id'].'/';
                // Получаем список всех файлов номенклатуры
                $files = @scandir(Yii::getAlias('@photos').$path_img);

                // Если файлы есть
                if($files){
                    // Убираем два первых элемента из массива файлов
                    array_splice($files,0,2);

                    // Удаляем из массива имя директории "thumb"
                    if(($key = array_search('thumb',$files)) !== false){
                        unset($files[$key]);
                    }

                    /*
                     * Если в массиве ничего не осталось
                     * значит нужно отобразить изображение по умолчанию
                     */
                    if(!count($files)) $file_name = 'img_default';
                    else{
                        // Дописываем путь к файлу
                        $file_name = $path_img.$files[0];
                    }
                }else $file_name = 'img_default';

                // Если есть авторматическая скидка
                if($product['automatic_discount'] != 0){
                    $new_price = $product['retail_price']-(($product['retail_price']/100)*$product['automatic_discount']);
                    $old_price = $product['retail_price'];
                    $discount = $product['automatic_discount'];

                }else{
                    $new_price = $product['retail_price'];
                    $old_price = $product['retail_price'];
                    $discount = $product['automatic_discount'];
                }

                $return[] = [
                    // ID строки корзины
                    'id'=>$item['id'],
                    'barcode' => $item['user_data'],
                    'path_img' => $file_name,
                    'name' => $ptne['labeling'],
                    'old_price' => $old_price,
                    'new_price' => $new_price,
                    'discount' => strval($discount),
                ];
            }
        }else $return = false;

        return $return;

    }// getDataForBacketProducts()
}