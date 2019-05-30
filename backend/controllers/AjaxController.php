<?php
/**
 * Контроллер для работы с Ajax зпросами
*/
namespace backend\controllers;

use app\models\Color;
use app\models\FilesExcel;
use app\models\Product;
use app\models\ProductNomenclature;
use app\models\SizeManufacturer;
use backend\components\GetData;
use backend\components\SiteHelper;
use backend\controllers\MainController as d;
use backend\libraries\barcode\BarcodeImage;
use backend\models\Ajax;
use common\models\User;
use backend\models\Orders;
use frontend\models\CustomerData;
use frontend\models\OrderProducts;
use yii\helpers\BaseHtml;
use frontend\models\SignupForm;
use Yii;

class AjaxController extends MainController{

    /**
     * Страница "Импорт файлов Excel".
     * ===============================
     * Загрузка файла
     *
     * @return json string
     */
    public function actionUpload(){

        $ajax = new Ajax;
        $data = [];
        $data['files_list'] = '';
        $data['count'] = 0;
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $data = $ajax->uploadFiles();

        // Получаем новый список файлов Excel из БД
        $arr_files = FilesExcel::find()->all();
        if($arr_files) {
            foreach ($arr_files as $file) {
                $data['count']++;
                $vars['id'] = $file['id'];
                $vars['name'] = $file['name'];
                $vars['full_name'] = $file['name'] . '.' . $file['ext'];
                $arr_fn = explode('_', $file['name']);
                $vars['date'] = $arr_fn[1];
                $te = explode('-', $arr_fn[2]);
                $vars['time'] = $te[0] . ':' . $te[1];
                $data['files_list'] .= $this->renderAjax('shortcodes/list_excel_files', $vars);
            }
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Импорт файлов Excel".
     * ===============================
     * Удаление файла
     *
     * @return json string
     */
    public function actionDeleteExcelFile(){

        $ajax = new Ajax;
        $data = [];
        $vars = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $data['files_list'] = '';
        $data['count'] = 0;
        $post = d::secureEncode($_POST);

        $result = $ajax->deleteExcelFile($post);
        if($result['error']){
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $result['error'];
        }else{
            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = $result['success'];

            // Получаем новый список файлов Excel из БД
            $arr_files = FilesExcel::find()->all();
            if($arr_files) {
                foreach ($arr_files as $file) {
                    $data['count']++;
                    $vars['id'] = $file['id'];
                    $vars['name'] = $file['name'];
                    $vars['full_name'] = $file['name'] . '.' . $file['ext'];
                    $arr_fn = explode('_', $file['name']);
                    $vars['date'] = $arr_fn[1];
                    $te = explode('-', $arr_fn[2]);
                    $vars['time'] = $te[0] . ':' . $te[1];
                    $data['files_list'] .= $this->renderAjax('shortcodes/list_excel_files', $vars);
                }
            }
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Загрузка Excel CDB".
     * ===============================
     * Загрузка файла
     *
     * @return json string
     */
    public function actionUploadExcelTmpl(){

        $ajax = new Ajax;
        $data = [];
        $data['files_list'] = '';
        $data['count'] = 0;
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $data = $ajax->uploadFilesExcelTmpl();

        // Получаем новый список файлов Excel из БД
        $arr_files = FilesExcel::find()->all();
        if($arr_files) {
            foreach ($arr_files as $file) {
                $data['count']++;
                $vars['id'] = $file['id'];
                $vars['name'] = $file['name'];
                $vars['full_name'] = $file['name'] . '.' . $file['ext'];
                $arr_fn = explode('__', $file['name']);
                // $arr_fn[1]: 2018-12-18_16-37-38
                $dt = explode('_', $arr_fn[1]);
                $vars['date'] = $dt[0];
                $te = explode('-', $dt[1]);
                $vars['time'] = $te[0] . ':' . $te[1];
                $data['files_list'] .=
                    $this->renderAjax('shortcodes/list_excel_files', $vars);
            }
        }

        d::echoAjax($data);

    }

    /**
     * Страницы "Номенклатура товара","Поступление товара","Справочники"
     * Получаем массив значений для заполнения поля select
     *
     * @return array
     */
    public function actionListValue(){

        $post = d::secureEncode($_POST);
        $options = '';
        $attr = [];

        /*
         * Таблица "Номенклатура товара"
         * Собираем выпадающий список артикулов по коду бренда
         */
        if($post['page'] == 'product_nomenclature'){
            // получаем список артикулов по бренду
            $arr_vc = Ajax::getArticlesByBrand($post['brand_code']);

            // Если НЕ страница "Поиск чека"
            if(!$post['check_search']){
                if($arr_vc) {
                    // Если артикулы найдены
                    $options .= '<option value="empty">' . $post['list_name'] . '</option>';
                }else{
                    // Если не найден ни один артикул
                    $options .= '<option value="empty">' . $post['empty_value'] . '</option>';
                }
                $options .= '<option value="new">' . $post['new_value'] . '</option>';
            }else{
                // Если страница "Поиск чека"

                // Если артикулы найдены
                if($arr_vc){
                    $options .= '<option value="">'.$post['list_name'].'</option>';
                }else{
                    // Если не найден ни один артикул
                    $options .= '<option value="">'.$post['empty_value'].'</option>';
                }
            }

            foreach ($arr_vc as $key => $value) {

                $attr['value'] = $value['id'];

                $options .= $this->renderAjax(
                    'shortcodes/options_list',[
                        'attributes' => BaseHtml::renderTagAttributes($attr),
                        'string' => $value['article_of_manufacture'],
                    ]
                );
            }
        }elseif($post['page'] == 'product'){
            /*
             * Страница "Поступление товара"
             * Собираем выпадающий список артикулов по коду бренда
             * ===================================================
             * получаем список артикулов по бренду
             */
            $arr_vc = Ajax::getArticlesByBrand($post['brand_code']);

            // Если что то нашлось
            if($arr_vc){
                $options .= '<option value="empty">'.$post['list_name'].'</option>';

                foreach ($arr_vc as $key => $value) {

                    $attr['value'] = $value['id'];

                    $options .= $this->renderAjax(
                        'shortcodes/options_list',[
                            'attributes' => BaseHtml::renderTagAttributes($attr),
                            'string' => $value['article_of_manufacture'],
                        ]
                    );
                }
            }else{
                // Если ни одного артикула не найдено
                $options .= '<option value="empty">'.d::getMessage('NO_ARTICLE_FOUND').'</option>';
            }
        }elseif($post['page'] == 'workers'){
            /*
             * Страница "Работники"
             */
        }else{
            /*
             * Страница "Справочники"
             * ======================
             * Получаем данные из соответствующих таблиц
             */
            $ajax = new Ajax;
            $arr = $ajax->getListValue($post);

            $options .= '<option value="empty">'.$post['list_name'].'</option>';
            $options .= '<option value="new">'.$post['new_value'].'</option>';

            foreach ($arr as $key => $val) {

                $attr['value'] = $val['id'];

                if(in_array($post['table'], Yii::$app->params['requiredFields']))
                    $attr['data-code'] = $val['code'];

                $options .= $this->renderAjax(
                    'shortcodes/options_list',[
                        'attributes' => BaseHtml::renderTagAttributes($attr),
                        'string' => $val['name'],
                    ]
                );
            }
        }

        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
        $data['header'] = d::getMessage('HEADER_SUCCESS');
        $data['message'] = d::getMessage('UPLOADED');
        $data['option_s'] = $options;

        d::echoAjax($data);
    }

    /**
     * Страница "Справочники"
     * Кнопка "внести изменения"
     * этот action вызывается только со страницы "справочники"
     *
     * @return array
     */
    public function actionReferenceEdit(){

        $ajax = new Ajax;
        $attr = [];
        $post = d::secureEncode($_POST);
        $data = $ajax->referenceEdit($post);

        /*
         * Если добавляем новую запись
         * то нам нужен ID последней записи
         */
        if($post['type'] == 'new') $for_selected = $data['last_insert_id'];
        /*
         * Если обновляем данные
         * то нам нужен ID обновляющейся записи
         */
        else $for_selected = $post['id'];

        /*
         * Если добавление успешно,
         * то были выбраны все значения
         */
        if(count($data['options']) > 0){

            $options = '<option value="empty">Выберите значение справочника</option>';
            $options .= '<option value="new">Добавить новое значение</option>';

            foreach($data['options'] as $key=>$val){

                $attr['value'] = $val['id'];

                if(in_array($post['table'], Yii::$app->params['requiredFields']))
                    $attr['data-code'] = $val['code'];

                if($val['id'] == $for_selected){
                    $attr['selected'] = 'selected';
                }else{
                    if(isset($attr['selected'])) unset($attr['selected']);
                }

                $options .= $this->renderAjax(
                    'shortcodes/options_list',[
                        'attributes' => BaseHtml::renderTagAttributes($attr),
                        'string' => $val['name'],
                    ]
                );
            }

        }else{
            $options = '<option value="empty">Справочник пока пуст</option>';
        }

        $data['option_s'] = $options;

        d::echoAjax($data);
    }

    /**
     * Страница "Номенклатура товара".
     * Ptne - product-nomenclature
     * ===============================
     * Кнопка "Внести изменения"
     * Обработка главной формы (всех полей)
     *
     * @return array
     */
    public function actionPtne(){

        $ajax = new Ajax;
        $post = d::secureEncode($_POST);
        // Удаляем csrf параметр Yii
        unset($post[Yii::$app->request->csrfParam]);
        $data['content'] = false;
        $attr = [];
        $content = '';
        // Первые два значения(option) select'а
        $names = [
            'empty' => 'Выберите артикул',
            'new'=>'Добавить новый артикул'
        ];
        foreach($names as $value=>$name){
            $attr['value'] = $value;
            $content .= $this->renderAjax(
                'shortcodes/options_list',[
                    'attributes' => BaseHtml::renderTagAttributes($attr),
                    'string' => $name,
                ]
            );
        }

        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        // Вносим данные в таблицу "product_nomenclature"
        $result = $ajax->Ptne($post);

        /*
         * arr_vc - vendor code
         * список артикулов по бренду
         */
        // Чистим массив для сбора атрибутов
        $attr = [];
        if(count($result['arr_vc'])){
            foreach($result['arr_vc'] as $key=>$value) {

                $attr['value'] = $value['id'];

                if ($post['article_of_manufacture'] == $value['article_of_manufacture']){
                    $attr['selected'] = 'selected';
                }else{
                    if(isset($attr['selected'])) unset($attr['selected']);
                }

                $content .= $this->renderAjax(
                    'shortcodes/options_list',[
                        'attributes' => BaseHtml::renderTagAttributes($attr),
                        'string' => $value['article_of_manufacture'],
                    ]
                );
            }
            $data['content'] = $content;
        }

        $data = array_merge($data,$result);

        d::echoAjax($data);
    }

    /**
     * Страница "Номенклатура товара".
     * Выпадающий список "Выберите артикул"
     * Получаем данные из таблицы "product_nomenclature"
     * для заполнения полей страницы
     *
     * @return array
     */
    public function actionGetNomenclature(){

        $ajax = new Ajax;
        $post = d::secureEncode($_POST);
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $response = $ajax->getNomenclature($post);
        // если что то есть, то переписываем status в успех
        if($response){
            foreach($response as $key=>$value){
                if($key == 'commodity_group_code'){
                    // если значение нули - то нужно вернуть пустое значение
                    if ($value == '000') $data[$key] = '';
                    else $data[$key] = $value;
                }else{
                    // если значение нули - то нужно вернуть 0
                    if ($value == '0' OR $value == '00' OR $value == '000') $data[$key] = '0';
                    else $data[$key] = $value;
                }
            }
            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('DATA_UPLOADED');
        }

        d::echoAjax($data);
    }

    /**
     * Страница "Номенклатура товара".
     * ===============================
     * Загрузка файлов через плагин dropzone
     *
     * @return array
     */
    public function actionUploadFiles(){

        // Преобразуем строку в объект
        $nt = \app\components\UploadedFile::getInstanceByName('nomenclature_type');
        // Разбиваем строку на части имен директориий
        $dirs = explode('-',$nt->name);

        $fileName = 'file';
        $paths = [];
        $photos = Yii::getAlias('@photos').'/';
        $paths['uploadPath'] = $photos.$dirs[0].'/'.$dirs[1].'/';
        $paths['uploadThumbPath'] = $paths['uploadPath'].'/thumb/';



        /*
         * Проверяем директории на существование
         * если не существует, то добавляем новые директории
         * photos / «code бренда» / «id номенклатуры /
         * =================================================
         * Если директории бренда нет
         */
        if(!is_dir($photos.$dirs[0])){
            // Создаем директорию бренда
            mkdir($photos.$dirs[0]);
            // И проверям директорию номенклатуры
            if(!is_dir($paths['uploadPath'])){
                /*
                 * Создаем директорию номенклатуры
                 * и в ней сразу создаем диерторию
                 * для миниатюр
                 */
                mkdir($photos.$dirs[0].'/'.$dirs[1]);
                mkdir($photos.$dirs[0].'/'.$dirs[1].'/thumb');
            }
        }else{
            /*
             * Если директория бренда есть,
             * а директории номенклатуры нет
             */
            if(!is_dir($paths['uploadPath'])){
                mkdir($photos.$dirs[0].'/'.$dirs[1]);
                mkdir($photos.$dirs[0].'/'.$dirs[1].'/thumb');
            }
        }

        if (isset($_FILES[$fileName])) {
            $file = \app\components\UploadedFile::getInstanceByName($fileName);
            $paths['file'] = $paths['uploadPath'] . $file->name;

            //Print file data
            //print_r($file);

            if ($file->saveAs($paths)) {
                //Now save file data to database

                echo \yii\helpers\Json::encode($file);
            }
        }

        return false;

    }

    /**
     * Страница "Номенклатура товара".
     * ===============================
     * Получение загруженных файлов
     *
     * @return array
     */
    public function actionGetImages(){

        // Перебираем миниатюры, и выводим их в блок
        $data  = [];
        $obj = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $thumb_dir = Yii::getAlias('@photos').'/'.$_GET['path'].'/thumb/';

        /*
         * Если директория существует
         * то пытаемся получить файлы
         */
        if(is_dir($thumb_dir)){
            $files = scandir($thumb_dir);

            // Если хоть один файл существует
            if ( false!==$files ) {
//                $files = array_slice($files, 2);
                foreach ( $files as $file ) {
                    if ( '.'!=$file && '..'!=$file) {
                        $data['images'][] = [
//                            'name'=>iconv('WINDOWS-1251','UTF-8',$file),
                            'name'=>$file,
                            'size'=>filesize($thumb_dir.$file)
                        ];
                    }
                }
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            }
        }

        if(!count($data['images'])){
            unset($data['images']);
            $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Номенклатура товара".
     * ===============================
     * Удаление загруженный файлов
     *
     * @return array
     */
    public function actionPtneDeleteFiles(){

        $result = [];
        $result['status'] = 407;
        $dirs = explode('-',$_POST['dir']);
        $dir = $dirs[0].'/'.$dirs[1].'/';
        //Папка для полноразмерных изображений
        $orig_directory =
            Yii::getAlias('@photos').'/'.$dir;
        //Папка для миниатюр
        $thumb_directory =
            Yii::getAlias('@photos').'/'.$dir.'thumb/';
        // Делаем имя файла из UTF-8 в WINDOWS-1251
//        $file = iconv('UTF-8','WINDOWS-1251',$_POST['file_name']);
        $file = $_POST['file_name'];

        // Проверка на существование файла
        if(file_exists($orig_directory.$file)){
            // Удаляем основное изображение
            unlink($orig_directory.$file);
            // Удаляем мини
            unlink($thumb_directory.$file);
            $result['status'] = 200;
        }

        d::echoAjax($result);

    }

    /**
     * Страница "Поступление товара".
     * Кнопка "Добавить строку"
     * Добавление строки <tr> в tbody
     *
     * @return array
     */
    public function actionGetTemplate(){

        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $post = d::secureEncode($_POST);
        $attr = [];

        /*
         * Проверка, если добавляется не первая строка
         * то нам нужно выбрать список артикулов по бренду
         */
        if($post['tr_quantity'] > 0){

            // полуаем артикулы по бренду
            foreach(Ajax::getArticlesByBrand($post['brand_code']) as $key=>$value){

                $attr['value'] = $value['id'];

                if ($value['article_of_manufacture'] == $post['vendor_code_name']){
                    $attr['selected'] = 'selected';
                }else{
                    if(isset($attr['selected'])) unset($attr['selected']);
                }

                $post['list_options'] .= $this->renderAjax(
                    'shortcodes/options_list',[
                        'attributes' => BaseHtml::renderTagAttributes($attr),
                        'string' => $value['article_of_manufacture'],
                    ]
                );
            }
        }

        $data['tpl'] = $this->renderAjax('shortcodes/add-product-tr',$post);
        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');

        d::echoAjax($data);
    }

    /**
     * Страница "Поступление товара".
     * Подгружаем
     *  "наименование номенклатуры"
     *  "товарную группу"
     *
     * @return array
     */
    public function actionProductNomenclature(){

        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $ajax = new Ajax;
        $post = d::secureEncode($_POST);

        $data = $ajax->productNomenclature($post);

        d::echoAjax($data);
    }

    /**
     * Страница "Поступление товара".
     * Кнопка "Добавить товары"
     *
     * @return array
     */
    public function actionSendGoods(){

        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $ajax = new Ajax;
        $post = d::secureEncode($_POST);

        $data = $ajax->sendGoods($post);

        d::echoAjax($data);
    }

    /**
     * Страница "Товарный чек".
     * Поле ввода "Ввод штрихкода"
     *
     * @return array
     */
    public function actionGetInfoByBarcode(){

        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $ajax = new Ajax;
        $post = d::secureEncode($_POST);
        /*
         * Из настроек сайта получаем переменные для number_format
         */
        $ko = Yii::getAlias('@ko');// kopecks
        $fl = Yii::getAlias('@fl');// float
        $th = Yii::getAlias('@th');// thousand

        $response = $ajax->getInfoByBarcode($post);
        $data['barcode'] = $response['barcode'];

        /*
         * Если штрихкод - дисконтная карта
         */
        if(count($response['discount_cards']) != 0){
            $data['type'] = 'discount_cards';
            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['barcode'] = $response['discount_cards']['barcode'];
            $data['fio'] = ($response['discount_cards']['name_of_the_holder'] != '')?$response['discount_cards']['name_of_the_holder']:'';
            $data['phone'] = ($response['discount_cards']['phone_number'] != '')?$response['discount_cards']['phone_number']:'';
            $data['email'] = $response['discount_cards']['email'];

            $data['accumulation_previous_year'] = number_format($response['discount_cards']['accumulation_previous_year'], $ko, $fl, $th);
            $data['accumulation_current_year'] = number_format($response['discount_cards']['accumulation_current_year'], $ko, $fl, $th);
//            $data['amount_purchases_current_year'] = $response['discount_cards']['email'];
            $data['discount'] = $response['discount_cards']['discount'];
            $data['return_exchange_by_card'] = number_format(abs($response['discount_cards']['return_exchange_by_card']), $ko, $fl, $th);

            $sums = [
                'sum' => $response['discount_cards']['accumulation_current_year'],
                'return_exchange_by_card' =>
                    $response['discount_cards']['return_exchange_by_card']
            ];

            $amount_to_threshold = SiteHelper::amountToDiscountThreshold($sums);

            $data['amount_to_threshold'] =
                number_format($amount_to_threshold, $ko, $fl, $th);
//                number_format($response['discount_cards']['amount_to_threshold'], $ko, $fl, $th);

        }

        /*
         * Если штрихкод - сертификат
         */
        if(count($response['certificates']) != 0){
            $data['type'] = 'certificates';
            /*
             * Если значение поля "оприходован" - «нет»
             * то выдается сообщение «Нет такого сертификата».
             */
            if($response['certificates']['accrued'] == '0'){
                $data['type_message'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = d::getMessage('NO_SUCH_CERTIFICATE');
            }elseif($response['certificates']['cooked'] == '1'){
                /*
                 * Если значение поля "отоварен" - «да»
                 * то выдается сообщение «Сертификат уже отоварен»
                 */
                $data['type_message'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = d::getMessage('CERTIFICATE_ALREADY_COOKED');
            }else{

                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');

                /*
                 * Если sold_out - 0
                 * продан - начальное значение "нет - 0"
                 */
                if($response['certificates']['sold_out'] == '0'){
                    $data['table_row'] = '2';
                    $content = $this->renderAjax(
                        'shortcodes/sales-receipt-tb2-tr',
                        ['arr_c'=>$response['certificates']]
                    );
                }else{
                    /*
                     * Если продан(sold_out) - "да - 1"
                     */
                    $data['table_row'] = '4';
                    $content = $this->renderAjax(
                        'shortcodes/sales-receipt-tb4-tr',
                        ['arr_c'=>$response['certificates']]
                    );
                }

                $data['content'] = $content;
            }
        }

        /*
         * Если штрихкод - товар
         */
        if(count($response['product']) != 0){
            $data['type'] = 'product';
            if($response['message'] != ''){
                $data['type_message'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = $response['message'];
            }else{
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
//                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
//                $data['header'] = d::getMessage('HEADER_SUCCESS');
//
//                $data['message'] = 'Документ есть';

                /*
                 * Если поле "код документа(ID строки таблицы document)"
                 * не заполнено. Пусто.
                 */
                if($post['document_id'] == '') {
                    $data['table_row'] = '1';
                    $content = $this->renderAjax(
                        'shortcodes/sales-receipt-tb1-tr',
                        ['arr_p' => $response]
                    );
                }else{
                    $data['table_row'] = '3';
                    $content = $this->renderAjax(
                        'shortcodes/sales-receipt-tb3-tr',
                        ['arr_p' => $response]
                    );
                }

                $data['content'] = $content;

            }
        }

        /*
         * Если введеный штрихкод в БД не найден
         */
        if(!$response) {
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = d::getMessage('BARCODE_ERROR');
        }

        d::echoAjax($data);
    }

    /**
     * Страница "Товарный чек".
     * Кнопка "Сохранить"
     *
     * @return array
     */
    public function actionSaveSalesReceipt(){

        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $ajax = new Ajax;
        $post = d::secureEncode($_POST);

        $data = $ajax->saveSalesReceipt($post);

        d::echoAjax($data);
    }

    /**
     * Страница "Кассовый отчет".
     * Кнопка "Вывести отчет"
     *
     * @return array
     */
    public function actionCashReport(){

        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $data['sum_of_denominations'] = false;
        $ajax = new Ajax;
        $post = d::secureEncode($_POST);
        /*
         * Из настроек сайта получаем переменные для number_format
         */
        $ko = Yii::getAlias('@ko');// kopecks
        $fl = Yii::getAlias('@fl');// float
        $th = Yii::getAlias('@th');// thousand

        $data = $ajax->cashReport($post);

        // если что то нашлось
        if($data['document']) {
            // Data Template (данные для шаблона)
            $dt = [];
            // для сбора "строки" верстки
            $content = '';

            foreach ($data['document'] as $str) {

                /*
                 * Собираем массив, для отправки в шаблон
                 * ======================================
                 * Код документа
                 */
                $dt['id'] = $str['id'];

                // Время составления документа
                $dt['document_time'] = date('d.m.Y, H:i', $str['document_time']);

                // Документ контрагента, комментарий
                $dt['counterparty_document_comment'] =
                    $str['counterparty_document_comment'];

                // ФИО, документ покупателя, комментарий
                $dt['name_buyers_document_comment'] =
                    $str['name_buyers_document_comment'];

                // Дисконтная карта
                $dt['discount_card'] =
                    ($str['discount_card'] != NULL) ?
                        $str['discount_card'] : '';

                /*
                 * Сумма оплаты
                 * ============
                 * Ориентируемся по полю "Способ оплаты"
                 * и выводим данные в соответствующую ячейку
                 * а во вторую ячейку выводим пустоту.
                 * -----------------------------------------
                 * если "наличка"
                 */
                if ($str['payment_method_bank_card'] == '1') {
                    $dt['cash'] = number_format($str['payment_amount'], $ko, $fl, $th);
                    $dt['bank_card'] = '';
                } else {
                    // если "банковская карта"
                    $dt['cash'] = '';
                    $dt['bank_card'] = number_format($str['payment_amount'], $ko, $fl, $th);
                }
                // Сумма возврата наличными
                $dt['cash_repayment_amount'] =
                    number_format($str['cash_repayment_amount'], $ko, $fl, $th);

                // Сумма возврата на банковскую карту
                $dt['amount_of_refund_to_bank_card'] =
                    number_format($str['amount_of_refund_to_bank_card'], $ko, $fl, $th);

                // получаем шаблон верстки - строку tr для table
                $content .= $this->renderAjax(
                    'shortcodes/cash-report-tr',
                    ['cr' => $dt]
                );

            }// foreach $data['document']

            $data['content'] = $content;

            if($data['certificates']){
                $data['sum_of_denominations'] = 0;
                foreach($data['certificates'] as $nom){
                    $data['sum_of_denominations'] += $nom['certificate_denomination'];
                }
                $data['sum_of_denominations'] =
                    number_format($data['sum_of_denominations'], $ko, $fl, $th);
            }

            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('REPORT_RECEIVED');

        // if($data['document'])
        }else $data['errors'] = d::getMessage('DATE_NOT_FOUND');

        d::echoAjax($data);
    }

    /**
     * Страница "Работники".
     * Выпадающий список "Выберите работника"
     */
    public function actionGetUser()
    {
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        if($user = Ajax::getUser($_POST)){
            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('DATA_UPLOADED');
            foreach($user as $key=>$value){
                if($key == 'status') continue;
                if($key == 'id' AND $value == '6') continue;
                $data[$key] = $value;
            }
        }else{
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = d::getMessage('DATA_ERROR');
        }

        d::echoAjax($data);

    }// function actionSignup()

    /**
     * Страница "Работники".
     * Кнопка "Добавить пользователя"
     */
    public function actionSignup()
    {
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $data['list_options'] = '';

        /*
         * Первые два элемента списка
         * "Выберите работника" и "Добавить работника"
         * Эти два <option> Должны быть в начале выпадающего списка
         */
        $begin_list = [
            ['value' => '','text' => d::getMessage('SELECT_WORKER')],
            ['value' => 'new','text' => d::getMessage('ADD_WORKER')],
        ];

        foreach($begin_list as $option){
            $data['list_options'] .= $this->renderAjax(
                'shortcodes/options_list',[
                    'attributes' => BaseHtml::renderTagAttributes([ 'value' => $option['value'] ]),
                    'string' => $option['text']
                ]);
        }

        if(Yii::$app->request->post('type') == 'new'){
            $model = new SignupForm();
            if ($model->load(Yii::$app->request->post(),'')) {
                if ($user = $model->signup()) {

                    // Получаем всех пользователей
                    foreach(User::find()->orderBy('fio')->all() as $key=>$value){

                        if($value['id'] == '6') continue;

                        $attr['value'] = $value['id'];

                        if ($value['fio'] == $user['fio']){
                            $attr['selected'] = 'selected';
                        }else{
                            if(isset($attr['selected'])) unset($attr['selected']);
                        }

                        $data['list_options'] .= $this->renderAjax(
                            'shortcodes/options_list',[
                                'attributes' => BaseHtml::renderTagAttributes($attr),
                                'string' => $value['fio'],
                            ]
                        );
                    }

                    $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                    $data['header'] = d::getMessage('HEADER_SUCCESS');
                    $data['message'] = d::getMessage('NEW_USER_CREATE_SUCCESS');

                }else{
                    if($model->errors){
                        $str_error = d::getErrors( $model, $model->errors );
                        $data['message'] = $str_error;
                    }
                    $data['type'] = d::getMessage('TYPE_WARNING');
                    $data['header'] = d::getMessage('HEADER_WARNING');
                }
            }
        }elseif(Yii::$app->request->post('type') == 'edit'){
            // Получаем пользователя по ID
            $user = User::find()->where(['id' => Yii::$app->request->post('id')])->one();

            // Заполняем модель новыми данными
            $user->fio = Yii::$app->request->post('fio');
            $user->username = Yii::$app->request->post('username');
            $user->active = Yii::$app->request->post('active');
            $user->role = Yii::$app->request->post('role');

            // Если пароль не пуст, значит есть новый пароль для пользователя
            if(Yii::$app->request->post('password') != '')
                $user->password = Yii::$app->request->post('password');

            /*
             * Пробуем сохранить изменения
             * валидация тоже внутри save()
             */
            if($user->save()){
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $data['header'] = d::getMessage('HEADER_SUCCESS');
                $data['message'] = d::getMessage('RECORDING_UPDATED');

                // Получаем всех пользователей
                foreach(User::find()->orderBy('fio')->all() as $key=>$value){

                    if($value['id'] == '6') continue;

                    $attr['value'] = $value['id'];

                    if ($value['id'] == Yii::$app->request->post('id')){
                        $attr['selected'] = 'selected';
                    }else{
                        if(isset($attr['selected'])) unset($attr['selected']);
                    }

                    $data['list_options'] .= $this->renderAjax(
                        'shortcodes/options_list',[
                            'attributes' => BaseHtml::renderTagAttributes($attr),
                            'string' => $value['fio'],
                        ]
                    );
                }

            }
            else{
                $errors = $user->getErrors();
                // получаем сообщение об ошибке валидации
                $data['type'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = d::getErrors($user,$errors);
            }

        }

        d::echoAjax($data);

    }// function actionSignup()

    /**
     * Страница "Оприходование товара"
     * Страница "Выгрузка этикеток"
     * Страница "Товарный учет" - поле "Введите штрихкод"
     * Поле ввода штрихкода
     * ================================
     * По штрихкоду получаем информацию
     * из таблицы "product"
     */
    public function actionGetProductByBarcode()
    {
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
//        Yii::$app->request->post
        $post = d::secureEncode($_POST);

        if($product = Ajax::getProductByBarcode($post)){

            if(!$product['errors']){
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $data['header'] = d::getMessage('HEADER_SUCCESS');
                $data['message'] = d::getMessage('GOODS_RECEIVED');

                switch($post['page']){
                    case 'capitalizatoin-goods':// Оприходование товара
                        $data['row'] = $this->renderAjax('shortcodes/posting_row_product', $product);
                        break;
                    case 'uploading-labels':// Выгрузка этикетоа
                        $data['row'] = $this->renderAjax('shortcodes/unloading_labels_row', $product);
                        break;
                    case 'commodity-accounting':// Товарный учет
                        $data['row'] = $this->renderAjax('shortcodes/commodity-accounting-row', $product);
                        break;
                }
            }else{
                $data['type'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = $product['errors'];
            }

        }else{
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = d::getMessage('WRONG_BARCODE');
        }

        d::echoAjax($data);

    }// function actionGetProductByBarcode()

    /**
     * Страница "Оприходование товара".
     * Кнопка "Оприходовать"
     * ================================
     * Сохраняем данные в БД
     */
    public function actionDebitProduct()
    {
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
//        Yii::$app->request->post
        $post = d::secureEncode($_POST);

        $data = Ajax::debitProduct($post);

        d::echoAjax($data);

    }// function actionDebitProduct()

    /**
     * Страница "Оприходование сертификата".
     * Поле ввода штрихкода
     * ================================
     * По штрихкоду получаем информацию
     * из таблицы "product"
     */
    public function actionGetCertificateByBarcode()
    {
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
//        Yii::$app->request->post
        $post = d::secureEncode($_POST);

        if($certificate = Ajax::getCertificateByBarcode($post)){

            // Если сертификат оприходован
            if($certificate['credited']){
                $data['type'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = $certificate['credited'];
            }else{
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $data['header'] = d::getMessage('HEADER_SUCCESS');
                $data['message'] = d::getMessage('CERTIFICATE_RECEIVED');

                $data['row'] = $this->renderAjax( 'shortcodes/posting_row_certificate',$certificate );
            }
        }else{
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = d::getMessage('WRONG_BARCODE');
        }

        d::echoAjax($data);

    }// function actionGetCertificateByBarcode()

    /**
     * Страница "Оприходование сертификата".
     * Кнопка "Оприходовать"
     * ================================
     * Сохраняем данные в БД
     */
    public function actionDebitCertificate()
    {
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
//        Yii::$app->request->post
        $post = d::secureEncode($_POST);

        $data = Ajax::debitCertificate($post);
        if(!$data['errors']){
            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('DONE');
        }else{
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $data['errors'];
        }

        d::echoAjax($data);

    }// function actionDebitCertificate()

    /**
     * Страница "Выгрузка этикеток".
     * Кнопка "Добавить"
     * ================================
     * Получаем товар по номеру документа
     */
    public function actionGetProductsByDocumentId()
    {

        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
//        Yii::$app->request->post
        $post = d::secureEncode($_POST);

        $products = Ajax::getProductsByDocumentId($post);

        if(!$products['error']){

            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');

            $data['message'] = d::getMessage('DONE');
            $data['row'] = '';

            // Собираем HTML строку из шаблона
            foreach($products['products'] as $pt){
                /*
                 * Проверяем, если по одному штрихкоду
                 * количество товара больше одного
                 * то собибраем row html table
                 * из каждой единицы товара.
                 * Счетчик $i - начинается с 1
                 * так как счетчик ориентируется на количество товара
                 * т.е. количество "$pt['quantity']" - не может быть 0
                 */
                if($pt['quantity'] > 1){
                    for($i=1;$i < $pt['quantity'];$i++){
                        $data['row'] .=
                            $this->renderAjax( 'shortcodes/unloading_labels_row',$pt );
                    }
                }

                $data['row'] .=
                    $this->renderAjax( 'shortcodes/unloading_labels_row',$pt );
            }
            $data['message'] = d::getMessage('DONE');

        }else{

            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $products['error'];
        }

        d::echoAjax($data);

    }// function actionGetProductsByDocumentId()

    /**
     * Страница "Выгрузка этикеток".
     * Кнопка "Реестр"
     * =============================
     * Собранные со страницы данные
     * вставляем в файл Excel
     */
    public function actionCreateStringBarcodes()
    {

//        d::pe(count($_POST));

        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
        // Путь до файла Excel
        $file_excel = $_SERVER['DOCUMENT_ROOT'] . Yii::getAlias('@export_registry');

        // =================================================
        // =================================================
        // =================================================

        $php_excel = $_SERVER['DOCUMENT_ROOT'].
            '/backend/libraries/phpexcel/PHPExcel.php';

        $excel5 = $_SERVER['DOCUMENT_ROOT'].
            '/backend/libraries/phpexcel/PHPExcel/Writer/Excel5.php';

        if(file_exists($file_excel)){
            unlink($file_excel);
        }

        sleep(2);// Время на удаление файла 2сек

        // Подключение класса для работы с Excel
        require_once $php_excel;
//        // Подключение класса для вывода данных в формате Excel
        require_once $excel5;

        // Создание объекта класса PHPExcel
        $myXls = new \PHPExcel();
        // Указание на активный лист
        $myXls->setActiveSheetIndex(0);
        // Получение активного листа
        $mySheet = $myXls->getActiveSheet();
        // Указание названия листа книги
        $mySheet->setTitle("Новый лист");

        $myXls->getDefaultStyle()->getFont()->setName('Arial');
        $myXls->getDefaultStyle()->getFont()->setSize(10);

        //Поля документа
        $mySheet->getPageMargins()->setTop(0.2);
        $mySheet->getPageMargins()->setRight(0.2);
        $mySheet->getPageMargins()->setLeft(0.2);
        $mySheet->getPageMargins()->setBottom(0.63);

        // Назначение ширины столбцов
        $mySheet ->getColumnDimension("A")->setWidth(4.57);
        $mySheet ->getColumnDimension("B")->setWidth(63.7);
        $mySheet ->getColumnDimension("C")->setWidth(8.2);
        $mySheet ->getColumnDimension("D")->setWidth(8.2);
        $mySheet ->getColumnDimension("E")->setWidth(15.1);
        $mySheet ->getColumnDimension("F")->setWidth(4.57);

        // Назначение высоты строк
        $mySheet ->getRowDimension(1)->setRowHeight(14.25);

        // Settings for borders
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000'),
                ),
            ),
        );

        // =========================================
        //         Настройка первой строки
        // =========================================

        // Borders
//        $mySheet->getStyle('A1')->applyFromArray($styleArray);
//        $mySheet->getStyle('B1')->applyFromArray($styleArray);
//        $mySheet->getStyle('C1')->applyFromArray($styleArray);
//        $mySheet->getStyle('D1')->applyFromArray($styleArray);
//        $mySheet->getStyle('E1')->applyFromArray($styleArray);
//        $mySheet->getStyle('F1')->applyFromArray($styleArray);

        // Положение текста по центру
        $mySheet->getStyle("A")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $mySheet->getStyle("B")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $mySheet->getStyle("C")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $mySheet->getStyle("D")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $mySheet->getStyle("E")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $mySheet->getStyle("F")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $mySheet->setCellValue('A1', '№');
        $mySheet->setCellValue('B1', 'Содержание лист №1');
        $mySheet->setCellValue('C1', 'Размер');
        $mySheet->setCellValue('D1', 'Цена');
        $mySheet->setCellValue('E1', 'Штрихкод');
        $mySheet->setCellValue('F1', 'V');

        // =========================================
        //         /настройка первой строки
        // =========================================

        $string_number = 1;// Номер стоки страницы
        $page_53 = 1;// Счетчик строк каждой страницы
        $page_number = 1;// Номер страницы
        $post_i = 1;// Индекс для получения данных из POST
        $jj = 2;

//        $mySheet->getNumberFormat("E")->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        
        for($i=1;$i<(count($_POST)+1);$i++){
            // Назначение высоты строк
            $mySheet ->getRowDimension($jj)->setRowHeight(14.25);

            // Borders
            if($jj != 2) {
    $mySheet->getStyle('A' . ($jj - 1))->applyFromArray($styleArray);
    $mySheet->getStyle('B' . ($jj - 1))->applyFromArray($styleArray);
    $mySheet->getStyle('C' . ($jj - 1))->applyFromArray($styleArray);
    $mySheet->getStyle('D' . ($jj - 1))->applyFromArray($styleArray);
    $mySheet->getStyle('E' . ($jj - 1))->applyFromArray($styleArray);
    $mySheet->getStyle('F' . ($jj - 1))->applyFromArray($styleArray);
            }

            if($page_53 == 53){

                $page_53 = 1;
                $page_number += 1;

                $mySheet->setCellValue('A'.$jj, '№');
                $mySheet->setCellValue('B'.$jj, 'Содержание лист №'.$page_number);
                $mySheet->setCellValue('C'.$jj, 'Размер');
                $mySheet->setCellValue('D'.$jj, 'Цена');
                $mySheet->setCellValue('E'.$jj, 'Штрихкод');
                $mySheet->setCellValue('F'.$jj, 'V');

                /*
                 * Чтобы число отображалось как строка
                 * с ведущими нулями
                 */
//                $mySheet->getStyle('E'.$i)->
//                getNumberFormat()->
//                setFormatCode(
//                    \PHPExcel_Style_NumberFormat::
//                    FORMAT_CURRENCY_USD_SIMPLE
//                );

                 /*
                  * В текущей итерации есть строка с данными
                  * которую надо добавить,
                  * Но так как при вставке на 53ю строку нужно вставить заголовки
                  * то чтобы не пропускать текущие данные,
                  * счетчик $i в текущей итерации нужно увеличить на один.
                  * Тем самым, в текущей итерации
                  * вставим сразу две стоки Excel
                  */
                $j = $jj+1;
                $post_i--;
                $i--;

                $mySheet->setCellValue('A'.$j, $i);
                $mySheet->setCellValue('B'.$j, $_POST[$post_i]['description']);
                $mySheet->setCellValue('C'.$j, $_POST[$post_i]['manufacturer_size']);
                $mySheet->setCellValue('D'.$j, $_POST[$post_i]['retail_price']);
                $mySheet->
                setCellValue('E'.$j, '\''.$_POST[$post_i]['barcode']);
                $mySheet->setCellValue('F'.$j, '');

            }else{



                /*
                 * Чтобы число отображалось как строка
                 * с ведущими нулями
                 */
//                $mySheet->getStyle('E'.$i)->
//                getNumberFormat()->
//                setFormatCode(
//                    \PHPExcel_Style_NumberFormat::
//                    FORMAT_CURRENCY_USD_SIMPLE
//                );
                
                $mySheet->setCellValue('A'.$jj, $i);
                $mySheet->setCellValue('B'.$jj, $_POST[$post_i]['description']);
                $mySheet->setCellValue('C'.$jj, $_POST[$post_i]['manufacturer_size']);
                $mySheet->setCellValue('D'.$jj, $_POST[$post_i]['retail_price']);
                $mySheet->
                setCellValue('E'.$jj, '\''.$_POST[$post_i]['barcode']);
                $mySheet->setCellValue('F'.$jj, '');
                $page_53++;
                $string_number++;
            }
            $jj++;
            $post_i++;
        }
        
        /**
         * К последней строке, почему то не применяются стили
         * зададим стили последней строке в самом конце
         */
        $mySheet->getStyle('A' . ($jj - 1))->applyFromArray($styleArray);
        $mySheet->getStyle('B' . ($jj - 1))->applyFromArray($styleArray);
        $mySheet->getStyle('C' . ($jj - 1))->applyFromArray($styleArray);
        $mySheet->getStyle('D' . ($jj - 1))->applyFromArray($styleArray);
        $mySheet->getStyle('E' . ($jj - 1))->applyFromArray($styleArray);
        $mySheet->getStyle('F' . ($jj - 1))->applyFromArray($styleArray);

        // Вывод файла
        $objWriter = new \PHPExcel_Writer_Excel5($myXls);
        $objWriter->save($file_excel);


        // =================================================
        // =================================================
        // =================================================

        d::echoAjax($data);

    }// function actionCreateStringBarcodes()

    /**
     * Страница "Выгрузка этикеток".
     * Кнопка "Этикетки"
     * =============================
     * Из собранных со страницы данных
     * Формируем графические штрихкоды
     * и затем вставляем в файл Excel
     */
    public function actionCreateGraphicBarcodes()
    {
        $post = d::secureEncode($_POST);
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
        // Путь до файла Excel
        $file_excel = $_SERVER['DOCUMENT_ROOT'] . Yii::getAlias('@export_labels');

        // =================================================
        // =================================================
        // =================================================

        $php_excel = $_SERVER['DOCUMENT_ROOT'].
            '/backend/libraries/phpexcel/PHPExcel.php';

        $excel5 = $_SERVER['DOCUMENT_ROOT'].
            '/backend/libraries/phpexcel/PHPExcel/Writer/Excel5.php';

        if(file_exists($file_excel)){
            unlink($file_excel);
        }

        sleep(2);// Время на удаление файла 2сек

        // Подключение класса для работы с Excel
        require_once $php_excel;
//        // Подключение класса для вывода данных в формате Excel
        require_once $excel5;

        // Создание объекта класса PHPExcel
        $myXls = new \PHPExcel();
        // Указание на активный лист
        $myXls->setActiveSheetIndex(0);
        // Получение активного листа
        $mySheet = $myXls->getActiveSheet();
        // Указание названия листа книги
        $mySheet->setTitle("Новый лист");

        // Изменение размера и ориентации таблицы
        $mySheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        // Горизонтальное/вертикальное центрирование страницы
        $mySheet->getPageSetup()->setHorizontalCentered(false);
        $mySheet->getPageSetup()->setVerticalCentered(false);

        //Поля документа
        $mySheet->getPageMargins()->setTop(0);
        $mySheet->getPageMargins()->setRight(0);
        $mySheet->getPageMargins()->setLeft(0);
        $mySheet->getPageMargins()->setBottom(0.71);

        $myXls->getDefaultStyle()->getFont()->setName('Arial');
        $myXls->getDefaultStyle()->getFont()->setSize(10);


        // горизонтальное выравнивание содержимого
        $mySheet->getStyle("A")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $mySheet->getStyle("C")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $mySheet->getStyle("E")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $mySheet->getStyle("G")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // размер текста в ячейке
        $mySheet->getStyle("A")->getFont()->setSize(9);
        $mySheet->getStyle("C")->getFont()->setSize(9);
        $mySheet->getStyle("E")->getFont()->setSize(9);
        $mySheet->getStyle("G")->getFont()->setSize(9);

        // Назначение ширины ячеек
        $mySheet ->getColumnDimension('A')->setWidth(10.71);
        $mySheet ->getColumnDimension('B')->setWidth(15);
        $mySheet ->getColumnDimension('C')->setWidth(10.71);
        $mySheet ->getColumnDimension('D')->setWidth(15);
        $mySheet ->getColumnDimension('E')->setWidth(10.71);
        $mySheet ->getColumnDimension('F')->setWidth(15);
        $mySheet ->getColumnDimension('G')->setWidth(10.71);
        $mySheet ->getColumnDimension('H')->setWidth(15);

        // $ii - номер строки Excel
        $ii = 1;

        // Перебираем штрихкоды, формируя изображения
        for($i=0;$i<count($post);$i=$i+4){

            // шаблон необходимых столбцов одной строки Excel
            $row_excel = [
                /*
                 * Во вложенном массиве
                 * 0 - ячейка для текста
                 * 1 - ячейка для графического штрихкода
                 */
                ['A','B'],
                ['C','D'],
                ['E','F'],
                ['G','H']
            ];
            $iii = 0;
            // перебираем шаблон столбцов Excel
            foreach($row_excel as $el_tpl) {

                /*
                 * Проверка на пустоту одного элемента
                 * (текст+изображнеие) - считается одним элементом.
                 * Проверяем элемент с текстом
                 * ===============================================
                 * Это нужно для того,
                 * чтобы при нечетном количестве одной строки
                 * все элементы Excel были на своих местах.
                 * В строке должно быть 4 элемента
                 * но если в одной строке нет 4ёх элементов,
                 * то в одной строке может быть и 1 и 2 и тд...
                 */
                if($post[($i+$iii)]['info'] == '') continue;

                /*
                 * Положение текста(содержимого ячейи)
                 * выравнивание к верхнему краю - VERTICAL_TOP
                 */
                $mySheet->getStyle($el_tpl[0])
                    ->getAlignment()
                    ->setVertical(
                        \PHPExcel_Style_Alignment::VERTICAL_CENTER);

                // создаем строку Excel в формате (строка+изображение)
                self::createRowExcel(
                /*
                 * $myXls - объект PHPExcel
                 * $mySheet - активный лист Excel
                 * $post - данные со строками и штрихкодами
                 * $row_excel - массив с буквами столбцов Excel
                 * $key - ключ текущей итерации массива с буквами Excel
                 * $ii - номер строки Excel
                 * $iii - переменная нужна для того, чтобы
                 *        в одну строку Excel
                 *        вставить сразу 4 элемента
                 * $i - счетчик основного цикла for
                 */
                    $myXls,$mySheet,$post,$el_tpl,$ii,($i+$iii)
                );
                $iii++;
            }

            // переходим на следующую строку листа Excel
            $ii++;

        }

        // Вывод файла
        $objWriter = new \PHPExcel_Writer_Excel5($myXls);
        $objWriter->save($file_excel);

        // =================================================
        // =================================================
        // =================================================

        d::echoAjax($data);

    }// function actionCreateGraphicBarcodes()

    /*
     * Для итераций перебора шаблона строки Excel
     */
    private static function createRowExcel(
        $myXls,$mySheet,$post,$el_tpl,$ii,$i
    ){

        $mySheet->setCellValue($el_tpl[0] . $ii, $post[$i]['info']);
        $mySheet->getStyle($el_tpl[0] . $ii)
            ->getAlignment()->setWrapText(true);


        // ================================
        $graphic_barcode =
            BarcodeImage::barcode_print(
                $post[$i]['barcode'],
                $scale = 3,// Общий размер изображения штрикода
                $mode = "jpg",// Расширение изображения
                $total_y = 240 // Высота полос штрихкода
            );

        //  Добавление изображения из памяти в лист
        $objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
//            $objDrawing->setName('Изображение в памяти 1');
//            $objDrawing->setDescription('Изображение в памяти 1');
        $objDrawing->setCoordinates($el_tpl[1] . $ii);
        $objDrawing->setOffsetX(5);
        $objDrawing->setOffsetY(5);
        $objDrawing->setImageResource($graphic_barcode);
        $objDrawing->setRenderingFunction(
            \PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG
        );
        $objDrawing->
        setMimeType(
            \PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT
        );
//            $objDrawing->setHeight(100);
        $objDrawing->setWidth(90);
        // ======================================

        $objDrawing->setWorksheet($myXls->getActiveSheet());

        // Настройка высоты текущей строки
        $mySheet->getRowDimension($ii)->setRowHeight(61.5);

    }// function createRowExcel(...)

    /**
     * Страница "Поиск чека"
     * Поле "Введите штрихкод"
     * =============================
     * Поиск по введеному штрихкоду
     */
    public function actionCheckSearchBarcode(){

        $data = [];
        $data['tr'] = '';
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $post = d::secureEncode($_POST);

        // Получаем необходимый диапозон времени поиска
        switch($post['time_period']){
            // Неделя
            case'week':
                // Начальная дата - минус три дня
                $post['date_range_from'] =
                    date('Y-m-d',(strtotime($post['search_date']) - 259200));
                // Конечня дата - плюс три дня
                $post['date_range_to'] =
                    date('Y-m-d',(strtotime($post['search_date']) + 259200));
                break;
            // Две недели
            case'two_weeks':
                // Начальная дата - минус семь дней
                $post['date_range_from'] =
                    date('Y-m-d',(strtotime($post['search_date']) - 604800));
                // Конечня дата - плюс семь дней
                $post['date_range_to'] =
                    date('Y-m-d',(strtotime($post['search_date']) + 604800));
                break;
            // Один день
            default:
                $post['date_range'] = $post['search_date'];
        }

        $find_result = Ajax::checkSearchBarcode($post);

        if(!$find_result['error']){
            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');

            // Собираем HTML
            foreach($find_result as $item){
                $data['tr'] .=
                    $this->renderAjax( 'shortcodes/tr-check-search',$item );
            }

        }else{
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $find_result['error'];
        }

        d::echoAjax($data);
    }

    /**
     * Страница "Поиск чека"
     * Кнопка "Поиск"
     * =============================
     * Поиск по параметрам фильтра
     */
    public function actionCheckSearchFilter(){

        $data = [];
        $data['tr'] = '';
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $post = d::secureEncode($_POST);

        // Получаем необходимый диапозон времени поиска
        switch($post['time_period']){
            // Неделя
            case'week':
                // Начальная дата - минус три дня
                $post['date_range_from'] =
                    date('Y-m-d',(strtotime($post['search_date']) - 259200));
                // Конечня дата - плюс три дня
                $post['date_range_to'] =
                    date('Y-m-d',(strtotime($post['search_date']) + 259200));
                break;
            // Две недели
            case'two_weeks':
                // Начальная дата - минус семь дней
                $post['date_range_from'] =
                    date('Y-m-d',(strtotime($post['search_date']) - 604800));
                // Конечня дата - плюс семь дней
                $post['date_range_to'] =
                    date('Y-m-d',(strtotime($post['search_date']) + 604800));
                break;
            // Один день
            default:
                $post['date_range'] = $post['search_date'];
        }

        $find_result = Ajax::checkSearchfilter($post);

        // Проверка на ошибки
        if(!$find_result['error']){

            /**
             * Если ошибок нет
             * и результаты поиска НЕ пусты
             */
            if(count($find_result) > 0){
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');

                // Собираем HTML
                foreach($find_result as $item){
                    $data['tr'] .=
                        $this->renderAjax( 'shortcodes/tr-check-search',$item );
                }
            }else{
                /**
                 * Если ошибок нет
                 * но результаты поиска пусты
                 */
                $data['type'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = d::getMessage('CHECK_SEARCH_NOT_FOUND');
            }

        }else{
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $find_result['error'];
        }

        d::echoAjax($data);
    }

    /**
     * Страница "Товарный учет"
     * ===============================
     * Выпадающие списки
     * "Тип документа"
     * "Месяц"
     * "Год"
     * ---------------------------------
     * Получаем документы по запрошенным параметрам
     */
    public function actionGetDocuments(){
//        sleep(2);
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $data['options'] = '';

        $post = d::secureEncode($_POST);

        // $ds = documents
        $find_ds = Ajax::getDocuments($post);

        if(!$find_ds['errors']){

            array_unshift($find_ds['documents'],
                ['id' => ''],['id' => 'new']
            );

            foreach($find_ds['documents'] as $dt){

                $attr['value'] = $dt['id'];

                switch($dt['id']){
                    case '':
                        $string = 'Выберите документ';
                        break;
                    case 'new':
                        if($post['new'])
                            $attr['selected'] = 'selected';
                        $string = 'Добавить новый';
                        break;
                    default:
                        $attr['data-date'] = $dt['document_date'];
                        $attr['data-type'] = $dt['document_type_value'];

                        $string = $dt['document_type']
                            .' № '.$dt['id'].' от '.$dt['date_view'];
                }

                $data['document_options'] .= $this->renderAjax(
                    'shortcodes/options_list',[
                        'attributes' => BaseHtml::renderTagAttributes($attr),
                        'string' => $string,
                    ]
                );
                unset($attr['selected']);
            }

            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('DOCUMENTS_FOUND');

        }else{
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $find_ds['errors'];
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Остатки ШК"
     */
    public function actionAccountBalance(){
//        sleep(2);
        $data = [];
        $data['rows'] = '';
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $post = d::secureEncode($_POST);

        $result = Ajax::accountBalance($post);

        if(!$result['errors']){
//            d::pe($result);
            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('DOCUMENT_TO_ZERO_SUCCESS');

            foreach($result['goods_movement'] as $row){
                $data['rows'] .= $this->renderAjax(
                    'shortcodes/commodity-accounting-row',$row
                );
            }

        }else{
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $result['errors'];
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Сохранить документ"
     * ---------------------------
     * Сохраняем данные в БД
     */
    public function actionSaveCommodityAccounting(){
//        sleep(2);
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $post = d::secureEncode($_POST);

        $result = Ajax::saveCommodityAccounting($post);

        if(!$result['errors']){
//            d::pe($result);
            /*
             * Если было списание документа на ноль,
             * то был изменен тип документа
             * значит нужно запустить создание нового документа
             * с новым типом документа
             * action_type - меняем на "new"
             */
            if($post['new_document_type']){
                $post['action_type'] = 'new';

                // Создание нового документа с новым типом
                $result_new = Ajax::saveCommodityAccounting($post);

                if(!$result_new['errors']){
                    $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                    $data['header'] = d::getMessage('HEADER_SUCCESS');
                    $data['message'] = d::getMessage('DONE');
                    $data['document_id'] = $result_new['document_id'];
                    $data['month'] = date('m',time());
                    $data['year'] = date('Y',time());
                }else{
                    $data['type_message'] = d::getMessage('TYPE_WARNING');
                    $data['type'] = d::getMessage('TYPE_WARNING');
                    $data['header'] = d::getMessage('HEADER_WARNING');
                    $data['message'] = $result_new['errors'];
                }

            }else{
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $data['header'] = d::getMessage('HEADER_SUCCESS');
                $data['message'] = d::getMessage('DOCUMENT_SAVED');
                $data['document_id'] = $result['document_id'];
                $data['month'] = date('m',time());
                $data['year'] = date('Y',time());
            }

            // Списание документа на ноль
            if($post['action_type'] == 'disabled_document_id'){
                if(!$result['errors']){
                    $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                    $data['header'] = d::getMessage('HEADER_SUCCESS');
                    $data['message'] = d::getMessage('DOCUMENT_TO_ZERO_SUCCESS');
                }
            }

        }else{
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['type'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $result['errors'];
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Отправка Eamil"
     * ===============================
     * Кнопка "Отправить Email"
     *
     * @echo json string
     */
    public function actionSendMail(){
//        sleep(2);
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

//		d::pe($_POST);
		
        if(isset($_POST['send_mail'])){

            $to  = $_POST['send_mail'];

            $subject = "Тема письма";

//            $message = $this->renderAjax('shortcodes/email/serebros');
            $message = 'Тело <b>HTML</b> письма';

            $headers  = "Content-type: text/html; charset=utf-8 \r\n";
            $headers .= "From: WebMaster <romeo@romeo-man.ru>\r\n";
            $headers .= "Reply-To: romeo@romeo-man.ru\r\n";

            if(mail($to, $subject, $message, $headers)){
                $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $data['header'] = d::getMessage('HEADER_SUCCESS');
                $data['message'] = d::getMessage('SUCCES_SAND_MAIL');
            }else{
                $data['type_message'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = d::getMessage('ERROR_SAND_MAIL');
            }

        }else{
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = d::getMessage('NO_SAND_MAIL');
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Товарный учет"
     * ===============================
     * Выпадающий список "Выберите документ"
     */
    public function actionGetFromGoodsMovement(){
//        sleep(2);
        $data = [];
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $data['rows'] = '';

        $post = d::secureEncode($_POST);

        $dt = Ajax::getFromGoodsMovement($post);
        $data['dg'] = $dt['dg'];
        if(!$dt['errors']){

            foreach($dt['goods_movement'] as $item){

                d::jtd($item);
                $data['rows'] .=
                    $this->renderAjax('shortcodes/commodity-accounting-row',$item);
            }

            $data['comment'] = $dt['document']['comment'];

            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('DATA_UPLOADED');
        }else{
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = $dt['errors'];
        }

        d::echoAjax($data);

    }

    /**
     * Главная шапка сайта "количество заказов онлайн"
     * ===============================================
     */
    public function actionCountOrdersOnline(){
//        sleep(2);
        $data = [];

        // Получение количества заказов
        if($orders = GetData::getOrders()){
            $data['count_orders'] = count($orders);
        }else $data['count_orders'] = 0;

        d::echoAjax($data);

    }

    /**
     * Страница "Заказы"
     * =================
     * Выпадающий список "Новые заказы"
     * --------------------------------
     * Получаем заказ по ID
     */
    public function actionGetOrders(){
//        sleep(2);
        $data = [];
        $data['rows'] = '';

        $post = d::secureEncode(Yii::$app->request->post());

        $order = Orders::find()
            ->where(['id'=>$post['order_id']])
            ->asArray()->one();

        /*
         * Если что то нашлось
         * соберем необходимую информацию из других таблиц БД
         */
        if($order){
            // Номер заказа
            $data['order_number'] = $order['name'];
            // Комментарий
            $data['comment'] = $order['comment'];

            // Выбираем данные пользователя
            $user_data = CustomerData::find()
                ->where([
                    'id_customer_profile'=>$order['id_customer_profile'],
                    'delete_at'=>NULL ])
                ->andWhere([ 'in','id_data_type', ['first_name','phone'] ])
                ->asArray()->all();

//            d::pe($user_data);

            // Если пользователь найден
            if($user_data){
                foreach($user_data as $item){
                    $data[$item['id_data_type']] = $item['user_data'];
                }
            }else{
                $data['first_name'] = '';
                $data['phone'] = '';
            }

            /*
             * Из таблицы "order_products"
             * получим все товары оп ID заказа
             */
            $order_products = OrderProducts::find()
                ->where(['orders_id'=>$order['id']])
                ->asArray()->all();
            /*
             * Если по заказу нашлись
             * какие нибудь товары
             */
            if($order_products){
                foreach($order_products as $pt){

                    $tpl_data = [];

                    /*
                     * Из таблицы "product"
                     * получим остальную информацию о товаре
                     */
                    $product = Product::findOne(['barcode'=>$pt['barcode']]);
                    // Если строка товара по штрихкоду найдена
                    if($product){
                        $ptne = ProductNomenclature::findOne([
                            'id'=>$product['item_code']]);
                        // Цвет
                        $color = Color::findOne(['id'=>$ptne['code_color']]);
                        // Рисунок/узор
                        $design = Color::findOne(['id'=>$ptne['code_pattern']]);
                        // Размер производителя
                        $size = SizeManufacturer::findOne(['id'=>$product['code_manufacturer_size']]);

                        $tpl_data['info'] = $ptne['nomenclature_name'].(($color)?', '.$color['name']:'').(($design)?', '.$design['name']:'');
                        $tpl_data['size'] = ($size)?$size['name']:'';
                        $tpl_data['barcode'] = ($size)?$size['name']:'';
                        $tpl_data['retail_price'] = $product['retail_price'];

                        // Если есть авторматическая скидка
                        if($product['automatic_discount'] != 0) {
                            $tpl_data['discount_price'] =
                                $product['retail_price'] -
                                (($product['retail_price'] / 100) * $product['automatic_discount']);

                        }else $tpl_data['discount_price'] = $product['retail_price'];

                        $tpl_data['discount'] = $product['automatic_discount'];

                        $data['rows'] .= $this->renderAjax('shortcodes/tr-orders',$tpl_data);

                    }
                }
            }
        }

        d::echoAjax($data);

    }

    /**
     * Страница "Заказы"
     * =================
     * Кнопка "Сохранить"
     */
    public function actionOrderStatusChange(){
//        sleep(2);
        $data = [];
        // Первый элемент выпадающего списка
        $data['new_orders_rows'] = $this->renderAjax(
            'shortcodes/options_list',[
                'attributes' => BaseHtml::renderTagAttributes(['value'=>'']),
                'string' => 'Новые заказы',
            ]
        );

        // Первый элемент выпадающего списка
        $data['ready_orders_rows'] = $this->renderAjax(
            'shortcodes/options_list',[
                'attributes' => BaseHtml::renderTagAttributes(['value'=>'']),
                'string' => 'Готовые заказы',
            ]
        );
        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $post = d::secureEncode(Yii::$app->request->post());

        if(Ajax::orderStatusChange($post)){

            $new_orders = GetData::getOrders();
            $ready_orders = GetData::getOrders('ready_orders');

            // Собираем options для select "Новые заказы"
            if($new_orders){
                $attr = [];
                foreach($new_orders as $order){

                    $attr['value'] = $order['id'];
                    if($order['id'] == $post['order_id']){
                        $attr['selected'] = 'selected';
                    }else unset($attr['selected']);

                    $data['new_orders_rows'] .= $this->renderAjax(
                        'shortcodes/options_list',[
                            'attributes' => BaseHtml::renderTagAttributes($attr),
                            'string' => $order['name'],
                        ]
                    );
                }
            }

            // Собираем options для select "Готовые заказы"
            if($ready_orders){
                $attr = [];
                foreach($ready_orders as $order){

                    $attr['value'] = $order['id'];
                    if($order['id'] == $post['order_id']){
                        $attr['selected'] = 'selected';
                    }else unset($attr['selected']);

                    $data['ready_orders_rows'] .= $this->renderAjax(
                        'shortcodes/options_list',[
                            'attributes' => BaseHtml::renderTagAttributes($attr),
                            'string' => $order['name'],
                        ]
                    );
                }
            }

            $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $data['header'] = d::getMessage('HEADER_SUCCESS');
            $data['message'] = d::getMessage('CHANGE_SAVED');
        }else{
            $data['type_message'] = d::getMessage('TYPE_WARNING');
            $data['header'] = d::getMessage('HEADER_WARNING');
            $data['message'] = d::getMessage('CHANGE_SAVED_ERROR');
        }

        d::echoAjax($data);

    }



















    /** ==================================================================
     * тестовый метод Debug
     */
    public function actionDebug(){

        $data = [
            'sum' => 3005,
            'return_exchange_by_card' => 0
        ];

        $rr = SiteHelper::amountToDiscountThreshold($data);

        d::pe($rr);




//        $ajax = new Ajax;
//        $data = [];
//        $data['status'] = d::getMessage('AJAX_STATUS_ERROR');

//        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');

//        d::echoAjax($data);
    }

}// End Class
