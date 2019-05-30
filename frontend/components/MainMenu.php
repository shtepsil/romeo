<?php

namespace frontend\components;

use frontend\controllers\MainController as d;
use frontend\models\MenuLevel2;
use frontend\models\MenuLevel3;
use yii\helpers\Url;
use Yii;

class MainMenu
{
    /**
     * @inheritdoc
     */
    public static function getMenuItems($classes)
    {
        $items_2 = [];
        // Получаем все строки из таблицы menu_level_2
        $query_menu_2 = MenuLevel2::findAll(['visibility' => '1']);
        // Получаем все строки из таблицы menu_level_3
        $query_menu_3 = MenuLevel3::findAll(['visibility' => '1']);
        $id_level_3 = 0;

        /*
         * Формируем массив для виджета Menu::widget
         * выводящего меню в представлении
         * =========================================
         * На каждой итерации меню2, каждый раз перебирается массив меню3
         */
        foreach ($query_menu_2 as $item)
        {

            // Сборка меню ТРЕТЬЕГО уровня
            $sub_menu = [];
            foreach($query_menu_3 as $im){

                $data_url = [
                    Yii::$app->getRequest()->serverName.Yii::getAlias('@catalog'),
//                    $level_id,
                    Yii::getAlias('@id_nomenclature') => $im['id'],
                    Yii::getAlias('@commodity_group_code') =>
                        $im['commodity_group_code']];

//                d::pe($data_url);

                if($item['commodity_group_code'] == '') $data_url['level_id'] = $item['id'];
                // Получаем ссылку пункта меню
                $link_url = Url::toRoute($data_url);
                // $active_level_3 = true или false
                $active_level_3 = d::activeForMultiLevelMenu(d::getPartStrByCharacter($link_url, '/'));

//                d::td(d::getPartStrByCharacter($link_url, '/'));
//                d::tdfa(d::getPartStrByCharacter(Yii::$app->request->url,'/'));

                /*
                 * Если ID меню2 совпадает с "id_menu_level_2" меню3
                 * то "id_menu_level_2" записываем в переменную "$id_level_3"
                 * Чтобы по ней сориентироваться, какому пункту меню2
                 * поставить "active"
                 */
                if($active_level_3) $id_level_3 = $im['id_menu_level_2'];

                /*
                 * Если url не #
                 * то за строкой catalog ставим слэш
                 * и дописываем url строкой на товар
                 * иначе все ссылки будут заканчиваться /catalog
                 * без слэша на конце
                 */
                if($im['id_menu_level_2'] == $item['id']){
                    $sub_menu[] = [
                        'label' => $im['name'],
                        'url' => $link_url,
//                        'active' => $active,
                        'template'=>'<a '.(($active_level_3)?'class="active-level"':'').'href="{url}">{label}</a>',
                        'active_level_2' => '0'
                    ];
                }
            }

            /*
             * Сборка меню ВТОРОГО уровня
             * ==========================
             * Если url не #,
             * значит это просто ссылка на страницу
             */
            if($item['commodity_group_code']){
                $menu_right_icon = '';

                $data_url2 = [
                    Yii::$app->getRequest()->serverName.Yii::getAlias('@catalog'),
//                    'l'=>$level_menu,
                    Yii::getAlias('@commodity_group_code') =>
                        $item['commodity_group_code'],
                ];

                $data_url2['level_id'] = $item['id'];

                if($item['commodity_group_code'] == '') $data_url2['level_id'] = $item['id'];

                $url_2 = Url::toRoute($data_url2);

                // $active_level_2 = true или false
                $active_level_2 = d::activeForMultiLevelMenu(d::getPartStrByCharacter($url_2,'/'));
                $cs = $classes[1].(($active_level_2)?' active-level':'');

            }else{
                // Иначе это вывпадающее меню

                /*
                 * Если ID меню2 совпадает с "$id_level_3"
                 * то пункт меню2 делаем активным
                 */
                if($item['id'] == $id_level_3) $active_level_2 = ' active-level';
                else $active_level_2 = '';

                $cs = $classes[0].$active_level_2;
                $menu_right_icon = '<span class="glyphicon glyphicon-menu-right"></span>';
                $url_2 = '#';
            }

            $items_2[] =
                ['label' => $item['name'],
                    'url' => $url_2,
                    'template' => '<a href="{url}" class="'.$cs.'">'.$menu_right_icon.'{label}</a>',
                    'items'=>$sub_menu
                ];
        }

        return $items_2;
    }

}//  End Class