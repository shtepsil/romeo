<?php

namespace frontend\controllers;

use frontend\models\Belt;
use frontend\models\Brand;
use frontend\models\Buckle;
use frontend\models\Clasp;
use frontend\models\CompositionFiller;
use frontend\models\CompositionLining;
use frontend\models\CompositionTop;
use frontend\models\Defenses;
use frontend\models\Design;
use frontend\models\Gender;
use frontend\models\Hood;
use frontend\models\Insulation;
use frontend\models\LandingLine;
use frontend\models\Length;
use frontend\models\Neckband;
use frontend\models\NumberButtons;
use frontend\models\Pockets;
use frontend\models\ProductGroup;
use frontend\models\Season;
use frontend\models\Silhouette;
use frontend\models\Sleeve;
use frontend\models\Splines;
use frontend\models\Width;
use frontend\models\SizeManufacturer;
use frontend\models\MenuLevel1;
use frontend\models\Color;
use frontend\models\MenuLevel2;
use frontend\models\MenuLevel3;
use frontend\models\Product;
use frontend\models\ProductNomenclature;
use frontend\controllers\MainController as d;
use yii\base\Security;
use yii\data\Pagination;
use frontend\components\Pagination as Paginator;
use Yii;
use yii\helpers\Url;

class CatalogController extends d
{

    public function actionIndex(){
        Yii::$app->response->redirect(Url::to('/catalog?cgc=012&level_id=2'));
    }

    public function actionCatalog()
    {
        $guest = Yii::$app->user->isGuest;

        Yii::$app->opengraph->set([
            'title' => Yii::$app->name,
            'description' => Yii::$app->params['description'],
            'image' => Yii::getAlias('@web').Yii::$app->params['img_preview_link'],
        ]);

        $get = d::secureEncode($_GET);
        /*
         * Если пользователь перешел на странцу /catalog
         * без GET параметров
         * то редиректим его на страницу - костюмы
         */
        if(!count($get)){
            Yii::$app->response->redirect(Url::to('/catalog?cgc=012&level_id=2'));
        }

        $ptne = [];

//        $pn = ProductNomenclature::findAll(['commodity_group_code'=>$get['cgc']]);

        /*
         * Получаем общее количество
         * выбранных по условию элементов
		 * ------------------------------
		 * Если нужно найти несколько номенклатур
		 * т.е. если $get[cgc] содержит строку вида ххх-ххх
		 *   а не ххх
         */
        if(preg_match('/-/',$get['cgc'])){

            $pn_query_count_pre = ProductNomenclature::find()
                ->where([
                    'in','commodity_group_code',
                    explode('-',$get['cgc'])
                ]);
            /*
             * Если админ не авторизован, то получаем
             * номенклатуры, у которых display = 1,
             * иначе если пользователь авторизован в админке,
             * получим абсолютно все номенклатуры
             */
            if($guest) {
                $pn_query_count_pre->andWhere(['display' => '1']);
            }
            $pn_query_count = $pn_query_count_pre->count();

        }else{
            $pn_query_count_pre = ProductNomenclature::find()
                ->where([
                    'commodity_group_code'=>$get['cgc'],
                ]);
            // Гость получит только display = 1
            if($guest) {
                $pn_query_count_pre->andWhere(['display' => '1']);
            }
            $pn_query_count = $pn_query_count_pre
                ->count();
        }

        $pn_query = ProductNomenclature::find();
        $pages = new Pagination([
            'totalCount' => $pn_query->count(),
            'pageSize' => Yii::getAlias('@catalogItemsPerPage'),
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);
//        $pages->pageSizeParam = false;


        if(preg_match('/-/',$get['cgc'])){

            $pn_pre = $pn_query->offset($pages->offset)
                ->where([
                    'in','commodity_group_code',
                    explode('-',$get['cgc'])
                ]);
            // Гость получит только display = 1
            if($guest) {
                $pn_pre->andWhere(['display' => '1']);
            }
            $pn = $pn_pre->limit($pages->limit)->all();

        }else{
            $pn_pre = $pn_query->offset($pages->offset)
                ->where([
                    'commodity_group_code'=>$get['cgc'],
                ]);
            // Гость получит только display = 1
            if($guest) {
                $pn_pre->andWhere(['display' => '1']);
            }
            $pn = $pn_pre->limit($pages->limit)->all();
        }

        if($pn){
            foreach($pn as $pn_one){
                foreach($pn_one as $key=>$val) $ptne[$pn_one['id']][$key] = $val;
                // Получаем список загруженных файлов
                $files = @scandir(Yii::getAlias('@photos').'/'.$pn_one['brand_code'].'/'.$pn_one['id']);
                if($files){
                    foreach($files as $file){
                        if($file != '.' AND $file != '..' AND $file != 'thumb')
                            $ptne[$pn_one['id']]['photos'][] =
                                Yii::getAlias('@photos_rel').
                                '/'.$pn_one['brand_code'].'/'.$pn_one['id'].'/'.$file;
                    }
                }else $ptne[$pn_one['id']]['photos'] = [];
            }
        }else $pn = false;

        $request = Yii::$app->request;
        // Получаем весь GET запрос
        $get = $request->get();
        /*
         * Если к $uri что то присоединится,
         * то это будет через "?"
         */
        $uri = '?';
        $page = false;
        if(count($get)){
            $page = $get['page'];
            if(array_key_exists('page',$get)) unset($get['page']);
            $uri .= http_build_query($get);
        };

        /* ================================================
               Получаем HTML постраничной навигации
        ================================================= */

        // Общее количество выбранных элементов
        $totalItems = $pn_query_count;
        // Число выведенных элементов на странице
        $itemsPerPage = Yii::getAlias('@catalogItemsPerPage');
        // Номер текущей старницы
        $currentPage = ($page)?$page:'1';
        /*
         * Шаблон URL для класса Pagination
         * ================================
         * Сначала ставим текущий action со слешем впереди
         * затем ставим весь GET запрос,
         * и последним ставим page
         */
        $urlPattern = '/'.Yii::$app->controller->action->id.$uri.'&page=(:num)';

        // Задаем настройки
        $pagination = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        // Информация для хлебных крошек
        $breadcrabs_data = self::getDataBreadcrambs();
        return $this->render('catalog',
            [
                'ptne' => $ptne,
                'pn_query_count' => $pn_query_count,
                'pagination' => $pagination,
                'menu1' => $breadcrabs_data['menu1'],
                'menu2' => $breadcrabs_data['menu2'],
                'menu3' => $breadcrabs_data['menu3'],
            ]
        );

    }

    public function actionSingleProduct()
    {
        // SEO
        Yii::$app->opengraph->set([
            'title' => Yii::$app->name,
            'description' => Yii::$app->params['description'],
            'image' => Yii::getAlias('@web').Yii::$app->params['img_preview_link'],
        ]);

        $pn = [];
        $ptps1 = [];
        $ptps2 = [];
        $pp = [];
        $sizes = [];
        $adm_info = [];// Информация для администратора
        $get = Yii::$app->request->get();
        // $get[product_id] - ID номенклатуры
        $pn_arr = ProductNomenclature::findOne(['id'=>$get['product_id']]);

        /*
         * Из таблицы "product" по ID номенклатуры
         * выбираем из всех найденых строк - штрихкод, розничная цена, код размера
         * $pts - products
         */
        $pts = Product::find()
            ->select(['barcode','retail_price','code_manufacturer_size'])
            ->where(['item_code'=>$get['product_id']])
            ->asArray()->all();

        // Убираем повторяющиеся размеры
        foreach($pts as $item){
            $sizes[$item['code_manufacturer_size']] = $item;
        }

        /*
         * Из таблицы "size_manufacturer" по коду размера
         * получаем Наименование и в массиве $pts код размера
         * заменяем массивом $product_size
         * manufacturer_size - размер производителя
         */
        foreach($sizes as $key=>$item){
            $sm = SizeManufacturer::findOne(['id'=>$item['code_manufacturer_size']]);
            $sizes[$key]['size'] = [
                'code'=>$item['code_manufacturer_size'],
                'name' => $sm->name
            ];
        }

        // Если "$sizes" массив не пуст
        if($sizes) {
            /*
             * Если в массиве только один элемент
             * и у него значение размера "без размера"
             * то массив "$sizes" делаем пустым
             */
            if(count($sizes) == 1){
                $sizes2 = $sizes;
                $first = array_shift($sizes2);
                if ($first['size']['code'] == '1') $sizes = [];
            }

            /*
             * Если после удаления элемета "без размера"
             * массив всё ещё не пуст
             * то вставим в ключи значения размеров
             * и отсортируем его по ключам
             */
            if($sizes) {
                $sizes2 = [];
                foreach($sizes as $sz){
                    $sizes2[$sz['size']['name']] = $sz;
                }

                // Сортируем по ключам
                ksort($sizes2);
                $sizes = $sizes2;

            }
        }

        $j=0;
        if($pn_arr){

            // По коду цвета, получаем цвет.
            $color = Color::findOne(['id'=>$pn_arr['code_color']]);

            /*
             * Делаем простой массив из выборки "product_nomenclature"
             * Из выборки цвета берем "наименование"
             */
            foreach($pn_arr as $key=>$val){
                if($key == 'code_color'){
                    $pn['color'] = $color['name'];
                }else $pn[$key] = $val;
            }

            /*
             * По ID номенклатуры,
             * из таблицы product выбираем все строки
             */
            $product_items = Product::find()
                ->where(['item_code'=>$pn['id']])
                ->all();

            // Если выборка из таблицы "product" не пуста
            if($product_items) {
                foreach ($product_items as $key => $val) {
                    /*
                     * Собираем массив
                     * В ключе общий ключ,
                     * в значении:
                     *  штрихкод
                     *  и цена строки товара
                     */
                    $ptps1[$key] = [
                        'barcode' => $val['barcode'],
                        'retail_price' => $val['retail_price'],
                        'automatic_discount' => $val['automatic_discount']
                    ];
                    /*
                     * Массив с ценами.
                     * В ключе общий ключ, в значении цена
                     */
                    $ptps2[$key] = $val['retail_price'];
                }

                /*
                 * По минимальной цене
                 * получаем ключ минимальной цены
                 */
                $key_by_min_value = array_keys($ptps2, min($ptps2))[0];

                /*
                 * По ключу минимальной цены, из массива $ptps1
                 * получаем нужные элементы - штрихкод и цену
                 */
                $pp = [
                    'barcode' => $ptps1[$key_by_min_value]['barcode'],
                    'retail_price' => $ptps1[$key_by_min_value]['retail_price'],
                    'automatic_discount' => $ptps1[$key_by_min_value]['automatic_discount'],
                ];
            }

            // Получаем список загруженных файлов
            $files = @scandir(Yii::getAlias('@photos').'/'.$pn['brand_code'].'/'.$pn['id']);
            if($files){
                foreach($files as $file){
                    if($file != '.' AND $file != '..' AND $file != 'thumb')
                        $pn['photos'][$j] = [
                            'path' => Yii::getAlias('@photos_rel').
                                '/'.$pn['brand_code'].'/'.$pn['id'].'/'.$file,
                            'name' => '/'.$pn['brand_code'].'/'.$pn['id'].'/'.$file
                        ];
                    $j++;
                }
            }else $pn['photos'] = [];
        }
        // Информация для хлебных крошек
        $breadcrabs_data = self::getDataBreadcrambs(true);

        /*
         * Если адинистратор авторизован,
         * получаем информацию для админа
         */
        if(!Yii::$app->user->isGuest){

            // $get[product_id] - ID номенклатуры
            // apa - adm_pn_arr
            $apa = ProductNomenclature::findOne(['id'=>$get['product_id']]);

            // Номенклатурный код товара
            $adm_info['nomenclature_code'] = [
                'content' => 'Номенклатурный код товара',
                'value' => $apa->id
            ];
            // артикул производителя
            $adm_info['article_of_manufacture'] = [
                'content' => 'Артикул производителя',
                'value' => $apa->article_of_manufacture
            ];
            // наименование номенклатуры
            $adm_info['nomenclature_name'] = [
                'content' => 'Наименование номенклатуры',
                'value' => $apa->nomenclature_name
            ];
            // товарная группа
            $adm_info['commodity_group'] = [
                'content' => 'Товарная группа',
                'value' => ProductGroup::findOne(['code'=>$apa->commodity_group_code])['name']
            ];
            // бренд
            $adm_info['brand'] =[
                'content' => 'Бренд',
                'value' => Brand::findOne(['code'=>$apa->brand_code])['name']
            ];
            // пол
            $adm_info['gender'] = [
                'content' => 'Пол',
                'value' => Gender::findOne(['code' => $apa->code_sex])['name']
            ];
            // рисунок/узор
            $adm_info['design'] = [
                'content' => 'Рисунок/узор',
                'value' => ($dsn = Design::findOne(['id'=>$apa->code_pattern]))?$dsn['name']:''
            ];
            // цвет
            $adm_info['color'] = [
                'content' => 'Цвет',
                'value' => ($color = Color::findOne(['id'=>$apa->code_color]))?$color['name']:''
            ];
            // состав верх
            $adm_info['composition_top'] = [
                'content' => 'Состав верх',
                'value' => ($cntp = CompositionTop::findOne(['id'=>$apa->code_composition_top]))?$cntp['name']:''
            ];
            // состав наполнитель
            $adm_info['filler_composition'] = [
                'content' => 'Состав наполнитель',
                'value' => ($cnfr = CompositionFiller::findOne(['id'=>$apa->code_filler_composition]))?$cnfr['name']:''
            ];
            // состав подклад
            $adm_info['composition_lining'] = [
                'content' => 'Состав подклад',
                'value' => ($cnlg = CompositionLining::findOne(['id'=>$apa->code_composition_lining]))?$cnlg['name']:''
            ];
            // утеплитель
            $adm_info['insulation'] = [
                'content' => 'Утеплитель',
                'value' => ($iuln = Insulation::findOne(['id'=>$apa->code_insulation]))?$iuln['name']:''
            ];
            // ворот
            $adm_info['neckband'] = [
                'content' => 'Ворот',
                'value' => ($nkbd = Neckband::findOne(['id'=>$apa->code_collar]))?$nkbd['name']:''
            ];
            // застежка
            $adm_info['clasp'] = [
                'content' => 'Застежка',
                'value' => ($clasp = Clasp::findOne(['id'=>$apa->code_clasp]))?$clasp['name']:''
            ];
            // число пуговиц
            $adm_info['number_of_buttons'] = [
                'content' => 'Число пуговиц',
                'value' => ($nrbs = NumberButtons::findOne(['id'=>$apa->code_number_of_buttons]))?$nrbs['name']:''
            ];
            // карманы
            $adm_info['pockets'] = [
                'content' => 'Карманы',
                'value' => ($pcks = Pockets::findOne(['id'=>$apa->code_pockets]))?$pcks['name']:''
            ];
            // капюшон
            $adm_info['hood'] = [
                'content' => 'Капюшон',
                'value' => ($hood = Hood::findOne(['id'=>$apa->code_hood]))?$hood['name']:''
            ];
            // длина
            $adm_info['length'] = [
                'content' => 'Длина',
                'value' => ($length = Length::findOne(['id'=>$apa->code_length]))?$length['name']:''
            ];
            // ширина
            $adm_info['width'] = [
                'content' => 'Ширина',
                'value' => ($width = Width::findOne(['id'=>$apa->code_width]))?$width['name']:''
            ];
            // рукав
            $adm_info['sleeve'] = [
                'content' => 'Рукав',
                'value' => ($sleeve = Sleeve::findOne(['id'=>$apa->code_sleeve]))?$sleeve['name']:''
            ];
            // силуэт
            $adm_info['silhouette'] = [
                'content' => 'Силуэт',
                'value' => ($silhouette = Silhouette::findOne(['id'=>$apa->code_silhouette]))?['name']:''
            ];
            // пояс
            $adm_info['belt'] = [
                'content' => 'Пояс',
                'value' => ($belt = Belt::findOne(['id'=>$apa->code_belt]))?$belt['name']:''
            ];
            // пряжка
            $adm_info['buckle'] = [
                'content' => 'Пряжка',
                'value' => ($buckle = Buckle::findOne(['id'=>$apa->code_buckle]))?$buckle['name']:''
            ];
            // линия посадки
            $adm_info['landing_line'] = [
                'content' => 'Линия посадки',
                'value' => ($lgle = LandingLine::findOne(['id'=>$apa->code_landing_line]))?$lgle['name']:''
            ];
            // защипы
            $adm_info['defenses'] = [
                'content' => 'Защипы',
                'value' => ($defenses = Defenses::findOne(['id'=>$apa->security_code]))?$defenses['name']:''
            ];
            // шлицы
            $adm_info['slots'] = [
                'content' => 'Шлицы',
                'value' => ($slots = Splines::findOne(['id'=>$apa->code_slots]))?$slots['name']:''
            ];
            // сезон
            $adm_info['season'] = [
                'content' => 'Сезон',
                'value' => ($season = Season::findOne(['id'=>$apa->code_season]))?$season:''
            ];
            // особенности модели
            $adm_info['features_of_the_model'] = [
                'content' => 'Особенности модели',
                'value' => $apa->features_of_the_model
            ];

            if($apa->display == '1') $display = 'Отображается';
            else $display = 'Не отображается';

            // признак отображать на сайте
            $adm_info['display'] = [
                'content' => 'Отображать на сайте',
                'value' => $display
            ];

        }


        return $this->render('single-product',[
            'pn'=>$pn,
            'pp'=>$pp,
            'sizes'=>$sizes,
            'menu1' => $breadcrabs_data['menu1'],
            'menu2' => $breadcrabs_data['menu2'],
            'menu3' => $breadcrabs_data['menu3'],
            'ppp' => $pn_arr,
            'adm_info' => $adm_info,
        ]);
    }

    // =====================================
    //   Внутренние методы для контроллера
    // =====================================

    /*
     * Сбор информации для хлебных крошек
     * Выборка из таблиц меню по условиям
     */
    private function getDataBreadcrambs($single_product = false){

        $get = d::secureEncode(Yii::$app->request->get());

        if($single_product) {
            $path_info = Yii::$app->request->pathInfo;
            $action = d::getPartStrByCharacter($path_info, '/', true);
        }else{
            $action = Yii::$app->request->pathInfo;
        }
        // Получаем пункт меню по "code" из URL
        $result['menu1'] = MenuLevel1::find()
            ->where(['code'=>$action])
            ->asArray()->one();
        // Получаем пункт меню2 по ID полученному из URL
        $result['menu2'] = MenuLevel2::find()
            ->where(['id'=>Yii::$app->request->get()['level_id']])
            ->asArray()->one();
        // Получаем пункт меню3 по ID родителя меню2
        $result['menu3'] =
            MenuLevel3::findOne([
                'id_menu_level_2'=>$result['menu2']['id'],
                'commodity_group_code' => $get['cgc']
            ]);

        return $result;
    }// getDataBreadcrambs(...)

    /**
     *
     */
    public function beforeAction($action)
    {

        return parent::beforeAction($action);

    }// beforeAction(...)


}// End Class
