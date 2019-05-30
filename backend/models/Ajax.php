<?php
/**
 * Класс для работы с Ajax запросами
 */
namespace backend\models;

use app\models\Color;
use app\models\Design;
use app\models\Document;
use app\models\DocumentType;
use app\models\GoodsMovement;
use app\models\Product;
use app\models\ProductGroup;
use app\models\ProductNomenclature;
use app\models\Provider;
use app\models\SizeManufacturer;
use common\components\Barcodes;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use backend\controllers\MainController as d;
use backend\phpexcel\PHPExcel\PHPExcel_IOFactory;
use common\components\GeneralRepository;
use app\models\FilesExcel;
use yii\helpers\ArrayHelper;

//sleep(2);

class Ajax extends Model{

    /**
     * Страница "Импорт файлов Excel".
     * ===============================
     * Ajax загрузка файлов Excel
     *
     * @return array $data
     */
    public function uploadFiles()
    {

        if(isset($_FILES['excel'])){

            // получаем расширение файла
            $ext = d::getExtension($_FILES['excel']['name']);
            // разрешенные расширения файлов
            $allowed_ext = ['xlsx','xls'];

            $post = d::secureEncode($_POST);

            // если расширение не правильное - вернем ошибку
            if(!in_array($ext,$allowed_ext)){
                $data['type_message'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = d::getMessage('WRONG_FORMAT');
            } else {

                $filename_unix = $post['type_file'].'_'.date('Y-m-d_H-i-s',time()+3600);

                $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/common/files/excel/';
                $file = $uploaddir . $filename_unix . '.' . $ext;

                if (move_uploaded_file($_FILES['excel']['tmp_name'], $file)) {

                    $customer = new FilesExcel();
                    $customer->name = $filename_unix;
                    $customer->ext = $ext;
                    $customer->type_file = $post['type_file'];
                    $customer->save();

                    $file_todb =
                        $this->readExelFile($filename_unix . '.' . $ext,$post['type_file']);

                    // Если файл загружен и данные в БД добавлены
                    if($file_todb['success']){
                        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                        $data['header'] = d::getMessage('HEADER_SUCCESS');
                        $data['message'] = $file_todb['success'];
                        $data['duplicates'] = $file_todb['duplicates'];
                        $data['not_existing'] = $file_todb['not_existing'];
                    }
                    // Если файл загружен, но данные в БД не добавлены.
                    if($file_todb['error']){
                        $data['type_message'] = d::getMessage('TYPE_WARNING');
                        $data['header'] = d::getMessage('HEADER_WARNING');
                        $data['message'] = $file_todb['error'];
                    }
                    // Если все строки файла уже присутствуют в БД
                    if(!$file_todb['success'] AND !$file_todb['error']){
                        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                        $data['type_message'] = d::getMessage('TYPE_WARNING');
                        $data['header'] = d::getMessage('HEADER_WARNING');
                        $data['message'] = d::getMessage('ALL_DATA_DUPLICATE');
                        $data['duplicates'] =
                            d::getMessage('FILE_SUCCESS_ALL_DATA_DUPLICATE');
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Страница "Импорт файлов Excel".
     * ===============================
     * Читаем excel файл и преобразуем данные в массив
     *
     * @return array
     */
    public function readExelFile($file_name,$type_file){
        $phpexcel_path = $_SERVER['DOCUMENT_ROOT'].
            '/backend/libraries/phpexcel/PHPExcel/IOFactory.php'
        ;
        require_once $phpexcel_path;
        $rows = [];
        $arr_todb = [];
        $barcodes = [];// массив для where выборки
        $barc = [];// сюда помещаем выбранное(если что то выберется)
        $return = [];// Возвращаемый результирующий массив
        /*
         * Сбор существующих в БД штрихкодов
         * т.е. те штрихкоды, которые уже есть в БД
         * нужно вывести на экране, в окне предупреждения
         */
        $return['duplicates'] = '';
        // Не существующие в БД штрихкоды
        $return['not_existing'] = '';

        $file_excel = $_SERVER['DOCUMENT_ROOT'] . '/common/files/excel/'.$file_name;

        // Открываем файл
        $xls = PHPExcel_IOFactory::load($file_excel);

        // собираем данные в массив
        foreach($xls ->getWorksheetIterator() as $key=>$worksheet) {
            if(trim($worksheet->toArray()[0][0]) == '') continue;
            else $lists[] = $worksheet->toArray();
        }

        /*
         * Перебираем массив и делаем конечный обработанный массив.
         * Пустые элементы и элементы где кроме цифр присутствуют
         * ещё какие то симовлы - пропускаем
         * ======================================================
         * В массиве $rows во вложенных массивах
         * в ключах должны быть имена ячеек таблицы БД
         */
        $i=0;
        foreach($lists[0] as $key3=>$v){
            /*
             * Проверка строки на цифры
             * если это не сплошные цифры, значит это строка
             * значит пропускаем итерацию
             */
            if(!ctype_digit($v[0])) continue;
            else{
                $rows[$i]['barcode'] = $v[0];
                $barcodes[] = $v[0];
                switch($type_file){
                    // Если тип файла "сертификаты"
                    case 'certificates':
                        $rows[$i]['certificate_denomination'] = $v[1];
                        break;
                    // Если тип файла "дисконтная карта"
                    case 'discount-cards':
                        $rows[$i]['discount'] = $v[1];
                        break;
                    // Если тип файла "автоматические скидки"
                    case 'automatic-discount':
                        $rows[$i]['action_price'] = $v[1];
                        $rows[$i]['automatic_discount'] = $v[2];
                        $rows[$i]['date_of_promotion_discounts'] = (strtotime($v[3]))?$v[3]:'';
                        $rows[$i]['end_date_of_promotion_discount'] = (strtotime($v[4]))?$v[4]:'';
                        break;
                }
            }
            $i++;
        }

        /*
         * Пробуем получить из (той или иной) таблицы
         * строки по списку штрих кодов
         * если что есть, то такие штрихкоды исключаем из массива
         * который будем отдавать на запись в БД
         */
        switch($type_file){
            // Если тип файла "сертификаты"
            case 'certificates':
                $table_name = Certificates::tableName();
                $barc = Certificates::find()->where(['barcode'=>$barcodes])->all();
                break;
            // Если тип файла "дисконтная карта"
            case 'discount-cards':
                $table_name = DiscountCards::tableName();
                $barc = DiscountCards::find()->where(['barcode'=>$barcodes])->all();
                break;
        }

        // Если загрузка сертификатов или дисконтных карт
        if($type_file == 'certificates' OR $type_file == 'discount-cards') {


            /*
             * Если что то выбралось
             * то из массива, который для записи в БД
             * нужно удалить элементы, которые уже есть в БД
             */
            if (count($barc) != 0) {
                // Собираем массив с дупликатами
                foreach ($barc as $v) $barcs[] = $v['barcode'];
                /*
                 * Перебираем очищеный массив $rows
                 * собранный из Excel
                 */
                foreach ($rows as $k => $v) {
                    /*
                     * Проверяем, если в массиве с дупликатами
                     * присутствует штрихкод текущей итерации,
                     * то добавляем его в строку дупликатов
                     * и пропускаем итерацию
                     */
                    if (in_array($v['barcode'], $barcs)) {
                        $return['duplicates'] .= '<b>' . $v['barcode'] . '</b>, ';
                        continue;
                    } else $arr_todb[] = $v;// Собираем штрихкоды, которых ещё нет БД
                }
                // Убираем с конца строки пробел и запятую
                $return['duplicates'] = substr($return['duplicates'], 0, -2);
                $return['duplicates'] =
                    d::getMessage('DUPLICATE_HEADER') . $return['duplicates'];
            }// если из БД ничего не выбрано, то добавляем весь массив в БД
            else $arr_todb = $rows;

            /*
             * Если после всех проверок на дубликаты
             * в массиве ещё остались штрихкоды для доабвления в БД
             * то добавляем их в БД
             */
            if (count($arr_todb) != 0) {
                /*
                 * Если по какой то причине добавление в БД выдало ошибку
                 * то сообщаем о том, что файл загружен, а данные в БД
                 * не добавлены
                 */
                $add_result = GeneralRepository::insertSeveral($table_name, $arr_todb);
                if ($add_result['errors']) {
                    $return['error'] = d::getMessage('FILE_SUCCESS_DATA_ERROR');
                }// Если данные в БД добавлены успешно
                else $return['success'] = d::getMessage('FILE_UPLOADED_DATABASE_DATA_ADDED');

            } else {
                /*
                 * Если все имеющиеся в файле штрихкоды, уже есть в БД
                 * то не выводим их всех на экран, а просто сообщаем, что
                 * файл загружен, но данные в БД не добавлены, потому что
                 * все строки файла уже присутствуют в БД
                 */
                $return['duplicates'] = d::getMessage('FILE_SUCCESS_ALL_DATA_DUPLICATE');
            }
        }else{
            // Если загрузка автоматических скидок

            // Делаем выборку из таблицы "Product" по штрихкодам
            $barc = Product::find()->where(['barcode'=>$barcodes])->all();

            /*
             * Если из таблицы "Product" что то выбралось
             * то из массива Excel возьмем только те штрихкоды
             * которые выбрались из БД
             * Остальные выведем на экран, как не существующие.
             */
            if (count($barc) != 0) {
                /*
                 * Из массива $rows выбираем все штрихкоды
                 * которые есть в "Product"
                 * и заносим их в массив $_GET[bacrs]
                 */
                foreach($barc as $v){
                    array_walk($rows,function($value,$key,$search){
                        if($value['barcode'] == $search)
                            $_GET['barcs'][] = $search;
                    },$v['barcode']);
                }

                /*
                 * Перебираем массив $rows в конечный массив $arr_todb
                 * пропуская штрихкоды, которых нет в БД
                 * пропущенные штрихкоды пишем в строку,
                 * чтобы вывести на экран
                */
                foreach($rows as $v){
                    /*
                     * Если в БД штрихкод не найден, пропускаем его.
                     * И заносим его в строку пропущеных
                     */
                    if(!in_array($v['barcode'],$_GET['barcs'])){
                        $return['not_existing'] .= '<b>'.$v['barcode'].'</b>, ';
                        continue;
                    }else $arr_todb[] = $v;
                }
                $return['not_existing'] = d::getMessage('NOT_EXISTING_HEADER') . (substr($return['not_existing'],0,-2));

                // =================================
                // тут делаем UPDATE таблицы Product
                // =================================

                $where_dc = '';
                $query_dc = "UPDATE `product` SET ";

                foreach($arr_todb as $b){
                    // собираем SQL строку
                    $query_dc .= "`action_price`= CASE
                        WHEN `barcode`='{$b['barcode']}'
                        THEN '{$b['action_price']}'
                        ELSE `action_price` END, ";
                    $query_dc .= "`automatic_discount`= CASE
                        WHEN `barcode`='{$b['barcode']}'
                        THEN '{$b['automatic_discount']}'
                        ELSE `automatic_discount` END, ";
                    $query_dc .= "`date_of_promotion_discounts`= CASE
                        WHEN `barcode`='{$b['barcode']}'
                        THEN '{$b['date_of_promotion_discounts']}'
                        ELSE `date_of_promotion_discounts` END, ";
                    $query_dc .= "`end_date_of_promotion_discount`= CASE
                        WHEN `barcode`='{$b['barcode']}'
                        THEN '{$b['end_date_of_promotion_discount']}'
                        ELSE `end_date_of_promotion_discount` END, ";
                    // собираем строку для WHERE IN
                    $where_dc .= "'".$b['barcode']."',";
                }

                // убираем с конца строки лишние символы
                $query_dc = substr($query_dc, 0, -2);
                $where_dc = substr($where_dc, 0, -1);

                // дополняем SQL строку WHERE IN
                $query_dc .= " WHERE `barcode` IN (".$where_dc.')';

                $update_dc = Yii::$app->db->createCommand($query_dc);
                try {
                    $update_dc->execute();
                    $return['success'] = d::getMessage('FILE_UPLOADED_DATABASE_DATA_ADDED');
                }catch (Exception $e){
                    $return['errors'] .= d::getMessage('FILE_SUCCESS_DATA_ERROR');
                }

            }// Если из БД не выбрано ни одного значения по штрихкоду
            else $return['not_existing'] = d::getMessage('NO_BAR_CODES_FOUND');
        }

        return $return;

    }

    /**
     * Страница "Загрузка Excel CDB".
     * ===============================
     * Ajax загрузка файлов Excel
     *
     * @return array $data
     */
    public function uploadFilesExcelTmpl()
    {

        if(isset($_FILES['excel'])){

            // получаем расширение файла
            $ext = d::getExtension($_FILES['excel']['name']);
            // разрешенные расширения файлов
            $allowed_ext = ['xlsx','xls'];

            $post = d::secureEncode($_POST);

            // если расширение не правильное - вернем ошибку
            if(!in_array($ext,$allowed_ext)){
                $data['type_message'] = d::getMessage('TYPE_WARNING');
                $data['header'] = d::getMessage('HEADER_WARNING');
                $data['message'] = d::getMessage('WRONG_FORMAT');
            } else {

                $filename_unix = $post['type_file'].'__'.date('Y-m-d_H-i-s',time()+3600);

                $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/common/files/excel/';
                $file = $uploaddir . $filename_unix . '.' . $ext;

                if (move_uploaded_file($_FILES['excel']['tmp_name'], $file)) {

                    $customer = new FilesExcel();
                    $customer->name = $filename_unix;
                    $customer->ext = $ext;
                    $customer->type_file = $post['type_file'];
                    $customer->save();

                    $file_todb =
                        $this->readExelFileTmpl($filename_unix . '.' . $ext,$post);

                    // Если файл загружен и данные в БД добавлены
                    if($file_todb['success']){
                        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                        $data['header'] = d::getMessage('HEADER_SUCCESS');
                        $data['message'] = $file_todb['success'];
                    }
                    // Если файл загружен, но данные в БД не добавлены.
                    if($file_todb['error']){
                        $data['type_message'] = d::getMessage('TYPE_WARNING');
                        $data['header'] = d::getMessage('HEADER_WARNING');
                        $data['message'] = $file_todb['error'];
                    }
                    // Если все строки файла уже присутствуют в БД
                    if(!$file_todb['success'] AND !$file_todb['error']){
                        $data['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                        $data['type_message'] = d::getMessage('TYPE_WARNING');
                        $data['header'] = d::getMessage('HEADER_WARNING');
                        $data['message'] = d::getMessage('ALL_DATA_DUPLICATE');
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Страница "Загрузка Excel CDB".
     * ===============================
     * Читаем excel файл и преобразуем данные в массив
     *
     * @return array
     */
    public function readExelFileTmpl($file_name,$post){
        $phpexcel_path = $_SERVER['DOCUMENT_ROOT'].
            '/backend/libraries/phpexcel/PHPExcel/IOFactory.php'
        ;
        require_once $phpexcel_path;
        $rows = [];
        $return = [];// Возвращаемый результирующий массив
        $i=0;

        $file_excel = $_SERVER['DOCUMENT_ROOT'] . '/common/files/excel/'.$file_name;

        // Открываем файл
        $xls = PHPExcel_IOFactory::load($file_excel);

        // собираем данные в массив
        foreach($xls ->getWorksheetIterator() as $key=>$worksheet) {
            if(trim($worksheet->toArray()[0][0]) == '') continue;
            else $lists[] = $worksheet->toArray();
        }

        // Удаляем первую строку заголовков Excel
        array_shift($lists[0]);

        /*
         * Перебираем массив и делаем конечный обработанный массив.
         * ========================================================
         * В массиве $rows во вложенных массивах
         * в ключах должны быть имена ячеек таблицы БД
         */
        foreach($lists[0] as $key=>$v){

            switch($post['data_type']){
                // Если тип файла "Бренд, Товарная группа"
                case 'brand-product-group':
                    $rows[$i][] = $v[0];
                    $rows[$i][] = $v[1];
                    break;
                // Если тип файла "Размер производителя"
                case 'size-manufacturer':
                    $rows[$i][] = $v[0];
                    break;
                // Если тип файла "Номенклатура"
                case 'product-nomenclature':
                    $rows[$i][] = $v[0];// Наименование номенклатуры
                    $rows[$i][] = $v[1];// Артикул производителя
                    $rows[$i][] = $v[2];// Надпись на этикетке
                    $rows[$i][] = $v[3];// Код товарной группы
                    $rows[$i][] = $v[4];// Код пол
                    $rows[$i][] = $v[5];// Код бренд
                    break;
                // Если тип файла "Товар"
                case 'product':
                    $rows[$i]['nomenclature_name'] = $v[0];// Номенклатурный код
                    $rows[$i]['retail_price'] = $v[1];// Розничная цена
                    $rows[$i]['code_manufacturer_size'] = $v[2];// Размер производителя
                    $rows[$i]['barcode'] = $v[3];// Штрихкод
                    $rows[$i]['date_1c'] = $v[4];// Дата поступлния товара в 1С
                    $rows[$i]['cost_of_goods'] = $v[5];// Себестоимость
                    break;
                }
            $i++;
        }

        // Если файл Excel не пуст
        if (count($rows) != 0) {

            switch($post['data_type']){
                case'brand-product-group':
                    /*
                     * Запись в таблицу "Бренд/Товарная группа"
                     * brand/product_group
                     */
                    // Порядок имен полей таблицы
                    $fields = ['code','name'];
                    break;
                case'size-manufacturer':
                    /*
                     * Запись в таблицу "Размер производителя"
                     * size_manufacturer
                     */
                    // Порядок имен полей таблицы
                    $fields = ['name'];
                    break;
                case'product-nomenclature':
                    // Порядок имен полей таблицы
                    $fields = [
                        'nomenclature_name',// Наименование номенклатуры
                        'article_of_manufacture',// Артикул производителя
                        'labeling',// Надпись на этикетке
                        'commodity_group_code',// Код товарной группы
                        'code_sex',// Код пол
                        'brand_code',// Код бренд
                    ];
                    break;
                case'product':
                    $pn = [];
                    $sm = [];
                    $product_todb = [];
                    $i = 0;
                    /*
                     * ID таблицы size_manufacturer
                     * который содержит в себе строку "без размера"
                     */
                    $no_size = '1';
                    // Получаем все строки из таблицы "Номенклатура товара"
                    $pnre =
                        ProductNomenclature::find()
                            ->select('id, nomenclature_name')
                            ->all();
                    // Получаем все строки из таблицы "Размер производителя"
                    $smer =
                        SizeManufacturer::find()
                            ->select('id, name')
                            ->all();

                    /*
                     * Создаем массивы, у которых в ключах будут занчения name
                     * а в значениях будут ID (т.е. код справочника)
                     */
                    foreach($pnre as $value)
                        $pn[$value['nomenclature_name']] = $value['id'];
                    foreach($smer as $value)
                        $sm[$value['name']] = $value['id'];

                    /*
                     * Перебираем массив из Excel
                     * ==========================
                     * Собираем конечный массив
                     * для записи в таблицу "product"
                     */
                    foreach($rows as $value){
                        /*
                         * Если ячейки Excel размер пустая
                         * то присвоим туда ID-1 таблицы size_manufacturer
                         * у которой под ID-1 хранится строка: "без размера"
                         */
                        if($value['code_manufacturer_size'] == '') $size = $no_size;
                        /*
                         * Из массива "Размер производителя"
                         * по имени ключа получаем ID (код размера)
                         */
                        else $size = $sm[$value['code_manufacturer_size']];
                        $product_todb[$i] = [
                            // Номенклатурный код
                            /*
                             * Из массива "Номенклатура товара"
                             * по имени ключа получаем ID (код номенклатуры)
                             */
                            $pn[$value['nomenclature_name']],
                            // Розничная цена
                            $value['retail_price'],
                            // Размер производителя
                            $size,
                            // Штрихкод
                            $value['barcode'],
                            // Дата поступлния товара в 1С
                            $value['date_1c'],
                            // Себестоимость
                            $value['cost_of_goods'],
                        ];
                        $i++;
                    }

                    // Порядок имен полей таблицы
                    $fields = [
                        'item_code',// Номенклатурный код
                        'retail_price',// Розничная цена
                        'code_manufacturer_size',// Размер производителя
                        'barcode',// Штрихкод
                        'date_1c',// Дата поступлния товара в 1С
                        'cost_of_goods',// Себестоимость
                    ];
                    $rows = $product_todb;
                    break;
            }

            $command = Yii::$app->db->createCommand()->batchInsert(
                $post['type_file'], $fields, $rows
            );

            try {
                $command->execute();
                $return['success'] =
                    d::getMessage('FILE_UPLOADED_DATABASE_DATA_ADDED');
            }catch (Exception $e){
                $return['error'] =
                    d::getMessage('FILE_SUCCESS_DATA_ERROR');
            }
        } else {
            /*
             * Сообщаем, что файл загружен,
             * но данные в БД не добавлены,
             * потому что файл Excel пуст
             */
            $return['error'] = d::getMessage('FILE_EXCEL_IS_EMPTY');
        }

        return $return;

    }

    /**
     * Страница "Импорт файлов Excel".
     * ===============================
     * Удаление файла
     */
    public function deleteExcelFile($data){
        $result = [];
        $file = FilesExcel::findOne($data['id']);

        // Если удаление строки файла успешно
        try{
            $file->delete();
            // Удаяем файл из директории
            if(!unlink(Yii::getAlias('@files_excel').'/'.$data['name'])){
                $result['error'] =
                    d::getMessage('DELETE_DATA_SUCCESS_FILE_ERROR');
            }else{
                /*
                 * Если запись о файле удалена
                 * и сам файл тоже удален
                 */
                $result['success'] = d::getMessage('FILE_EXCEL_DELETE_SUCCESS');
            }
        }catch(Exception $e){
            // Если есть ошибка удаления строки из таблицы
            $result['error'] = d::getMessage('DELETE_DB_EXCEL_FILE_ERROR');
        }
        return $result;
    }

    /**
     * Страница "Справочники"
     * Получаем данные из таблиц для справочника
     * т.е. получаем список значений справочника
     *
     * @return array
     */
    public function getListValue($data){

        $arr_c = explode('_',$data['table']);
        $class_name = '';
        foreach($arr_c as $str){
            $class_name .= ucfirst($str);
        }

        $class = '\app\models\\'.$class_name;

        $arr_value = $class::find()->orderBy('name')->all();

        return $arr_value;

    }

    /**
     * Страница "Справочники"
     * Кнопка "внести изменения"
     * Редактируем или вносим новые значения
     * в таблицу "Справочники"
     *
     * @return array
     */
    public function referenceEdit($data){

        $data = d::secureEncode($data);

        $arr_c = explode('_',$data['table']);
        $class_name = '';
        foreach($arr_c as $str){
            $class_name .= ucfirst($str);
        }

        $response = array();
        $response['status'] = d::getMessage('AJAX_STATUS_ERROR');

        $class = '\app\models\\'.$class_name;

        if ($data['type'] == 'new') {
            /*
             * Создаем новую запись
             */
            $model = new $class(); //создаём объект
            //теперь, будем писать данные в объект
            /*
             * Если это не бренд,пол,товарная группа,
             * то заполним значение алиасом @empty_data_field
             * чтобы пройти проверку валидации на пустоту
             */
            if(!in_array($data['table'], Yii::$app->params['requiredFields'])){
                $model->code = Yii::getAlias('@empty_data_field');
            }else $model->code = $data['code'];
            $model->name = $data['name'];

            // и по пробуем сохранить
            if($model->save()){
                $response['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $response['header'] = d::getMessage('HEADER_SUCCESS');
                $response['message'] = d::getMessage('NEW_ENTRY_ADDED');
                $response['options'] = $class::find()->orderBy('name')->all();
                $response['last_insert_id'] = $model->id;
            }else{
                $errors = $model->getErrors();
                // получаем сообщение об ошибке валидации
                $response['type_message'] = d::getMessage('TYPE_WARNING');
                $response['header'] = d::getMessage('HEADER_WARNING');
                /*
                 * Если создаем новую запись для бренд/пол/товарная группа
                 * то при пустом значении code нам нужно $errors['code']
                 * Иначе нам нужно $errors['name']
                 * Потому что у остальных списков поле code не активно
                 * и проверяется только поле name
                 */
//                if(in_array($data['table'], Yii::$app->params['requiredFields'])){
//                    $response['message'] = $errors['code'];
//                }else $response['message'] = $errors['name'];
                foreach($errors as $key=>$value)
                    $response['message'] = $value;

            }
        } else {
            /*
             * Обновляем запись
             */

            $model = $class::find()->where(['id' => $data['id']])->one();

            /*
             * Если это не бренд,пол,товарная группа,
             * то заполним значение алиасом @empty_data_field
             * чтобы пройти проверку валидации на пустоту
             */
            // было так, когда использовали code справочников
//            if(!in_array($data['table'], Yii::$app->params['requiredFields'])){
//                $model->code = Yii::getAlias('@empty_data_field');
//            }else{
//                $model->code = $data['code'];
//            }
            /*
             * стало так, теперь у справочников исползуем ID
             * а code используется только у бренд,пол,товарная группа
             */
            if(in_array($data['table'], Yii::$app->params['requiredFields'])){
                $model->code = $data['code'];
            }
            $model->name = $data['name'];
            if($model->save()){
                $response['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $response['header'] = d::getMessage('HEADER_SUCCESS');
                $response['message'] = d::getMessage('RECORDING_UPDATED');
                $response['options'] = $class::find()->orderBy('name')->all();
            }
            else{
                $errors = $model->getErrors();
                // получаем сообщение об ошибке валидации
                $response['type_message'] = d::getMessage('TYPE_WARNING');
                $response['header'] = d::getMessage('HEADER_WARNING');
                $response['message'] = d::getErrors($model,$errors);
            }
        }

        return $response;

    }

    /**
     * Страница "Номенклатура товара"
     * Выпадающий список "Выберите артикул"
     * Получаем данные из таблицы "product_nomenclature"
     * для заполнения полей страницы
     *
     * @return array
     */
    public function getNomenclature($data){
        /*
         * Из таблицы "product_nomenclature"
         * Выбираем все поля по паре бренд-артикул
         */
        $product_nomenclature = ProductNomenclature::find()
            ->where([
                'article_of_manufacture' => $data['vendor_code'],
                'brand_code' => $data['brand_code']
            ])
            ->orderBy('id')->one();

        // если что то выбралось, то возвращаем массив с данными
        if($product_nomenclature) return $product_nomenclature;
        else return false;
    }

    /**
     * Страница "Номенклатура товара"
     * Обработка главной формы.
     * Кнопка "Внести изменения"
     * Вносим данные в таблицу "product_nomenclature"
     *
     * @return array
     */
    public function Ptne($data){

        // Массив для возврата
        $response = [];

        $data = d::secureEncode($data);

        // создаем объект модели
        $product_nomenclature = new ProductNomenclature;

        /*
         * Тут можно создать объект модели ProductNomenclature
         * сразу для двух условий
         */

        // Если нужно сделать ЗАПИСЬ НОВОЙ номенклатуры
        if($data['type'] == 'new') {

            /*
             * Удаляем лишние элементы из массива
             * Этоти элементы нужены для обновления данных
             * при добавлении новой записи, этоти элементы не нужны
             */
            unset($data['type']);
            unset($data['reference_value']);

            /*
             * Создаем НОВУЮ запись
             */

            //теперь, будем писать данные в объекте
            foreach ($data as $key => $val) {
                $product_nomenclature->$key = $val;
            }

            // и по пробуем сохранить
            if ($product_nomenclature->save()) {
                $response['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $response['header'] = d::getMessage('HEADER_SUCCESS');
                $response['message'] = d::getMessage('SAVED');

                /*
                 * После обнолвения данных, нужно обновить список артикулов
                 * в выпадающем списке "Выберите автикул"
                 * ========================================================
                 * Получаем список артикулов по бренду
                 * и отдаем этот список в JS
                 */
                $pn = self::getArticlesByBrand($data['brand_code']);
                /*
                 * Если что то выбралось
                 * =====================
                 * arr_vc - array vendor code
                 * список артикулов по бренду
                 */
                if ($pn) $response['arr_vc'] = $pn;

            } else {
                // получаем сообщение об ошибке валидации
                $errors = $product_nomenclature->getErrors();
                $response['type_message'] = d::getMessage('TYPE_WARNING');
                $response['header'] = d::getMessage('HEADER_WARNING');
                $response['message'] = d::getErrors($product_nomenclature, $errors);
            }
        }else{

            // Если нужно РЕДАКТИРОВАТЬ существующую номенклатуру

            $validate_error = false;
            $validation = [];

            /*
             * Если поле "Выберите артикул"
             * и поле для редактирования артикула - не одинаковы,
             * это значит, что артикул редактировался.
             * Значит нужно проверить, существует ли новое значение артикула в БД
             * для этого нужно сделать
             * валидацию по полю "article_of_manufacture" (Артикул)
             * Если такой артикул в БД уже существует - выдаем ошибку.
             * Если не существует - обновляем данные.
             * article_of_manufacture - новое значение артикула
             * reference_value - старое значение артикула
             */
            if($data['article_of_manufacture'] != $data['reference_value']){

                // добавляем в массив элементы, которые нужно проверить валидатором
                $validation['brand_code'] = $data['brand_code'];
                $validation['article_of_manufacture'] = $data['article_of_manufacture'];
                $validation['commodity_group_code'] = $data['commodity_group_code'];
                $validation['code_sex'] = $data['code_sex'];

            }else{
                /*
                 * Добавляем в массив элементы, которые нужно проверить валидатором
                 * ================================================================
                 * Бренд и артикул при обновлении проверять не нужно
                 * потому что эти данные проверяются в JS
                 * Но чтобы валидатор не выдал ошибку,
                 * что в БД уже существуют "бренд" или "артикул"
                 * подсунем ему просто строки, лишь бы значения не были пустыми.
                 * В БД для обновления, используется массив $data
                 * а для валидации мы используем массив $validation
                 * поэтому для проверки можно подсунуть любые строки.
                 */
                $validation['brand_code'] = 'brand_code';
                $validation['article_of_manufacture'] = 'article_of_manufacture';

                /*
                 * А данные "Товарная группа" и "Пол"
                 * при обновлении, нужно проверить
                 */
                $validation['commodity_group_code'] = $data['commodity_group_code'];
                $validation['code_sex'] = $data['code_sex'];
            }

            /**
             * Валидация данных
             * ================
             * Перед обновлением данных в БД
             * проверим корректность данных через валидатор модели
             * ---------------------------------------------------
             * заполняем модель данными, которые нужно проверить
             */
            $product_nomenclature->load($validation, '');
            // аналогично следующей строке:
            // $model->attributes = \Yii::$app->request->post('ContactForm');

            // Если валидация выдала ошибку
            if(!$product_nomenclature->validate()) {

                $response['type_message'] = d::getMessage('TYPE_WARNING');
                $response['header'] = d::getMessage('HEADER_WARNING');
                // данные не корректны: $errors - массив содержащий сообщения об ошибках
                $response['message']  .= d::getErrors(
                    $product_nomenclature,
                    $product_nomenclature->errors
                );
                // флаг, говорит о том, что валидация выдала ошибку
                $validate_error = true;
            }

            // если валидация не выдала ошибку, делаем обновление данных
            if(!$validate_error){

                $where['brand_code'] = $data['brand_code'];
                $where['reference_value'] = $data['reference_value'];
                $response['arr_vc'] = false;

                /*
                 * Перед обновлением данных в БД
                 * удаляем лишние элементы из массива
                 */
                unset($data['type']);
                unset($data['reference_value']);
                unset($data['brand_code']);

                $update = Yii::$app->db->createCommand()
                    ->update('product_nomenclature',
                        $data,
                        [
                            'brand_code' => $where['brand_code'],
                            'article_of_manufacture' => $where['reference_value'],
                        ]);
                try {
                    $update->execute();
                    $response['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                    $response['header'] = d::getMessage('HEADER_SUCCESS');
                    $response['message'] = d::getMessage('EDIT_SUCCESS');

                    /*
                     * После обнолвения данных, нужно обновить список артикулов
                     * в выпадающем списке "Выберите автикул"
                     * ========================================================
                     * Получаем список артикулов по бренду
                     * и отдаем этот список в JS
                     */
                    $pn = self::getArticlesByBrand($where['brand_code']);
                    /*
                     * Если что то выбралось
                     * =====================
                     * arr_vc - array vendor code
                     * список артикулов по бренду
                     */
                    if ($pn) $response['arr_vc'] = $pn;

                } catch (Exception $e) {
//                    d::td($e->getMessage());
                    $response['type_message'] = d::getMessage('TYPE_WARNING');
                    $response['header'] = d::getMessage('HEADER_WARNING');
                    $response['message'] .= d::getMessage('EDIT_ERROR');
                }
            }// if(!$product_nomenclature->validate())
        }

        return $response;
    }

    /**
     * Страница "Отладка"
     */
    public function debug($data){

        if($data['type'] == 'reset-s2'){
            $update = Yii::$app->db->createCommand()
                ->update('certificates',
                    ['accrued'=>'1','cooked'=>'0','sold_out'=>'0'],
                    ['barcode'=>['0004567891011','0004567891012','0004567891013']]);
        }elseif($data['type'] == 'reset-s4'){
            $update = Yii::$app->db->createCommand()
                ->update('certificates',
                    ['accrued'=>'1','cooked'=>'0','sold_out'=>'1'],
                    ['barcode'=>['0004567891014','0004567891015','0004567891016']]);
        }else{
            $response['status'] = d::getMessage('AJAX_STATUS_ERROR');
            return;
        }

        try {
            $update->execute();
            $response['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $response['message'] = 'Таблица certificates сброшена';
        } catch (Exception $e) {
            $response['status'] = d::getMessage('AJAX_STATUS_ERROR');
            $response['message'] = 'Ошибка обновления таблицы certificates';
        }

        return $response;

    }

    /**
     * Страница "Поступление товара".
     * Подгружаем "наименование номенклатуры" и "товарную группу"
     *
     * @return array
     */
    public function productNomenclature($data){
        $product = array();

        // выбираем строку из "Номенклатура товара" по коду бренда и имени артикула
        $arr1 = ProductNomenclature::find()->where([
            'brand_code' => $data['brand_code'],
            'article_of_manufacture' => $data['article_of_manufacture'],
        ])->one();

        /**
         * Если по паре "наименование номенклатуры" и "товарнуя группа"
         * ничего НЕ НАЙДЕНО
         */
        if(count($arr1) == 0){
            $product['type_message'] = d::getMessage('TYPE_WARNING');
            $product['header'] = d::getMessage('HEADER_WARNING');
            $product['message'] = d::getMessage('NOMENCLATURE_NOT_FOUND');
        }else{
            /**
             * Если по паре "наименование номенклатуры" и "товарнуя группа"
             * информация НАЙДЕНА
             */
            // выбираем из "Товарной группы" строку по коду(code) товарной группы
            $arr2 = ProductGroup::find()->where([
                'code' => $arr1['commodity_group_code']
            ])->one();


            // Если нам нужен только штрихкод
            if(isset($data['get_barcode'])){
                // ГЕНЕРИРУЕМ ШТРИХКОД

                /*
                 * Строим строку для запроса LIKE
                 * ==============================
                 * В таблице Product ищем все штрихкоды, которые содержат в себе
                 * комбинацию кодов (товарная группа|пол|бренд)
                 */
                $like = $arr2['code'] . $arr1['code_sex'] . $arr1['brand_code'];
                $arr_barcodes = Product::find()->where(['like', 'barcode', $like])->all();

                // Если хоть один штрихкод найден
                if (count($arr_barcodes) > 0) {

                    // Инкрементим порядковый номер
                    $serial_number = Barcodes::arcadeNumberIncrement($arr_barcodes);

                    $ean = $like.$serial_number;

                    if ($serial_number <= 99999) {
                        $barcode = Barcodes::barcodeGenEanSum($ean);
                        $product['barcode'] = $barcode;
                        $product['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                        $product['header'] = d::getMessage('HEADER_SUCCESS');
                        $product['message'] = d::getMessage('BARCODE_GENERATED');
                    }else{
                        $product['type_message'] = d::getMessage('TYPE_WARNING');
                        $product['header'] = d::getMessage('HEADER_WARNING');
                        $product['message'] = d::getMessage('BARCODES_OVER');
                    }

                } else {
                    /*
                     * Если совпадений не найдено,
                     * делаем начальное значение порядкового номера
                     */

                    $ean = $like . '00001';

                    $barcode = Barcodes::barcodeGenEanSum($ean);
                    $product['barcode'] = $barcode;
                    $product['status'] = d::getMessage('AJAX_STATUS_SUCCESS');

                }
            }else{
                /*
                 * По изменению выпадающего списка артикулов.
                 * Если нам нужна информация только
                 * для "товарная группа" и "наименование номенклатуры"
                 */
                $product['nomenclature_name'] = $arr1['nomenclature_name'];
                $product['gender_code'] = $arr1['code_sex'];
                $product['product_group'] = $arr2['name'];
                $product['product_group_code'] = $arr2['code'];
                $product['item_code'] = $arr1['id'];

                $product['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
                $product['header'] = d::getMessage('HEADER_SUCCESS');
                $product['message'] = d::getMessage('DATA_UPLOADED');
            }
        }

        return $product;

    }

    /**
     * Страница "Поступление товара".
     * Кнопка "Добавить товары"
     *
     * @return array
     */
    public function sendGoods($data){

        $data = d::secureEncode($data);

        $result = array();
        $arr_document = array();
        $arr_goods_movement = array();
        $arr_product = array();
        $arr_todb_goods_movement = array();
        $arr_todb_product = array();
        $result['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
        $result['document_errors'] = '';
        $result['errors'] = '';
        $errors = false;
        $errors_document = false;
        $dogc = 0;

        // Создаем объекты моделей
        $document = new Document();
        $goods_movement = new GoodsMovement();
        $product = new Product();
        /**
         * Массив для таблицы "document" - документ
         * =================================
         */

//        print_r($data);
//        exit();

        $arr_document['vendor_code'] = $data['document']['vendor_code'];// код поставщика
        $arr_document['document_type'] = $data['document']['document_type'];// тип документа
        $arr_document['organization_code'] = '001';// код организации
        $arr_document['employee_code'] = Yii::$app->user->id;// ID работника
        // Документ контрагента, комментарий
        $arr_document['counterparty_document_comment'] =
            $data['document']['counterparty_document_comment'];

        // дата с сервера
        $arr_document['document_time'] = Yii::$app->getFormatter()->asTimestamp(time());
        $arr_document['document_date'] = Yii::$app->getFormatter()->asDate(time());

        /**
         *  Валидация данных
         */
        // заполняем модель пользовательскими данными
        $document->load($arr_document, '');
        // аналогично следующей строке:
        // $model->attributes = \Yii::$app->request->post('ContactForm');

        if (!$document->validate()) {
            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
            // данные не корректны: $errors - массив содержащий сообщения об ошибках
            $result['document_errors'] .= d::getErrors($document, $document->errors).'<br>';
            $errors_document = true;
        }

        if(count($data['goods']) > 0) {
            $i = 0;
            foreach ($data['goods'] as $el) {
                /*
                 * Обнуляем список ошибок
                 */
                $error_list = '';
                // задаем заголовок списку, с номером товара на странце
                $item_number = 'Товар: ' . $el['serial_number'] . '<br>';

                /**
                 * Массив для таблицы "product" - товар
                 * =================================
                 */
                $arr_product['barcode'] = $el['barcode'];
                $arr_product['item_code'] = $el['item_code'];
                $arr_product['code_manufacturer_size'] = ($el['code_manufacturer_size'] != '0') ? $el['code_manufacturer_size'] : '';
                $arr_product['code_size_russian'] = ($el['code_size_russian'] != '0') ? $el['code_size_russian'] : '';
                $arr_product['code_growth_russian'] = $el['code_growth_russian'];
                $arr_product['cost_of_goods'] = $el['cost_of_goods'];
                $arr_product['retail_price'] = $el['retail_price'];

//        print_r($arr_product);

                /**
                 *  Валидация данных
                 */
                // заполняем модель пользовательскими данными
                $product->load($arr_product, '');
                // аналогично следующей строке:
                // $model->attributes = \Yii::$app->request->post('ContactForm');

                if (!$product->validate()) {
                    $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
                    // данные не корректны: $errors - массив содержащий сообщения об ошибках
                    $error_list .= d::getErrors($product, $product->errors);
                    $errors = true;
                }

                /**
                 * Массив для таблицы "goods_movement" - движение товара
                 * =================================
                 */
                $arr_goods_movement['barcode'] = $el['barcode'];
                $arr_goods_movement['quantity'] = $el['quantity'];
                $arr_goods_movement['employee_code'] = Yii::$app->user->id;// ID работника;

                /**
                 *  Валидация данных
                 */
                // заполняем модель пользовательскими данными
                $goods_movement->load($arr_goods_movement, '');
                // аналогично следующей строке:
                // $model->attributes = \Yii::$app->request->post('ContactForm');

                if (!$goods_movement->validate()) {
                    $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
                    // данные не корректны: $errors - массив содержащий сообщения об ошибках
                    $error_list .= d::getErrors($goods_movement, $goods_movement->errors);
                    $errors = true;
                }

                /*
                 * Составляем строку ошибок только если есть ошибки
                 * Если одна строка товара заполнена корректно,
                 * то пропускаем сосавление строки ошибок
                 * А "заголовок: $item_number" - перезапишется в следующей итерации
                 * Чтобы не отображалось лишнее - "Товар 'X' и пустота"
                 */

                if ($error_list != ''){
                    $result['errors'] .= $item_number.$error_list.'<br>';
                }

                $arr_todb_product[$i] = $arr_product;
                $arr_todb_goods_movement[$i] = $arr_goods_movement;

                $i++;

            }// foreach $data[goods]

            /*
             * Если после всех проверок ни какая ошибка не произошла
             * и переменные ошибок остались в false
             * то делаем запись в БД
             * по всем таблицам
             */
            if (!$errors AND !$errors_document){

                /* ======================================
                 * Запись в таблицу "Документ" - document
                 */
                foreach($arr_document as $key=>$val){
                    $document->$key = $val;
                }

                /*
                 * Добавляем строку в таблицу "Документ"
                 */
                if(!$document->save()) $result['errors'] .= d::getMessage('WRITE_ERROR_IN_DOCUMENT');

                /*
                 * В массив данных, который будет добавляться в таблицу "Движение товара"
                 * к каждой строке добавляем ID последней записи таблицы "Документ"
                 * т.е. помечаем, к какому документу относятся строки таблицы
                 */
                for($j=0;$j<count($arr_todb_goods_movement);$j++){
                    $arr_todb_goods_movement[$j]['document_id'] = Yii::$app->db->getLastInsertID();
                }

                /* ===================================================
                 * Запись в таблицу "Движение товара" - goods_movement
                 */
                $goods_movement_command = Yii::$app->db->createCommand()->batchInsert(
                    'goods_movement',
                    ['barcode','quantity','employee_code','document_id'],
                    $arr_todb_goods_movement
                );

                try {
                    $goods_movement_command->execute();
                }catch (Exception $e){
                    $result['errors'] = d::getMessage('WRITE_ERROR_IN_GOODS_MOVEMENT');
                }

                /*
                 * Запись в таблицу "Товар" - product
                 */
                $product_command = Yii::$app->db->createCommand()->batchInsert(
                    'product',
                    ['barcode','item_code','code_manufacturer_size','code_size_russian','code_growth_russian','cost_of_goods','retail_price'],
                    $arr_todb_product
                );

                try {
                    $product_command->execute();
                }catch (Exception $e){
                    $result['errors'] = d::getMessage('WRITE_ERROR_IN_PRODUCT');
                }

                if($result['errors'] == ''){
                    $result['header'] = d::getMessage('HEADER_SUCCESS');
                    $result['message'] = d::getMessage('ITEMS_ADDED');
                }

            }

        }else{
            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
            if (!$errors_document) $result['errors'] = d::getMessage('PRODUCT_NOT_ADDED');
        }


        return $result;

    }

    /**
     * Страница "Товарный чек".
     * Ввод штрихкода
     * Получаем данные из БД по штрихкоду
     *
     * @return array
     */
    public function getInfoByBarcode($data){
        $result = array();// Результирующий массив
        /*
         * Если где то будет ошибка, то $result['status'] будет содержать статус ошибки
         * если же всё будет успешно - то $result['status'] получит новое значение: успех
         */
        $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
        $result['errors'] = '';
        $discount_cards = false;
        $certificates = false;
        $product = false;
        $goods_movement = false;
        /*
         * Из настроек сайта получаем переменные для number_format
         */
        $ko = Yii::getAlias('@ko');// kopecks
        $fl = Yii::getAlias('@fl');// float
        $th = Yii::getAlias('@th');// thousand

        /*
         * Ячейка таблицы "Количество"
         * ===========================
         * при плучении данных из БД - всегда происходит добавление ПЕРВОЙ строки в раздел 1 или 3
         * и количество товара должно быть в количестве 1
         * поэтому задаем количество ДО выполнения скриптов
         * чтобы использовать эту переменную в вычислениях во всех условиях
         * там, где эта переменная будет нужна
         * (изменение количества - будет меняться только в JS
         * поэтому тут "количество всегда будет 1)
         */
        $result['quantity'] = 1;

        /*
         * Выборками - определяем, какой штрихкод введен
         */
        $discount_cards = DiscountCards::find()
            ->where(['barcode' => $data['barcode']])
            ->one();

        $certificates = Certificates::find()
            ->where(['barcode' => $data['barcode']])
            ->one();

        $product = Product::find()
            ->where([
                'barcode' => $data['barcode'],
            ])
            ->one();

        /*
         * Если в БД что то нашлось по введеному штрихкоду
         */
        // если штрихкод - дисконтная карта
        if($discount_cards){
            $result['discount_cards'] = $discount_cards;
        }
        // если штрихкод - сертификат
        if($certificates){
            $result['certificates'] = $certificates;
        }
        // если штрихкод - товар
        if($product){
            $result['product'] = $product;
            $result['barcode'] = $product['barcode'];

            /*
             * Получаем информацию номенклатуры товара
             * по коду номенклатуры товара
             */
            $product_nomenclature = ProductNomenclature::findOne([
                'id'=>$product['item_code']
            ]);

            /*
             * Ячейка таблицы
             * "Наименование номенклатуры товара, цвет, рисунок/узор, размер производителя"
             * ============================================================================
             *
             * Получаем данные:
             * цвет, рисунок/узор, размер производителя
             */
            // цвет
            $color = Color::findOne(['id'=>$product_nomenclature['code_color']]);
            // рисунок/узор
            $design = Design::findOne(['id'=>$product_nomenclature['code_pattern']]);
            // размер производителя
            $size_manufacturer = SizeManufacturer::findOne([
                'id'=>$product['code_manufacturer_size']
            ]);

            $result['for_kkm'] =
                $product_nomenclature['nomenclature_name'].', '.
                (($size_manufacturer)?$size_manufacturer['name'].', ':'').
                $result['barcode'];

            $result['description'] =
                $product_nomenclature['nomenclature_name'].', '.
                (($color)?$color['name'].', ':'').
                (($design)?$design['name'].', ':'').
                (($size_manufacturer)?$size_manufacturer['name'].', ':'');

            /*
             * Если "код документа" - введен
             * значит это возврат
             * Добавление строки в "Раздел 3"
             */
            if ($data['document_id'] != ''){

                /*
                 * Пытаемся получить из таблицы (goods_movement) "движение товара"
                 * по штрихкоду(barcode) и коду документа(ID строки таблицы document)
                 */
                $goods_movement = GoodsMovement::find()
                    ->where([
                        'document_id' => $data['document_id'],
                        'barcode' => $data['barcode'],
                    ])
                    ->orderBy('id')
                    ->one();

                // Если что то выбралось
                if($goods_movement){

                    /*
                     * Если ID документа не пуст, то нужно работать только с теми товарами
                     * которые были проданы, т.е. у которых количество с минусом.
                     * Иначе выдаем сообщение, что не ничего не найдено.
                     */
                    if($goods_movement['quantity'] > 0)
                        $result['message'] = d::getMessage('MOV_ROW_NOT_FOUND');

                    /*
                     * У числа "количество" есть знак минуса
                     * Убираем знак минуса
                     */
                    $goods_movement['quantity'] = abs($goods_movement['quantity']);

                    /*
                     * Данные, которые нужны для вычислений в JS
                     * при увеличении количества
                     * =========================================
                     * Скрытое поле input
                     * для хранения общего количества
                     * чтобы при увеличении количества через JS, был ориентир
                     * для того чтобы на возврат не сделать количество
                     * больше того, что было продано
                     */
                    $result['total'] = $goods_movement['quantity'];
                    /*
                     * Общая "Ручная скидка" нужна пересчетов в JS
                     * при увеличении количества
                     */
                    $result['common_manual_discount'] = $goods_movement['manual_discount'];
                    /*
                     * Общая "Сумма продажи" нужна пересчетов в JS
                     * при увеличении количества
                     */
                    $result['common_sales_amount'] = $goods_movement['sale_amount'];

                    // /для JS =========================================

                    /*
                     * Ячейка таблицы "Код документа"
                     * ==============================
                     */
                    $result['document_id'] = $data['document_id'];

                    /*
                     * Ячейка таблицы "Надписи"
                     * ========================
                     * Получаем информацию из таблицы document
                     */
                    $document = Document::find()
                        ->where([
                            'id' => $data['document_id'],
                        ])
                        ->one();

                    // способ оплаты будет либо "наличные" либо "банковская карта"
                    $pmbc = ($document['payment_method_bank_card'] == '1')?
                        // способ оплаты "наличные"
                        d::getMessage('PAYMENT_METHOD_CASH'):
                        // способ оплаты "банковская карта"
                        d::getMessage('PAYMENT_METHOD_BANK_CARD');

                    // для атрибута data-payment-method (способ оплаты)
                    $result['payment_method_bank_card'] = $document['payment_method_bank_card'];

                    // Если документ имеет штрихкод дисконтной карты
                    if($document['discount_card'] != NULL){
                        $result['discount_card'] = $document['discount_card'];

                        $discount_cards = DiscountCards::find()
                            ->where([
                                'barcode' => $document['discount_card'],
                            ])
                            ->one();
                        // если из таблицы "Дисконтные карты" что то выбралось
                        if($discount_cards){
                            // для атрибута data-discount-card (возврат, обмен по карте)
                            $result['return_exchange_by_card'] =
                                number_format(
                                    $discount_cards['return_exchange_by_card'],
                                    $ko, $fl, $th
                                );
                        }else  $result['return_exchange_by_card'] = '';

                    }else $result['discount_card'] = '';

                    // для атрибута data-discount-card (штрихкод дисконтной карты)
                    $result['discount_card'] =
                        ($document['discount_card'] != NULL)?$document['discount_card']:'';

                    // получаем данные по ID пользователя, которое(ID) пришло из таблицы "Движение товара"
                    $gm_user = User::find()->where([
                        'id' => $goods_movement['employee_code']
                    ])->one();

                    // получаем ФИО пользователя для строки $result['document_info']
//                    if($gm_user) $gm_user_id = $gm_user['fio'].', ';
                    if($gm_user) $result['fio'] = $gm_user['fio'];

                    /*
                     * ID пользователя, который был выбран в выпадающем списке
                     * в соответствующе строке в разделе 1
                     */
                    $result['row_user_id'] = $gm_user['id'];

                    /*
                     * Надписи
                     * "дисконтная карта",
                     * "способ оплаты",
                     * "документ контрагента, комментарий",
                     * "ФИО, документ покупателя, коммнетрий"
                     */
                    $result['document_info'] =
                        (($document['discount_card'] != NULL)?$document['discount_card'].', ':'').
                        $pmbc.
//                        $gm_user_id.
                        (($document['counterparty_document_comment'] != '')?
                            ', '.$document['counterparty_document_comment']:'');
//                        (($document['name_buyers_document_comment'] != '')?$document['name_buyers_document_comment']:'');
                    $result['description'] .= $result['document_info'];

                    /*
                     * Ячейка таблицы "Розничная цена"
                     * ===============================
                     */
                    $result['retail_price'] = $goods_movement['retail_price_on_day_of_sale'];

                    /*
                     * Ячейка таблицы "Сумма без скидок"
                     * ===========================================
                     * "Текущая цена за шт" умножить на "Количество"
                     */
                    $result['amount_without_discounts'] = (
                        $result['retail_price'] * $result['quantity']
                    );

                    /*
                     * Ячейка таблицы "Скидка по дисконтной карте"
                     * ===========================================
                     */
                    $result['discount_on_a_discount_card'] = $goods_movement['discount_on_discount_card'];

                    /*
                     * Ячейка таблицы "Автоматическая скидка"
                     * ===========================================
                     */
                    $result['automatic_discount'] = $goods_movement['automatic_discount'];

                    /*
                     * Ячейка таблицы "Ручная скидка"
                     * ===========================================
                     * "Общая сумма ручных скидок" делим на "Общее количество". Получим скидку за 1ШТ
                     * и эту скидку умножаем на количество
                     */
                    $result['manual_discount'] = (
                        ($goods_movement['manual_discount'] / $goods_movement['quantity']) * $result['quantity']
                    );

                    /*
                     * Ячейка таблицы "Скидка по подарочным сертификатам"
                     * ==================================================
                     * "Общая скидка по подарочным сертификатам" делим на "Общее количество"
                     * взятое из таблицы БД - "Движение товара" = получаем скидка за 1ШТ
                     * и умножаем на "Количество" (здесь "Количество" всегда 1)
                     */
                    $result['discount_on_gift_certificates'] = (
                        ($goods_movement['discount_on_gift_certificates'] / $goods_movement['quantity']) * $result['quantity']
                    );

                    /*
                     * Ячейка таблицы "Сумма продажи"
                     * ==============================
                     * "Общая сумма продажи" делим на "Общее количество" = получим сумму продажи за 1ШТ
                     * и умножаем на "Количество" (здесь "Количество" всегда 1)
                     */
                    $result['sales_amount'] = (
                        ($goods_movement['sale_amount'] / $goods_movement['quantity']) * $result['quantity']
                    );

                    /*
                     * Ячейка таблицы "Итого скидки"
                     * =============================
                     * "Сумма без скидок" минус "Сумма продажи"
                     */
                    $result['total_discounts'] = (
                        (($result['amount_without_discounts'] - $result['sales_amount']) > 0)?
                            ($result['amount_without_discounts'] - $result['sales_amount']):0
                    );

                    /*
                     * Ячейка таблицы "Сумма скидок"
                     * =============================
                     * "Итого скидки" минус "Скидка по подарочным сертификатам"
                     */
                    $result['sum_of_discounts'] = (
                        (($result['total_discounts'] - $result['discount_on_gift_certificates']) > 0)?
                            ($result['total_discounts'] - $result['discount_on_gift_certificates']):0
                    );

                    /*
                     * Ячейка таблицы "Сумма за вычетом скидок"
                     * ========================================
                     * "Сумма без скидок" минус "Сумма скидок"
                     */
                    $result['amount_after_deduction_of_discounts'] = (
                    (($result['amount_without_discounts'] - $result['sum_of_discounts']) > 0)?
                        ($result['amount_without_discounts'] - $result['sum_of_discounts']):0
                    );

                }else{
                    // Если из таблицы "Движение товара" ничего не выбралось
                    $result['message'] = d::getMessage('MOV_ROW_NOT_FOUND');
                }
            }else{
                /*
                 * Если "код документа"не введен,
                 * значит это выбытие товара.
                 * Добавление строки в "Раздел 1"
                 */

                /*
                 * Получаем всех пользователей,
                 * которые активны
                 */
                $allUsers = User::find()
                    ->where(['active' => '1'])
                    ->orderBy('username')
                    ->all();

                /*
                 * Ячейка таблицы "список пользователей"
                 * =====================================
                 */
                // Берем только ID и ФИО Пользователей
                foreach($allUsers as $val){
                    $result['users'][$val->id]['id'] = $val->id;
                    $result['users'][$val->id]['fio'] = $val->fio;
                }

                /*
                 * Ячейка таблицы "Розничная цена"
                 * ===============================
                 */
                $result['retail_price'] = $product['retail_price'];

                /*
                 * Ячейка таблицы "Сумма без скидок"
                 * =================================
                 * "Текущую цену" умножить на "количетво"
                 */
                $result['amount_without_discounts'] = (
                    $product['retail_price'] * $result['quantity']
                );

                /*
                 * Ячейка таблицы "Скидка по дисконтной карте"
                 * ===========================================
                 * Эту скидку не надо проверять.
                 * Данные могут быть добавлены и без отображения данных по дисконтной карте
                 */
                $result['discount_on_a_discount_card'] =
                    ($data['current_discount_card'] != '')?$data['current_discount_card']:'0'
                ;

                /*
                 * Ячейка таблицы "Автоматическая скидка"
                 * ======================================
                 */
                $result['automatic_discount'] = $product['automatic_discount'];

                /*
                 * Ячейка таблицы "Ручная скидка"
                 * ===========================================
                 * значение 0, потому что при выбытии товара
                 * ручная скидка будет вводиться вручную
                 * а переменная "$result['manual_discount']"
                 * нужен для высчета значения - "Сумма скидок"
                 */
                $result['manual_discount'] = 0;

                /*
                 * Ячейка таблицы "Сумма скидок"
                 * =============================
                 *
                 * Вносим данные в массив,
                 * чтобы через max() выбрать наибольшее значение
                 */
                $two = array(
                    $result['discount_on_a_discount_card'],// скидка по дисконтной карте
                    $result['automatic_discount']// автоматическая скидка
                );

                /*
                 * (Сумма без скидок * (
                 *      большее число между двумя занчениями:
                 *          Скидка по дисконтной карте | Автоматическая скидка
                 *      )
                 * последовательно: далее делим всё это на 100
                 * и прибавляем "ручная скидка - 0") - это всё - результат вычисления
                 *
                 * при добавлении новой строки в раздел
                 * "ручная скидка" - всегда будет 0,
                 * далее из пары "Сумма без скидок" и "результат вычисления"
                 * берем наименьшее значение
                 */
                $result['sum_of_discounts'] = min(
                    $result['amount_without_discounts'],
                    (($result['amount_without_discounts'] * max($two)) / 100 + $result['manual_discount'])
                );

                /*
                 * Ячейка таблицы "Сумма за вычетом скидок"
                 * ========================================
                 * "Сумма без скидок" минус "Сумма скидок"
                 */
                $result['amount_after_deduction_of_discounts'] = (
                    (($result['amount_without_discounts'] - $result['sum_of_discounts']) > 0)?
                    ($result['amount_without_discounts'] - $result['sum_of_discounts']):0
                );

            }// else document_id == ''

            /*
             * number_format - форматируем цифры всех рузультатов только полсе всех вычислений,
             * т.е. - в самом конце перед отдачей резульата
             */
            $result['retail_price'] = number_format($result['retail_price'], $ko, $fl, $th);
            $result['manual_discount'] = number_format($result['manual_discount'], $ko, $fl, $th);
            $result['common_manual_discount'] = number_format($result['common_manual_discount'], $ko, $fl, $th);
            $result['amount_without_discounts'] = number_format($result['amount_without_discounts'], $ko, $fl, $th);
            $result['sum_of_discounts'] = number_format($result['sum_of_discounts'], $ko, $fl, $th);
            $result['amount_after_deduction_of_discounts'] = number_format($result['amount_after_deduction_of_discounts'], $ko, $fl, $th);
            $result['common_sales_amount'] = number_format($result['common_sales_amount'], $ko, $fl, $th);
            $result['sales_amount'] = number_format($result['sales_amount'], $ko, $fl, $th);
            $result['total_discounts'] = number_format($result['total_discounts'], $ko, $fl, $th);
            $result['discount_on_gift_certificates'] =
                number_format($result['discount_on_gift_certificates'], $ko, $fl, $th);
            /*
             * Общая "Скидка по подарочным сертификатам"
             * имеется ввиду по всему количеству.
             * Число нужное для вычислений в JS
             */
            $result['common_discount_on_gift_certificates'] =
                number_format($goods_movement['discount_on_gift_certificates'], $ko, $fl, $th);

        }

        /*
         * Если введеный штрихкод в БД не найден
         */
        if(!$discount_cards AND !$certificates AND !$product) $result = false;

        return $result;

    }// function getInfoByBarcode()

    /**
     * Страница "Товарный чек".
     * Кнопка "Сохранить"
     *
     * @return array
     */
    public function saveSalesReceipt($data){

        $data = d::secureEncode($data);

        $result = [];
        $arr_document = [];
        // массив для сборки строк разделов 1/3
        $arr_goods_movement = [];
        /*
         * Массив для сборки массивов $arr_goods_movement
         * в конце каждой итерации
         * собираем один общий массив для таблицы "goods_movement" (движение товара)
         */
        $arr_todb_goods_movement = [];
        // массив для добавления данных в таблицу discount_cards
        $arr_todb_discount_cards = [];
        // Массив для сборки штрихкодов раздела 2
        $arr_todb_certificates2 = [];
        // Массив для сборки штрихкодов раздела 4
        $arr_todb_certificates4 = [];
        $result['errors'] = '';
        $errors = false;

        // Создаем объекты моделей
        $document = new Document();
        $goods_movement = new GoodsMovement();
        /**
         * Массив для таблицы "document" - документ
         * =================================
         */
        foreach($data['info'] as $key=>$val){
            $arr_document[$key] = $val;
        }
        // ID авторизованного пользователя
        $arr_document['employee_code'] = Yii::$app->user->id;

        /*
         * Удаляем не нужные элементы массива
         * имена полей, которых нет в таблице.
         * Чтобы модель не выдавала ошибки
         * по не существующим полям таблицы "document"
         */
        unset($arr_document['document_id']);
        unset($arr_document['barcode']);

        // Берем дату с сервера
        $arr_document['document_time'] = Yii::$app->getFormatter()->asTimestamp(time());
        $arr_document['document_date'] = Yii::$app->getFormatter()->asDate(time());


        /*
         * В модели "Document" происходит проверка поля "Поставщик" на пустоту
         * эта проверка нужна для страницы "Поступление товара"
         * Но так как здесь нам это поле не нужно (но оно тоже тут проверяется)
         * и все ID в таблицах начинают свой отсчет с 1
         * то в это поле сделаем занчение - 0
         * чтобы это "поле" таблицы проходило валидацию в модели
         */
        $arr_document['vendor_code'] = '0';

        /**
         *  Валидация данных
         */
        // заполняем модель пользовательскими данными
        $document->load($arr_document, '');
        // аналогично следующей строке:
        // $model->attributes = \Yii::$app->request->post('ContactForm');

        /*
         * Если валидация данных модели "Document" не удачна
         */
        if (!$document->validate()) {
            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
            // данные не корректны: $errors - массив содержащий сообщения об ошибках
            $result['errors'] .= d::getErrors($document, $document->errors).'<br>';
        }else{
            /*
             * Если валидация данных в модели "Document" успешна
             * =================================================
             * заполняем модель данными
             */
            foreach($arr_document as $key=>$val) $document->$key = $val;

            /*
             * Если запись в таблицу "document" успешна
             * то перебираем массивы всех разделов
             */
            if($document->save()){
                /*
                 * Массив Раздела 1
                 * Собираем массив для записи в таблицу "goods_movement" (Движение товара)
                 */

                /*
                 * Перед циклом помещаем в переменную
                 * ID последней записи таблицы "document"
                 * ======================================
                 * в цикле, конструкция (Yii::$app->db->getLastInsertID())
                 * на второй и последующих итерациях,
                 * почему то выдает 0
                 */
                $last_insert_id_document = Yii::$app->db->getLastInsertID();

                // Если раздел 1 (section1) не пуст
                if(count($data['section1']) > 0) {
                    foreach ($data['section1'] as $key => $el) {
                        /*
                         * Обнуляем список ошибок
                         */
                        $error_list = '';
                        // задаем заголовок списку, с номером товара на странце
                        $item_number = 'Строка штрихкода: ' . $el['barcode'] . '<br>';

                        /**
                         * Массив для таблицы "goods_movement" - движение товара
                         * =================================
                         */
                        // Код документа. ID последней записи в таблицу document
                        $arr_goods_movement['document_id'] = $last_insert_id_document;
                        /*
                         * Строка документа
                         * Считаем количество строк в разделе 1
                         * принадлежащих одному документу
                         */
                        $arr_goods_movement['str_dock'] = $key;

                        /*
                         * Добавляем все элементы строки
                         * ключи массива - это имена полей таблицы
                         */
                        foreach ($el as $k => $item) {
                            if($k == 'quantity') $arr_goods_movement[$k] = (0 - $item);
                            else $arr_goods_movement[$k] = $item;
                        }

                        /**
                         *  Валидация данных
                         */
                        // заполняем модель пользовательскими данными
                        $goods_movement->load($arr_goods_movement, '');
                        // аналогично следующей строке:
                        // $model->attributes = \Yii::$app->request->post('ContactForm');

                        if (!$goods_movement->validate()) {
                            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
                            // данные не корректны: $errors - массив содержащий сообщения об ошибках
                            $error_list .= d::getErrors($goods_movement, $goods_movement->errors);
                            $errors = true;
                        }

                        /*
                         * Составляем строку ошибок только если есть ошибки
                         * Если одна строка товара заполнена корректно,
                         * то пропускаем сосавление строки ошибок
                         * А "заголовок: $item_number" - перезапишется в следующей итерации
                         * Чтобы не отображалось лишнее - "Товар 'X' и пустота"
                         */

                        if ($error_list != '') {
                            $result['errors'] .= $item_number . $error_list . '<br>';
                        }

                        $arr_todb_goods_movement[] = $arr_goods_movement;

                    }// foreach $data[section1]

                }// if(count($data['section1']) > 0)

                // обнуляем массив для последующего использования в коде
                $arr_goods_movement = [];

                // Если раздел 3 (section3) не пуст
                if(count($data['section3']) > 0) {
                    foreach ($data['section3'] as $key => $el) {
                        /*
                         * Обнуляем список ошибок
                         */
                        $error_list = '';
                        // задаем заголовок списку, с номером товара на странце
                        $item_number = 'Строка штрихкода: ' . $el['barcode'] . '<br>';

                        /**
                         * Массив для таблицы "goods_movement" - движение товара
                         * =================================
                         */
                        // Код документа. ID последней записи в таблицу document
                        $arr_goods_movement['document_id'] = $last_insert_id_document;
                        /*
                         * Строка документа
                         * Считаем количество строк в разделе 3
                         * принадлежащих одному документу
                         */
                        $arr_goods_movement['str_dock'] = $key;

                        /*
                         * Добавляем все элементы строки
                         * ключи массива - это имена полей таблицы
                         */
                        foreach ($el as $k => $item) {
                            /*
                             * При похождении строки "discount_on_gift_certificates"
                             * Добавляем в массив пустое значение поля "employee_code"
                             * чтобы массив был такой же как у раздела 1
                             * чтобы очередность полей совпадало
                             * для записи в таблицу через "batchInsert"
                             */
                            if($k == 'section'){
                                $arr_goods_movement[$k] = $item;
                                $arr_goods_movement['employee_code'] = '0';
                            }else $arr_goods_movement[$k] = $item;
                        }

                        /**
                         *  Валидация данных
                         */
                        // заполняем модель пользовательскими данными
                        $goods_movement->load($arr_goods_movement, '');
                        // аналогично следующей строке:
                        // $model->attributes = \Yii::$app->request->post('ContactForm');

                        if (!$goods_movement->validate()) {
                            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
                            // данные не корректны: $errors - массив содержащий сообщения об ошибках
                            $error_list .= d::getErrors($goods_movement, $goods_movement->errors);
                            $errors = true;
                        }

                        /*
                         * Составляем строку ошибок только если есть ошибки
                         * Если одна строка товара заполнена корректно,
                         * то пропускаем сосавление строки ошибок
                         * А "заголовок: $item_number" - перезапишется в следующей итерации
                         * Чтобы не отображалось лишнее - "Товар 'X' и пустота"
                         */

                        if ($error_list != '') {
                            $result['errors'] .= $item_number . $error_list . '<br>';
                        }

                        $arr_todb_goods_movement[] = $arr_goods_movement;

                    }// foreach $data[section1]

                }// if(count($data['section1']) > 0)

                // Если раздел 2 (section2) не пуст
                if(count($data['section2']) > 0) {
                    // $b - barcode
                    foreach ($data['section2'] as $b) {
                        $arr_todb_certificates2[] = $b['barcode'];
                    }// foreach $data[section2]

                }// if(count($data['section2']) > 0)

                // Если раздел 4 (section4) не пуст
                if(count($data['section4']) > 0) {
                    // $b - barcode
                    foreach ($data['section4'] as $b) {
                        $arr_todb_certificates4[] = $b['barcode'];
                    }// foreach $data[section4]

                }// if(count($data['section4']) > 0)

                // Если раздел 4 (section4) не пуст
                if(count($data['s3dc']) > 0) {

                    $where_dc = '';

                    $query_dc = "UPDATE `discount_cards` SET ";

                    foreach($data['s3dc'] as $key=>$value){
                        // собираем SQL строку
                        $query_dc .= "`return_exchange_by_card`= CASE
                        WHEN `barcode`='{$key}' THEN '{$value}'
                        ELSE `return_exchange_by_card` END, ";
                        // собираем строку для WHERE IN
                        $where_dc .= "'".$key."',";
                    }

                    // убираем с конца строки лишние символы
                    $query_dc = substr($query_dc, 0, -2);
                    $where_dc = substr($where_dc, 0, -1);

                    // дополняем SQL строку WHERE IN
                    $query_dc .= " WHERE `barcode` IN (".$where_dc.')';

                }// if(count($data['s3dc']) > 0)

                /*
                 * Если после всех проверок ни какая ошибка не произошла
                 * и переменные ошибок остались в false
                 * то делаем запись в БД
                 * по всем таблицам
                 */
                if (!$errors AND $result['errors'] == '') {

                    /*
                     * РАЗДЕЛЫ 1/3
                     * Запись в таблицу "Движение товара" - goods_movement
                     * ===================================================
                     * Если массив для записи в таблицу $arr_todb_goods_movement
                     * "Движение товара" не пуст
                     */
                    if(count($arr_todb_goods_movement) > 0) {
                        $goods_movement_command = Yii::$app->db->createCommand()->batchInsert(
                            'goods_movement',
                            [
                                'document_id',
                                'str_dock',
                                'section',
                                'employee_code',
                                'barcode',
                                'retail_price_on_day_of_sale',
                                'quantity',
                                'discount_on_discount_card',
                                'automatic_discount',
                                'manual_discount',
                                'discount_on_gift_certificates',
                                'sale_amount',
                            ],
                            $arr_todb_goods_movement
                        );


                        try {
                            $goods_movement_command->execute();
                        } catch (Exception $e) {
                            $result['errors'] .= d::getMessage('WRITE_ERROR_IN_GOODS_MOVEMENT');
                        }

                    }// if(count($arr_todb_goods_movement) > 0)

                    // РАЗДЕЛЫ 2/4
                    // ===================================

                    /*
                     * Если массив $arr_todb_certificates2
                     * для записи в таблицу certificates
                     * "Сертификаты" не пуст
                     * ===================================
                     * поле "sold_out" 0 изменяем на 1
                     * значит (продан)
                     */
                    if(count($arr_todb_certificates2) > 0){
                        $update = Yii::$app->db->createCommand()
                            ->update('certificates', [
                                'sold_out' => '1',
                                'date_of_sale' => Yii::$app->getFormatter()->asDate(time()),
                                'document_id_sale' => $last_insert_id_document
                            ], ['barcode' => $arr_todb_certificates2]);
                        try {
                            $update->execute();
                        }catch (Exception $e){
                            $result['errors'] .= d::getMessage('SECTION_2_ERROR');
                        }
                    }

                    /*
                     * Если массив $arr_todb_certificates4
                     * для записи в таблицу certificates
                     * "Сертификаты" не пуст
                     * ===================================
                     * поле "cooked" 0 изменяем на 1
                     * значит (отоварен)
                     */
                    if(count($arr_todb_certificates4) > 0){
                        $update = Yii::$app->db->createCommand()
                            ->update('certificates', [
                                'cooked' => '1',
                                'date_of_digestion' => Yii::$app->getFormatter()->asDate(time()),
                                'document_id_digestion' => $last_insert_id_document
                            ], ['barcode' => $arr_todb_certificates4]);
                        try {
                            $update->execute();
                        }catch (Exception $e){
                            $result['errors'] .= d::getMessage('SECTION_4_ERROR');
                        }
                    }

                    /*
                     * Если массив $data['s3dc']
                     * для записи в таблицу discount_cards
                     * "Дисконтные карты" не пуст
                     */
                    if(count($data['s3dc']) > 0){

                        $update_dc = Yii::$app->db->createCommand($query_dc);
                        try {
                            $update_dc->execute();
                        }catch (Exception $e){
                            $result['errors'] .= d::getMessage('S3DC_ERROR');
                        }
                    }

                    /*
                     * Если массив $data['discount_card']
                     * для записи в таблицу discount_cards
                     * "Дисконтные карты" не пуст
                     */
                    if(count($data['discount_card']) > 0){
                        $dc = DiscountCards::findOne([
                            'barcode' => $data['discount_card']['barcode']
                        ]);

                        // убираем из массива штрихкод, нам его переписывать не нужно
                        unset($data['discount_card']['barcode']);

                        foreach($data['discount_card'] as $key=>$val){
                            $dc->$key = $val;
                        }

                        if ($dc->update() === false) {
                            $result['errors'] .= d::getMessage('DC_ERROR');
                        }
                    }

                }// if (!$errors AND $result['errors'] == '')


            // if($document->save())
            }else{
                /*
                 * Если запись в таблицу "document" (Документ) не удачна
                 * то, в переменную $result[errors] добавляем пояснения ошибки
                 */
                $result['errors'] .= d::getMessage('WRITE_ERROR_IN_DOCUMENT');
            }

        }// else ($document->validate())

        if($result['errors'] == ''){
            $result['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $result['header'] = d::getMessage('HEADER_SUCCESS');
            $result['message'] = d::getMessage('CHECK_IS_SAVED');
        }

        return $result;

    }// function actionSaveSalesReceipt()

    /**
     * Страница "Кассовый отчет".
     * Кнопка "Вывести отчет"
     *
     * @return array
     */
    public function cashReport($data){

        $result = [];

        /*
         * Из таблицы "document" получаем все строки,
         * где дата совпадает с запрошеной датой
         */
        $document = Document::find()
            ->where([
                'document_date' => $data['date_report'],
                'document_type' => DocumentType::getOne(['code'=>'03'])
            ])
            ->orderBy('id')
            ->all();

        // если что то нашлось в "document"
        if($document) $result['document'] = $document;
        else $result['document'] = false;

        /*
         * Из таблицы "certificates"
         * Выбираем поля "номинал сертификата" по запрошеной дате
         */
        $certificates = Certificates::find()
            ->where(['date_of_digestion' => $data['date_report']])
            ->orderBy('id')->all();

        // если что то нашлось в "certificates"
        if($certificates) $result['certificates'] = $certificates;
        else $result['certificates'] = false;

        return $result;

    }// function actionSaveSalesReceipt()

    /**
     * Страница "Работники".
     * Выпадающий спискок "Выберите работника"
     * =======================================
     * Получаем информацию о работнике из таблицы user
     *
     * @return array
     */
    public static function getUser($post){

        $data = d::secureEncode($post);

        $result = [];

        /*
         * Из таблицы "user" получаем все поля,
         * по ID пользователя
         */
        $user = User::find()
            ->where([ 'id' => $data['id']])->one();

        // если что то нашлось в "user"
        if($user) return $user;
        else false;



    }// function getUser()

    /**
     * Страница "Оприходование товара".
     * Страница "Выгрузка этикеток"
     * Страница "Товарный учет" - поле "Введите штрикод"
     * Поле ввода штрихкода
     * ================================
     * По штрихкоду получаем информацию
     * из таблицы "product"
     */
    public static function getProductByBarcode($data){

        $result = [];
        $product = Product::findOne(['barcode'=>$data['barcode']]);
        if(!$product) return false;
        else{

            // Если страница "Товарный учет"
            if($data['page'] == 'commodity-accounting'){
                $document_id = GoodsMovement::find()
                    ->where(['barcode'=>$data['barcode']])
                    ->min('document_id');

                /*
                 * Далее из таблицы "document"
                 * получаем строку по "document_id"
                 * полученном из таблицы движение товара
                 */
                $d = Document::find()
                    ->where(['id'=>$document_id])
                    ->asArray()->one();
                if($d){// ID документа
                    $result['document_id'] = $d['id'];
                    // Дата документа
                    $result['document_date'] = $d['document_date'];
                    // Код поставщика
                    $result['provider'] = $d['vendor_code'];
                }else{
                    $result['errors'] = d::getMessage('DOCUMENT_ID_ERROR');
                }

            }

            /*
             * Получаем информацию из таблицы "Номенклатура товара"
             * по коду номенклатуры (по ID номенклатуры)
             */
            $nomenclature =
                ($nre = ProductNomenclature::findOne(['id'=>$product['item_code']]))?$nre:'';
            if($nomenclature) {
                // Наименование номенклатуры
                $result['nomenclature_name'] = $nomenclature['nomenclature_name'];
                // Получаем "цвет" по ID
                $result['color'] = ($cor = Color::findOne(['id' => $nomenclature['code_color']])) ? $cor['name'] : '';
                // Получаем "рисунок/узор" по ID
                $result['design'] = ($dgn = Design::findOne(['id' => $nomenclature['code_pattern']])) ? $dgn['name'] : '';
            }
            // Получаем "Размер производителя" по ID
            $result['manufacturer_size'] = ($sm = SizeManufacturer::findOne(['id'=>$product['code_manufacturer_size']]))?$sm['name']:'';
            // Себестоимость
            $result['cost_price'] = $product['cost_of_goods'];
            // Розничная цена
            $result['retail_price'] = $product['retail_price'];
            // Цена по акции
            $result['action_price'] = $product['action_price'];
            // Автоматическая скидка
            $result['automatic_discount'] = $product['automatic_discount'];
            // Надпись на этикетке
            $result['labeling'] = $nomenclature['labeling'];

            /*
             * Дата поступления товара
             * =======================
             * пока что оставим этот момент на потом
             */
            $result['receipt_date'] = '';
        }

        /*
         * Если штрихкод запрашивается
         * со страницы "Товарный учет"
         * Указываем номер блока таблицы
         */
        if($data['number_section']){
            // Номер блока section1
            $result['number_section'] = $data['number_section'];
        }

        $result['barcode'] = $data['barcode'];

        return $result;
    }

    /**
     * Страница "Товарный учет"
     * Кнопка "Сохранить документ"
     * ===========================
     * Сохраняем данные в БД
     */
    public static function saveCommodityAccounting($data){
        // Создание нового документа
        if($data['action_type'] == 'new'){
            return self::debitProductCA($data);
        }
        // Корректировка документа
        if($data['action_type'] == 'document_correction'){
            return self::saveEditCA($data);
        }
        // Помечаем документ пустым
        if($data['action_type'] == 'disabled_document_id'){
            return self::disabledDocumentId($data);
        }
    }

    /**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Сохранить документ"
     * ---------------------------
     * Сохраняем новые данные в БД
     */
    public static function debitProductCA($data){

        $data = d::secureEncode($data);

        $result = [];
        // Массив для сборки информации для таблицы "document"
        $arr_document = [];
        // массив для сборки одной итерации товара по штрихкоду
        $arr_goods_movement = [];
        /*
         * Массив для сборки массивов $arr_goods_movement
         * в конце каждой итерации
         * собираем один общий массив для таблицы "goods_movement" (движение товара)
         */
        $arr_todb_goods_movement = [];
        $dock_attrs = [];// Для атрибутов модели Document
        /*
         * Для строк перед добавлением в таблицу движение товара.
         * Будут храниться сортированные по ключам массивы
         */
        $arr_todb_goods_movement_ksort = [];
        $result['errors'] = '';
        $errors = false;

        // Создаем объекты моделей
        $document = new Document();
        $goods_movement = new GoodsMovement();
        /**
         * Массив для таблицы "document" - документ
         * ========================================
         */
        foreach($data['info'] as $key=>$val){
            $arr_document[$key] = $val;
        }

        // ID авторизованного пользователя
        $arr_document['employee_code'] = Yii::$app->user->id;

        // Берем дату с сервера
        $arr_document['document_date'] = Yii::$app->getFormatter()->asDate(time());
        $arr_document['document_time'] = Yii::$app->getFormatter()->asTimestamp(time());

        /*
         * Если есть код поставщика
         * то добавляем в массив $arr_document
         */
        if($data['info']['vendor_code']) {
            $arr_document['vendor_code'] = $data['info']['vendor_code'];
        }else{
            /*
             * В модели "Document" происходит проверка поля "Поставщик" на пустоту
             * эта проверка нужна для страницы "Поступление товара"
             * Но так как здесь нам это поле не нужно (но оно тоже тут проверяется)
             * и все ID в таблицах начинают свой отсчет с 1
             * то в это поле сделаем занчение - 0
             * чтобы это "поле" таблицы проходило валидацию в модели
             */
            $arr_document['vendor_code'] = '0';
        }

        /**
         * Валидация данных
         */

        // заполняем модель пользовательскими данными
        $document->load($arr_document, '');
        // аналогично следующей строке:
        // $model->attributes = \Yii::$app->request->post('ContactForm');

        /*
         * Если валидация данных модели "Document" не удачна
         */

        // Соберем все атрибуты в массив
        foreach($document as $key=>$attr) $dock_attrs[] = $key;
        /*
         * Пройдемся по всему массиву
         * и проверим ключи.
         * Если в массиве $dock_attrs
         * хоть какого то ключа нет
         * то из массива $arr_document удаляем элемент
         * с таким ключом
         * Это делается для того, чтобы при загрузке данных в модель
         * не попался ключ, которого нет в атрибутах модели.
         */
        foreach($arr_document as $key=>$attr){
            if(!in_array($key,$dock_attrs)) unset($arr_document[$key]);
        }

        if (!$document->validate()) {
            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
            // данные не корректны: $errors - массив содержащий сообщения об ошибках
            $result['errors'] .= d::getErrors($document, $document->errors).'<br>';
        }else{
            /*
             * Если валидация данных в модели "Document" успешна
             * =================================================
             * заполняем модель данными
             */
            foreach($arr_document as $key=>$val) $document->$key = $val;

            /*
             * Если запись в таблицу "document" успешна
             * то перебираем массивы всех разделов
             */
            if($document->save()){
                /*
                 * Массив Раздела 1
                 * Собираем массив для записи
                 * в таблицу "goods_movement" (Движение товара)
                 */

                /*
                 * Перед циклом помещаем в переменную
                 * ID последней записи таблицы "document"
                 * ======================================
                 * Если это делать в цикле,
                 * то конструкция (Yii::$app->db->getLastInsertID())
                 * на второй и последующих итерациях,
                 * почему то выдает 0
                 */
                $last_insert_id_document = Yii::$app->db->getLastInsertID();
                // По этому ID получим строки для вывода на экран
                $result['document_id'] = $last_insert_id_document;

                // Если таблица .table не пуста
                if(count($data['table']) > 0) {
                    foreach ($data['table'] as $key => $el) {
                        // Обнуляем список ошибок
                        $error_list = '';
                        // задаем заголовок списку, с номером товара на странце
                        $item_number = 'Строка штрихкода: ' . $el['barcode'] . '<br>';

                        /**
                         * Массив для таблицы "goods_movement" - движение товара
                         * =====================================================
                         */

                        /*
                         * Добавляем все элементы строки (выбранные ячейки)
                         * ключи массива - это имена полей таблицы
                         */
                        foreach ($el as $k => $item) {
                            $arr_goods_movement[$k] = $item;
                        }

                        // Код документа. ID последней записи в таблицу document
                        $arr_goods_movement['document_id'] = $last_insert_id_document;
                        /*
                         * Строка документа
                         * Считаем количество строк в таблице .table
                         * принадлежащих одному документу
                         */
                        $arr_goods_movement['str_dock'] = $el['str_dock'];

                        // ID авторизованного пользователя
                        $arr_goods_movement['employee_code'] = Yii::$app->user->id;

                        /*
                         * Количество
                         * "плюс" если тип "Оприходование"
                         * "минус" если остальноые типы
                         */
                        $arr_goods_movement['quantity'] =
                            ($data['info']['document_type'] != '04')?
                                (0 - $el['quantity']):$el['quantity'];

                        /**
                         *  Валидация данных
                         */
                        // заполняем модель пользовательскими данными
                        $goods_movement->load($arr_goods_movement, '');
                        // аналогично следующей строке:
                        // $model->attributes = \Yii::$app->request->post('ContactForm');

                        if (!$goods_movement->validate()) {
                            // данные не корректны: $errors - массив содержащий сообщения об ошибках
                            $error_list .= d::getErrors($goods_movement, $goods_movement->errors);
                            $errors = true;
                        }

                        /*
                         * Составляем строку ошибок только если есть ошибки
                         * Если одна строка товара заполнена корректно,
                         * то пропускаем составление строки ошибок
                         * А "заголовок: $item_number" - перезапишется в следующей итерации
                         * Чтобы не отображалось лишнее - "Товар 'X' и пустота"
                         */

                        if ($error_list != '') {
                            $result['errors'] .= $item_number . $error_list . '<br>';
                        }

                        $arr_todb_goods_movement[] = $arr_goods_movement;
                        $arr_goods_movement = [];

                    }// foreach $data[section1]

                }// if(count($data['table']) > 0)

                /*
                 * Если после всех проверок ни какая ошибка не произошла
                 * и переменные ошибок остались в false
                 * то делаем запись в БД
                 * по всем таблицам
                 */
                if (!$errors AND $result['errors'] == '') {
                    /*
                     * Таблица .table
                     * Запись в таблицу "Движение товара" - goods_movement
                     * ===================================================
                     * Если массив для записи в таблицу $arr_todb_goods_movement
                     * "Движение товара" не пуст
                     */
                    if(count($arr_todb_goods_movement) > 0) {
                        /*
                         * Сортируем по ключам
                         * ===================
                         * Потому что при сборке массивов,
                         * происходит разный порядок добавления.
                         * Нужно упорядочить, для того чтобы
                         * был такой же порядок, как у массива полей таблицы
                         */
                        foreach($arr_todb_goods_movement as $key=>$item) {
                            ksort($item);
                            $arr_todb_goods_movement_ksort[] = $item;
                        }

                        $goods_movement_command =
                            Yii::$app->db->createCommand()->batchInsert(
                            'goods_movement',
                            [
                                /*
                                 * Поочередность выставлена
                                 * по пришедшему массиву
                                 */
                                'barcode',
                                'document_id',
                                'employee_code',
                                'quantity',
                                'str_dock',
                            ],
                            $arr_todb_goods_movement_ksort
                        );

                        try {
                            $goods_movement_command->execute();
                            $result['last_insert_id_document'] =
                                $last_insert_id_document;
                        } catch (Exception $e) {
                            $result['errors'] .=
                                d::getMessage('WRITE_ERROR_IN_GOODS_MOVEMENT');
                        }

                    }// if(count($arr_todb_goods_movement) > 0)

                }// if (!$errors AND $result['errors'] == '')

            }else{
                /*
                 * Если запись в таблицу "document" (Документ) не удачна
                 * то, в переменную $result[errors] добавляем пояснения ошибки
                 */
                $result['errors'] .= d::getMessage('WRITE_ERROR_IN_DOCUMENT');
            }

        }// else ($document->validate())

        return $result;

    }// function debitProduct()

    /**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Сохранить документ"
     * ---------------------------------------------
     * Функция "Сохранение коррекционного документа"
     * Сохраняем данные в БД
     */
    public static function saveEditCA($data){

        $result = [];// Массив для возвращения
        $arr_todb_document = [];
        $data = d::secureEncode($data);
        $i=0;

        // Расставим данные в нужном порядке
        $arr_todb_document[0]['document_correction_code'] = $data['info']['document_id'];
//        $arr_todb_document[0]['document_type'] = $data['info']['document_type'];
        $arr_todb_document[0]['document_date'] = Yii::$app->getFormatter()->asDate(time());
        $arr_todb_document[0]['document_time'] = Yii::$app->getFormatter()->asTimestamp(time());
        $arr_todb_document[0]['counterparty_document_comment'] =
            $data['info']['counterparty_document_comment'];
        $arr_todb_document[0]['employee_code'] = Yii::$app->user->id;
        /*
         * Запись в таблицу "Документ" - document
         */
        $document_command = Yii::$app->db->createCommand()->batchInsert(
            'document',[
                'document_correction_code',
//                'document_type',
                'document_date',
                'document_time',
                'counterparty_document_comment',
                'employee_code',
            ],
            $arr_todb_document
        );

        try {
            $document_command->execute();
            /*
             * Сразу после операции,
             * ID последней записи сохраняем в переменную
             */
            $last_insert_id = Yii::$app->db->getLastInsertID();

            /*
             * Если было списание документа на ноль
             * то помечаем оригинал документа
             * в поле document_correction_code ставим 0
             */
            if($data['new_document_type']){
                $document = Document::findOne($data['info']['document_id']);
                $document->document_correction_code = '0';
                $document->update();
            }

            $i=0;
            $rows_tb = [];

            $result['document_id'] = $data['info']['document_id'];
            $user_id = Yii::$app->user->id;

            // Расставим данные в нужном порядке
            foreach($data['table'] as $row){
                $rows_tb[$i]['document_id'] = $last_insert_id;
                $rows_tb[$i]['str_dock'] = $row['str_dock'];
                $rows_tb[$i]['employee_code'] = $user_id;
                $rows_tb[$i]['barcode'] = $row['barcode'];

                /*
                 * Знак количества
                 * "плюс" если тип "Оприходование"
                 * "минус" если остальноые типы
                 */
                $rows_tb[$i]['quantity'] =
                    ($data['info']['document_type'] == '04')?
                        $row['quantity']:(0 - $row['quantity']);

                $i++;
            }

            $fields = ['document_id','str_dock','employee_code','barcode','quantity'];
            $bbm_command = Yii::$app->db->createCommand()->batchInsert(
                'goods_movement', $fields, $rows_tb
            );

            try {
                $bbm_command->execute();
            }catch (Exception $e){
//                $result['errors'] = d::getMessage('WRITE_ERROR_IN_GOODS_MOVEMENT');
                $result['errors'] = $e->getMessage();
            }

        }catch (Exception $e){
//            $result['errors'] = d::getMessage('WRITE_ERROR_IN_DOCUMENT');
            $result['errors'] = $e->getMessage();
        }

        return $result;

    }// function saveEditCA(...)

    public static function disabledDocumentId($data){
        $return = [];
        $document = Document::findOne($data['document_id']);
        $document->document_correction_code = '0';
        if($document->update()) $return['errors'] = false;
        else $return['errors'] = d::getMessage('DOCUMENT_TO_ZERO_ERROR');

        return $return;
    }

    /**
     * Страница "Оприходование товара".
     * Кнопка "Оприходование"
     * ================================
     * Сохраняем данные в БД
     */
    public static function debitProduct($data){

        $result = [];
        // Массив для сборки информации для таблицы "document"
        $arr_document = [];
        // массив для сборки одной итерации товара по штрихкоду
        $arr_goods_movement = [];
        /*
         * Массив для сборки массивов $arr_goods_movement
         * в конце каждой итерации
         * собираем один общий массив для таблицы "goods_movement" (движение товара)
         */
        $arr_todb_goods_movement = [];
        $result['errors'] = '';
        $errors = false;

        // Создаем объекты моделей
        $document = new Document();
        $goods_movement = new GoodsMovement();
        /**
         * Массив для таблицы "document" - документ
         * =================================
         */
        foreach($data['info'] as $key=>$val){
            $arr_document[$key] = $val;
        }
        // ID авторизованного пользователя
        $arr_document['employee_code'] = Yii::$app->user->id;

        // Берем дату с сервера
        $arr_document['document_date'] = Yii::$app->getFormatter()->asDate(time());
        $arr_document['document_time'] = Yii::$app->getFormatter()->asTimestamp(time());

        /*
         * В модели "Document" происходит проверка поля "Поставщик" на пустоту
         * эта проверка нужна для страницы "Поступление товара"
         * Но так как здесь нам это поле не нужно (но оно тоже тут проверяется)
         * и все ID в таблицах начинают свой отсчет с 1
         * то в это поле сделаем занчение - 0
         * чтобы это "поле" таблицы проходило валидацию в модели
         */
        $arr_document['vendor_code'] = '0';

        /**
         *  Валидация данных
         */
        // заполняем модель пользовательскими данными
        $document->load($arr_document, '');
        // аналогично следующей строке:
        // $model->attributes = \Yii::$app->request->post('ContactForm');

        /*
         * Если валидация данных модели "Document" не удачна
         */
        if (!$document->validate()) {
            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
            // данные не корректны: $errors - массив содержащий сообщения об ошибках
            $result['errors'] .= d::getErrors($document, $document->errors).'<br>';
        }else{
            /*
             * Если валидация данных в модели "Document" успешна
             * =================================================
             * заполняем модель данными
             */
            foreach($arr_document as $key=>$val) $document->$key = $val;

            /*
             * Если запись в таблицу "document" успешна
             * то перебираем массивы всех разделов
             */
            if($document->save()){
                /*
                 * Массив Раздела 1
                 * Собираем массив для записи в таблицу "goods_movement" (Движение товара)
                 */

                /*
                 * Перед циклом помещаем в переменную
                 * ID последней записи таблицы "document"
                 * ======================================
                 * Если это делать в цикле,
                 * то конструкция (Yii::$app->db->getLastInsertID())
                 * на второй и последующих итерациях,
                 * почему то выдает 0
                 */
                $last_insert_id_document = Yii::$app->db->getLastInsertID();

                /*
                 * В JS, в первый элемент, почему то пишется пустота
                 * поэтому удаляем первый элемент из массива
                 */
                unset($data['table'][0]);

                // Если таблица .table2 не пуста
                if(count($data['table']) > 0) {
                    foreach ($data['table'] as $key => $el) {
                        /*
                         * Обнуляем список ошибок
                         */
                        $error_list = '';
                        // задаем заголовок списку, с номером товара на странце
                        $item_number = 'Строка штрихкода: ' . $el['barcode'] . '<br>';

                        /**
                         * Массив для таблицы "goods_movement" - движение товара
                         * =================================
                         */

                        // Код документа. ID последней записи в таблицу document
                        $arr_goods_movement['document_id'] = $last_insert_id_document;
                        /*
                         * Строка документа
                         * Считаем количество строк в таблице .table2
                         * принадлежащих одному документу
                         */
                        $arr_goods_movement['str_dock'] = $key;

                        // ID авторизованного пользователя
                        $arr_goods_movement['employee_code'] = 0;

                        /*
                         * Добавляем все элементы строки (выбранные ячейки)
                         * ключи массива - это имена полей таблицы
                         */
                        foreach ($el as $k => $item) {
                            $arr_goods_movement[$k] = $item;
                        }

                        /**
                         *  Валидация данных
                         */
                        // заполняем модель пользовательскими данными
                        $goods_movement->load($arr_goods_movement, '');
                        // аналогично следующей строке:
                        // $model->attributes = \Yii::$app->request->post('ContactForm');

                        if (!$goods_movement->validate()) {
                            $result['status'] = d::getMessage('AJAX_STATUS_ERROR');
                            // данные не корректны: $errors - массив содержащий сообщения об ошибках
                            $error_list .= d::getErrors($goods_movement, $goods_movement->errors);
                            $errors = true;
                        }

                        /*
                         * Составляем строку ошибок только если есть ошибки
                         * Если одна строка товара заполнена корректно,
                         * то пропускаем сосавление строки ошибок
                         * А "заголовок: $item_number" - перезапишется в следующей итерации
                         * Чтобы не отображалось лишнее - "Товар 'X' и пустота"
                         */

                        if ($error_list != '') {
                            $result['errors'] .= $item_number . $error_list . '<br>';
                        }

                        $arr_todb_goods_movement[] = $arr_goods_movement;
                        $arr_goods_movement = [];

                    }// foreach $data[section1]

                }// if(count($data['table']) > 0)

                /*
                 * Если после всех проверок ни какая ошибка не произошла
                 * и переменные ошибок остались в false
                 * то делаем запись в БД
                 * по всем таблицам
                 */
                if (!$errors AND $result['errors'] == '') {

                    /*
                     * Таблица .table2
                     * Запись в таблицу "Движение товара" - goods_movement
                     * ===================================================
                     * Если массив для записи в таблицу $arr_todb_goods_movement
                     * "Движение товара" не пуст
                     */

                    if(count($arr_todb_goods_movement) > 0) {
                        $goods_movement_command = Yii::$app->db->createCommand()->batchInsert(
                            'goods_movement',
                            [
                                'document_id',
                                'str_dock',
                                'employee_code',
                                'barcode',
                                'quantity',
                            ],
                            $arr_todb_goods_movement
                        );


                        try {
                            $goods_movement_command->execute();
                        } catch (Exception $e) {
                            $result['errors'] .= d::getMessage('WRITE_ERROR_IN_GOODS_MOVEMENT');
                        }

                    }// if(count($arr_todb_goods_movement) > 0)

                }// if (!$errors AND $result['errors'] == '')

            }else{
                /*
                 * Если запись в таблицу "document" (Документ) не удачна
                 * то, в переменную $result[errors] добавляем пояснения ошибки
                 */
                $result['errors'] .= d::getMessage('WRITE_ERROR_IN_DOCUMENT');
            }

        }// else ($document->validate())

        if($result['errors'] == ''){
            $result['status'] = d::getMessage('AJAX_STATUS_SUCCESS');
            $result['header'] = d::getMessage('HEADER_SUCCESS');
            $result['message'] = d::getMessage('CHECK_IS_SAVED');
        }

        return $result;

    }// function debitProduct()

    /**
     * Страница "Оприходование сертификата".
     * Поле ввода штрихкода
     * ================================
     * По штрихкоду получаем информацию
     * из таблицы "Certificates"
     */
    public static function getCertificateByBarcode($data){
        $result = [];
        $certificate = Certificates::findOne(['barcode'=>$data['barcode']]);
        if(!$certificate) return false;
        elseif($certificate['accrued'] == '1'){
            $result['credited'] = d::getMessage('CERTIFICATE_ALREADY_CREDITED');
        }else{
            // Получаем поле "Штрихкод"
            $result['barcode'] = $certificate['barcode'];
            // Получаем поле "Номинал"
            $result['certificate_denomination'] = $certificate['certificate_denomination'];
            // Получаем поле "Оприходован"
            $result['accrued'] = $certificate['accrued'];
            $result['label'] = d::getMessage('CERTIFICATE_ALREADY_CREDITED');
        }

        return $result;
    }

    /**
     * Страница "Оприходование сертификата".
     * Кнопка "Оприходование"
     * ================================
     * Сохраняем данные в БД
     */
    public static function debitCertificate($data){

        $data = d::secureEncode($data);

        $result = [];
        $update = Yii::$app->db->createCommand()
            ->update(
                'certificates',
                [
                    'accrued' => '1',
                    'capitalization_date' => Yii::$app->getFormatter()->asDate(time()),
                    'employee_code_capitalization' => Yii::$app->user->id
                ],
                ['barcode' => $data['barcodes']]);
        try {
            $update->execute();
        }catch (Exception $e){
            $result['errors'] .= d::getMessage('POSTING_ERROR');
        }
    }// function debitCertificate()

    /**
     * Страница "Выгрузка этикеток".
     * Кнопка "Добавить"
     * ================================
     * Получаем товар по номеру документа
     */
    public static function getProductsByDocumentId($data){

        $result = [];
        /*
         * Проверка, существует ли пара
         * document_id - document_type
         * в таблице "document"
         * SELECT * FROM `document` WHERE `id`='4' AND `document_type` IN('03','04')
         */
        $document = Document::findAll([
            'id' => $data['document_id'],
            'document_type' => ['01','02','04']
        ]);

        /*
         * Если документ найден
         * то получаем строки из табилцы
         * движиение товара "goods_movement"
         */
        if($document){
            $goods_movement = GoodsMovement::findAll([
                'document_id' => $data['document_id']
            ]);

            // Если в таблице движение товара что то выбралось
            if($goods_movement){
                $quantity = [];
                $barcodes = [];
                $result['goods_movement'] = $goods_movement;
                $i=0;
                foreach($goods_movement as $product) {
                    /*
                     * Собираем массив штрихкодов
                     * для выборки товаров из "product"
                     */
                    $quantity[$product['barcode']] = $product['quantity'];
                    $barcodes[] = $product['barcode'];

                }

                /*
                 * По штрихкодам получаем
                 * все товары из таблицы "product"
                 */
                if($pts = Product::findAll(['barcode'=>$barcodes])){
                    /*
                     * Перебираем товары и получаем информацию
                     * из нужных таблиц по данным каждого товара
                     * Собираем конечный массив товаров для html таблицы
                     */
                    $i=0;
                    foreach($pts as $pt){
                        // pt_ne - product_nomenclature
                        $pt_ne = ProductNomenclature::findOne(['id'=>$pt['item_code']]);
                        // Наименование номенклатуры
                        $result['products'][$i]['nomenclature_name']=
                            $pt_ne['nomenclature_name'];
                        // Надпись на этикетке
                        $result['products'][$i]['labeling']=
                            $pt_ne['labeling'];
                        // Цвет
                        $result['products'][$i]['color']=
                            Color::findOne(['id'=>$pt_ne['code_color']])['name'];
                        // Рисунок/узор
                        $result['products'][$i]['design']=
                            Design::findOne(['id'=>$pt_ne['code_pattern']])['name'];
                        // Количество по штрихкоду
                        $result['products'][$i]['quantity']=
                            $quantity[$pt['barcode']];
                        // Розничная цена
                        $result['products'][$i]['retail_price']=
                            $pt['retail_price'];
                        // Цена по акции
                        $result['products'][$i]['action_price']=
                            $pt['action_price'];
                        // Автоматическая скидка
                        $result['products'][$i]['automatic_discount']=
                            $pt['automatic_discount'];
                        // Размер производителя
                            $result['products'][$i]['manufacturer_size'] =
                                SizeManufacturer::findOne([
                                    'id' => $pt['code_manufacturer_size']])['name'];
                        // Штрихкод
                        $result['products'][$i]['barcode']=
                            $pt['barcode'];

                        $i++;
                    }// foreach $pts

                }else {
                    // Не найдено ни одного товара
                    $result['error'] =
                        d::getMessage('NO_ONE_PRODUCT_NOT_FOUND');
                }
            }else{
                // По номеру документа товар не найден
                $result['error'] =
                    d::getMessage('NO_PRODUCT_FOR_THIS_DOCUMENT');
            }
        }else{
            // Номер документа не существует
            $result['error'] =
                d::getMessage('DOCUMENT_ID_ERROR');
        }

        return $result;

    }// function getProductsByDocumentId()

    /**
     * Страница "Поиск чека"
     * Поле "Введите штрихкод"
     * ================================
     * Поиск по введеному штрихкоду
     */
    public static function checkSearchBarcode($data){

        $result = [];
        $document_ids = [];
        $document_data = [];
        /*
         * Проверка, есть ли такой штрихкод
         * в таблице product
         */
        $product = Product::find()
            ->where(['barcode' => $data['barcode']])
            ->asArray()->one();
        /*
         * Проверка, есть ли такой штрихкод
         * в таблице product
         * ================================
         * Если штрихкод найден, то производим поиск
         */
        if($product){

            // Создаем объект выборки из таблицы "Document"
            $documents_query = Document::find();

            /*
             * Условия выборки по периоду
             */
            // Если запрошенный период один день day
            if($data['date_range']){
                /*
                 * Из таблицы "document"
                 * выбираем все строки, где
                 * тип документа "товарный чек" - 03
                 * и период один день(по дате)
                 */
                $documents_query->andWhere([
                        'document_type'=>'03',
                        'document_date'=>$data['date_range']
                    ]);
            }else{
                // Если запрошенный период неделя/две недели
                $documents_query->where([
                    '>','document_date',$data['date_range_from']])
                    ->andWhere(['<', 'document_date', $data['date_range_to']]);
            }

            /*
             * Делаем выборку из таблицы document
             * по собранным условиям
             */
            $documents = $documents_query->asArray()->all();

            // Если в таблице "Document" что то найдено
            if($documents){
                foreach($documents as $document) {
                    /*
                     * Собираем в массив все ID
                     * найденные по заданным условиям
                     * для поиска по полю document_id
                     * в таблице goods_movement
                     */
                    $document_ids[] = $document['id'];

                    /*
                     * В этот массив соберем все даты,
                     * которые будем использовать в переборке выбраных товаров
                     * из таблицы "Движение товара"
                     * ключом будет document_id
                     * значением - весь массив
                     * ===================================================
                     * при переборке товаров, будем подставлять
                     * нужную дату в нужный товар.
                     * Получая дату по ключу
                     */
                    $document_data[$document['id']]['document_id'] =
                        $document['id'];
                    $document_data[$document['id']]['date_time'] =
                        $document['document_date'];
                }

                /*
                 * Делаем выборку из табилцы "Движение товара"
                 * по списку найденных documetn_id,
                 * по штрихкоду, и по section=1
                 */
                $goods_movement = GoodsMovement::find()
                    ->where(['IN','document_id',$document_ids])
                    ->andWhere([
                        'barcode' => $data['barcode'],
                        'section' => '1'
                    ])
                    ->asArray()->all();

                /*
                 * Если в таблице "Движение товара"
                 * что нашлось
                 */
                if($goods_movement){
                    /*
                     * Перебираем товары и получаем информацию
                     * из нужных таблиц по данным каждого товара
                     * Собираем конечный массив товаров для html таблицы
                     */
                    $i=0;
                    foreach($goods_movement as $pt){
                        // pt_ne - product_nomenclature
                        $pt_ne = ProductNomenclature::findOne([
                            'id'=>$product['item_code']]);
                        // Код документа (ID документа)
                        $result[$i]['document_id']=
                            $document_data[$pt['document_id']]['document_id'];
                        // Дата и время
                        $result[$i]['date_time']=
                            $document_data[$pt['document_id']]['date_time'];
                        // Размер производителя
                        $result[$i]['manufacturer_size']=
                            SizeManufacturer::findOne([
                                'id'=>$product['code_manufacturer_size']])['name'];
                        // Розничная цена
                        $result[$i]['retail_price']=
                            $product['retail_price'];
                        // Товарная группа
                        $result[$i]['commodity_group'] =
                            ProductGroup::findOne([
                                'id'=>$pt_ne['commodity_group_code']])['name'];
                        // Артикул производителя
                        $result[$i]['article_of_manufacture'] =
                            $pt_ne['article_of_manufacture'];
                        // Наименование номенклатуры
                        $result[$i]['nomenclature_name']=
                            $pt_ne['nomenclature_name'];
                        // Цвет
                        $result[$i]['color']=
                            Color::findOne(['id'=>$pt_ne['code_color']])['name'];
                        // Рисунок/узор
                        $result[$i]['design']=
                            Design::findOne(['id'=>$pt_ne['code_pattern']])['name'];
                        // Штрихкод
                        $result[$i]['barcode'] = $data['barcode'];
                        $i++;

                    }// foreach $pts
                }else{
                    /*
                     * В таблице "Движение товара"
                     * не найдено ни одного товара
                     * ..._GM - GoodsMovement
                     */
                    $result['error'] =
                        d::getMessage('NO_ONE_PRODUCT_NOT_FOUND_GM');
                }


            }else {
                // Не найдено ни одного документа
                $result['error'] =
                    d::getMessage('NO_ONE_DOCUMENT_NOT_FOUND');
            }
        }else{
            // Штрихкод не найден, поиск невозможен
            $result['error'] =
                d::getMessage('BARCODE_NOT_FOUND');
        }

        return $result;

    }// function checkSearch(...)

    /**
     * Страница "Поиск чека"
     * Кнопка "Поиск"
     * =============================
     * Поиск по параметрам фильтра
     */
    public static function checkSearchfilter($data){

        $result = [];
        $document_ids = [];
        $document_data = [];
        $barcodes = [];
        $products = [];
        $filter = [];
        /*
         * Из настроек сайта получаем переменные для number_format
         */
        $ko = Yii::getAlias('@ko0');// kopecks
        $fl = Yii::getAlias('@fl');// float
        $th = Yii::getAlias('@th');// thousand

        // Создаем объект выборки из таблицы "Document"
        $documents_query = Document::find();

        /*
         * Условия выборки по периоду
         */
        // Если запрошенный период один день day
        if($data['date_range']){
            /*
             * Из таблицы "document"
             * выбираем все строки, где
             * тип документа "товарный чек" - 03
             * и период один день(по дате)
             */
            $documents_query->andWhere([
                    'document_type'=>'03',
                    'document_date'=>$data['date_range']
                ]);
        }else{
            // Если запрошенный период неделя/две недели
            $documents_query->where([
                '>','document_date',$data['date_range_from']])
                ->andWhere(['<', 'document_date', $data['date_range_to']]);
        }

        /*
         * Делаем выборку из таблицы document
         * по собранным условиям
         */
        $documents = $documents_query->asArray()->all();

        // Если в таблице "Document" что то найдено
        if($documents){

            foreach($documents as $document) {
                /*
                 * Собираем в массив все ID
                 * найденные по заданным условиям
                 * для поиска по полю document_id
                 * в таблице goods_movement
                 */
                $document_ids[] = $document['id'];

                /*
                 * В этот массив соберем все даты,
                 * которые будем использовать в переборке выбраных товаров
                 * из таблицы "Движение товара"
                 * ключом будет document_id
                 * значением - весь массив
                 * ===================================================
                 * при переборке товаров, будем подставлять
                 * нужную дату в нужный товар.
                 * Получая дату по ключу
                 */
                $document_data[$document['id']]['document_id'] =
                    $document['id'];
                $document_data[$document['id']]['date_time'] =
                    $document['document_time'];
            }

            /*
             * Делаем выборку из табилцы "Движение товара"
             * по списку найденных documetn_id,
             * по штрихкоду, и по section=1
             */
            $goods_movement = GoodsMovement::find()
                ->where(['IN','document_id',$document_ids])
                ->andWhere(['section' => '1'])
                ->asArray()->all();

            /*
             * Если в таблице "Движение товара"
             * что нашлось
             */
            if($goods_movement){

                /*
                 * Собираем все штрихкоды
                 * выбранные из таблицы "Движение товара"
                 * ======================================
                 * Все повторяющиеся штрихкоды
                 * будут перезаписаны
                 * в итоге на выходу получим только
                 * уникальные элементы массива
                 */
                foreach($goods_movement as $item_product)
                    $barcodes[$item_product['barcode']] = $item_product['barcode'];


                $product = Product::find()
                    ->where(['IN','barcode',$barcodes])
                    ->asArray()->all();
                /*
                 * Пересобираем массив product
                 * чтобы в клюах были штрихкоды
                 * ============================
                 * По этим штихкодам будем выбирать
                 * соответствующие данные для конечного массива
                 */
                foreach($product as $pt)
                    $products[$pt['barcode']] = $pt;

                /*
                 * Перебираем товары и получаем информацию
                 * из нужных таблиц по данным каждого товара
                 * Собираем конечный массив товаров для html таблицы
                 */
                $i=0;
                foreach($goods_movement as $pt){
                    /*
                     * Выборка из таблицы "Номенклатура товара"
                     * pt_ne - product_nomenclature
                     */
                    $pt_ne = ProductNomenclature::findOne([
                        'id'=>$products[$pt['barcode']]['item_code']]);

                    /*
                     * Если какие то параметры фильтра заданы
                     * ======================================
                     * Принцип работы филтра такой:
                     * Если какое то из значений фильтра
                     * не равняется значению текущей итерации
                     * то пропускаем итерацию.
                     * Иначе - собираем результирующий массив
                     */
                    // Код товарной группы (номенклатура товара)
                    if($data['product_group']){
                        if($pt_ne['commodity_group_code'] !=
                            $data['product_group']
                        ) continue;
                    }
                    // Код бренда (номенклатура товара)
                    if($data['brand_code']){
                        if($pt_ne['brand_code'] !=
                            $data['brand_code']
                        ) continue;
                    }
                    // Строка артикула (номенклатура товара)
                    if($data['reference_value']){
                        if($pt_ne['id'] !=
                            $data['reference_value']
                        ) continue;
                    }
                    // Код размера (product)
                    if($data['size_manufacturer']){
                        if($products[$pt['barcode']]['code_manufacturer_size'] !=
                            $data['size_manufacturer']
                        ) continue;
                    }

                    // Код документа (ID документа)
                    $result[$i]['document_id']=
                        $document_data[$pt['document_id']]['document_id'];
                    // Дата и время
                    $result[$i]['date_time']=
                        Yii::$app->getFormatter()->asDatetime($document_data[$pt['document_id']]['date_time']);
                    // Размер производителя
                    $result[$i]['manufacturer_size']=
                        SizeManufacturer::findOne([
                            'id'=>$products[$pt['barcode']]['code_manufacturer_size']])['name'];
                    // Розничная цена
                    $result[$i]['retail_price']=
                        number_format(
                            $products[$pt['barcode']]['retail_price'],
                            $ko, $fl, $th
                        );
                    // Товарная группа
                    $result[$i]['commodity_group'] =
                        ProductGroup::findOne([
                            'id'=>$pt_ne['commodity_group_code']])['name'];
                    // Артикул производителя
                    $result[$i]['article_of_manufacture'] =
                        $pt_ne['article_of_manufacture'];
                    // Наименование номенклатуры
                    $result[$i]['nomenclature_name']=
                        $pt_ne['nomenclature_name'];
                    // Цвет
                    $result[$i]['color']=
                        Color::findOne(['id'=>$pt_ne['code_color']])['name'];
                    // Рисунок/узор
                    $result[$i]['design']=
                        Design::findOne(['id'=>$pt_ne['code_pattern']])['name'];
                    // Штрихкод
                    $result[$i]['barcode'] = $pt['barcode'];
                    $i++;

                }// foreach $pts
            }else{
                /*
                 * В таблице "Движение товара"
                 * не найдено ни одного товара
                 * ..._GM - GoodsMovement
                 */
                $result['error'] =
                    d::getMessage('NO_ONE_PRODUCT_NOT_FOUND_GM');
            }


        }else {
            // Не найдено ни одного документа
            $result['error'] =
                d::getMessage('NO_ONE_DOCUMENT_NOT_FOUND');
        }

        return $result;

    }// function checkSearchfilter(...)

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
    public static function getDocuments($data)
    {
        $result = [];

        if ($data['year'] == '' AND $data['month'] == ''){
            /*
             * Если месяц и год пусты
             * то выбираем всё за текущий месяц
             * текущего года
             */
            $date = date('Y-m', time());
        }elseif($data['year'] == '' AND $data['month'] != '') {
            /*
             * Если год пуст, а месяц выбран
             * то выбираем всё за выбранный месяц
             * текущего года
             */
            $date = date('Y-' . $data['month'], time());
        }elseif($data['year'] != '' AND $data['month'] == '') {
            /*
             * Если месяц пуст, а год выбран
             * то выбираем всё за выбранный год
             */
            $date = date('Y', time());
        }else{
            /*
             * Если выбраны и год и месяц
             * то делаем выборку по заданным параметрам
             */
            $date = date($data['year'].'-'.$data['month'],time());
        }

        /*
         * Из таблицы document
         * делаем выборку по заданным параметрам
         */
        $dt = Document::find()
            ->select(['document_type','id','document_date'])
            ->where(['like', 'document_date', $date])
            ->andWhere([
                'document_type'=>['04','05','06','07'],
                /*
                 * Списанные на ноль документы
                 * загружать в список "Выберите документ" не нужно
                 * У них значение "document_correction_code" будет "0"
                 */
                'document_correction_code'=>null
            ]);

        // Если есть тип документа
        if($data['document_type'] != '')
            $dt->andWhere(['document_type'=>$data['document_type']]);

        // Производим настроенную выборку
        $result['documents'] = $dt->asArray()->all();

        if(count($result['documents'])){
            for($i=0;$i<count($result['documents']);$i++){

                /*
                 * В $result[document_type_value] зписшем номер документа
                 * Потому что $result[document_type]
                 * будет перезаписан наименованием типа документа
                 */
                $result['documents'][$i]['document_type_value'] =
                    $result['documents'][$i]['document_type'];

                /*
                 * Из таблицы document_type
                 * выбираем поле name по номеру документа (code)
                 * переписываем основной массив
                 * и вместо номера документа,
                 * подставляем наименование документа
                 */
                $result['documents'][$i]['document_type'] =
                    DocumentType::find()
                        ->where([
                            'code'=>$result['documents'][$i]['document_type']
                        ])->asArray()->one()['name'];

                /*
                 * Дополняем массив форматом даты
                 * которая будет выводится на экран
                 */
                $result['documents'][$i]['date_view'] = d::changeDate(
                    $result['documents'][$i]['document_date'],
                    'format','dd.mm.yyyy'
                );
            }

        }else $result['errors'] = d::getMessage('DOCUMENTS_NOT_FOUND');

        return $result;
    }

    /**
     * Страница "Товарный учет"
     * ===============================
     * Выпадающий список "Выберите документ"
     * -------------------------------------
     * Из таблицы "Движение товара"
     * получаем строки по ID документа
     */
    public static function getFromGoodsMovement($data)
    {
        $result = [];
        $documents_data = [];// Для данных из таблицы document
        /*
         * Список ID по которым нужно будет выбрать
         * все строки из таблицы движение товара
         */
        $ids_ds = [];
        $result['errors'] = '';
        /*
         * В ключе - уникальный штрихкод
         * в значении - общее количество по штрихкоду
         */
        $counter_by_barcode = [];

        /*
         * Из таблицы "document" выбираем все строки
         * у которых основной  id совпадает с запрошенным ID
         * и у которых document_correction_code
         * совпадает с запошенным ID
         */
        $ds = Document::find()
            ->where(['id'=>$data['document_id']])
            ->orWhere(['document_correction_code'=>$data['document_id']])
            ->asArray()->all();

        // Если ни один документ не существует
        if(!$ds) {
            $result['errors'] .=
                d::getMessage('DOCUMENT_ID_ERROR') . '<br>';
        }else{
            /*
             * Собираем массив, где в ключе будет ID документа
             * а в значении будет "Дата документа"
             */
            foreach($ds as $item){
                $ids_ds[] = $item['id'];
                if($item['id'] == $data['document_id'])
                    $result['document']['comment'] =
                        $item['counterparty_document_comment'];
                $documents_data[$item['id']] = [
                    'document_date'=>$item['document_date'],
                ];
            }
        }

        /*
         * Из таблицы "Движение товара"
         * делаем выборку по всем ID документов
         */
        $gm_rows = GoodsMovement::find()
            ->orderBY('str_dock DESC')
            ->where(['in','document_id',$ids_ds])
            ->asArray()->all();

        // Если что то выбралось
        if(count($gm_rows)){
            /*
             * Массив, по которому ориентируемся
             * была ли уже такая строка в итерации
             */
            $gm_rs = [];
            /*
             * Массив для номеров строк которые повторяются.
             * ключ = номер строки
             * значение = номер строки
             * по одинаковому ключу - значение перепишется
             */
            $double_str_dock = [];
            foreach($gm_rows as $dock){
                if(array_key_exists($dock['str_dock'],$gm_rs)){
                    $double_str_dock[$dock['str_dock']] = $dock['str_dock'];
                }
                $gm_rs[$dock['str_dock']] = $dock['str_dock'];
            }

//            $result['dg'] = d::toString($double_str_dock);

            foreach($gm_rows as $item) {

                // Номер блока section1
                $result['goods_movement'][$item['str_dock']]['number_section'] =
                    $data['number_section'];

                /*
                 * Из таблицы "product" по текущему штрихкоду
                 * получаем данные по товару
                 */
                $product = Product::find()
                    ->where(['barcode'=>$item['barcode']])
                    ->asArray()->one();
                /*
                 * Если в таблице "product"
                 * по штрихкоду ничего не нашлось
                 * пропускаем итерацию
                 */
                if(!count($product)) continue;

                /*
                 * Если массив дублирующихся строк
                 * содержит текущий номер строки
                 * значит скрываем её.
                 * Ставим - display: none; классом dn
                 */
                if(in_array($item['str_dock'],$double_str_dock)){
                    $result['goods_movement'][$item['str_dock']]['dn'] = 'dn';
                }

                /*
                 * Вместо счетчика $i,
                 * используем текущий номер строки - $item['str_dock']
                 * так как номер строки никогда не будет повторятся
                 * тем самым избежим повторения строк на экране.
                 * А те у которых есть дубли будут скрыты через dn
                 */

                // ID документа
                $result['goods_movement'][$item['str_dock']]['document_id'] =
                    ($item['document_id'])?$item['document_id']:'0';
                // Дата документа
                $result['goods_movement'][$item['str_dock']]['document_date'] =
                    $documents_data[$item['document_id']]['document_date'];

                // По штрихкоду выбираем из всех строк "document_id"
                $gsmt = GoodsMovement::find()
                    ->select(['document_id'])
                    ->where(['barcode'=>$item['barcode']])
                    ->asArray()->all();

                /*
                 * По минимальному document_id
                 * из таблицы document получаем код поставщика
                 * текущей строки штрихкода
                 */
                $result['goods_movement'][$item['str_dock']]['provider'] =
                    Document::findOne(['id'=>min($gsmt)])['vendor_code'];

                /*
                 * Получаем информацию из таблицы "Номенклатура товара"
                 * по коду номенклатуры (по ID номенклатуры)
                 */
                $nomenclature = ($nre = ProductNomenclature::findOne([
                    'id' => $product['item_code']])) ? $nre : '';

                if ($nomenclature) {

                    // Наименование номенклатуры
                    $result['goods_movement'][$item['str_dock']]['nomenclature_name'] =
                        $nomenclature['nomenclature_name'];
                    // Получаем "цвет" по ID
                    $result['goods_movement'][$item['str_dock']]['color'] = ($cor = Color::findOne([
                        'id' => $nomenclature['code_color']])) ? $cor['name'] : '';
                    // Получаем "рисунок/узор" по ID
                    $result['goods_movement'][$item['str_dock']]['design'] = ($dgn = Design::findOne([
                        'id' => $nomenclature['code_pattern']])) ? $dgn['name'] : '';
                }

                // Номер строки
                $result['goods_movement'][$item['str_dock']]['str_dock'] = $item['str_dock'];
                // Получаем "Размер производителя" по ID
                $result['goods_movement'][$item['str_dock']]['manufacturer_size'] =
                    ($sm = SizeManufacturer::findOne([
                        'id' => $product['code_manufacturer_size']])) ? $sm['name'] : '';
                // Себестоимость
                $result['goods_movement'][$item['str_dock']]['cost_price'] = $product['cost_of_goods'];
                // Розничная цена
                $result['goods_movement'][$item['str_dock']]['retail_price'] = $product['retail_price'];
                // Цена по акции
                $result['goods_movement'][$item['str_dock']]['action_price'] = $product['action_price'];
                // Автоматическая скидка
                $result['goods_movement'][$item['str_dock']]['automatic_discount'] = $product['automatic_discount'];
                // Штрихкод
                $result['goods_movement'][$item['str_dock']]['barcode'] = $product['barcode'];
                // Количество
                $result['goods_movement'][$item['str_dock']]['quantity'] =
                    // Убираем минус, если он есть
                    abs($item['quantity']);

            }
        }else $result['errors'] .= d::getMessage('NOT_FOUND');

        return $result;
    }

    /**
     * Страница "Товарный учет"
     * ===============================
     * Выпадающий список "Выберите документ"
     * -------------------------------------
     * Из таблицы "Движение товара"
     * получаем строки по ID документа
     */
    public static function accountBalance($data)
    {
        $result = [];
        $quantities_db = [];
        $barcodes = [];

        // Собираем все уникальные штрихкоды со страницы
        // в один массив
        foreach($data['all_barcodes'] as $key=>$item)
            $barcodes[] = $key;

        /*
         * По всем уникальным штрихкодам
         * выбираем все строки из таблицы "Движение товара"
         */
        $quantities = GoodsMovement::find()
            ->where(['in','barcode',$barcodes])
            ->asArray()->all();

        /*
         * Общее количество по БД по уникальным штрихкодам.
         * Собираем уникальные штрихкоды из выборки "Движение товара"
         * где в ключе будет штрихкод
         * а в значении - все данные по штрихкоду и
         * в количестве - общее количество:
         * сумма количеств всех строк по уникальному штрихкоду
         * и ещё в каждую строку выборки добавляем ключ:
         * общее количество по штрихкоду - "total_count_uniq_barcode"
         */
        foreach($quantities as $item) {
            if (array_key_exists($item['barcode'], $quantities_db)) {
                $quantities_db[$item['barcode']]['total_count_uniq_barcode'] =
                    ($quantities_db[$item['barcode']]['total_count_uniq_barcode'] +
                        $item['quantity']);
            } else{
                $item['total_count_uniq_barcode'] = $item['quantity'];
                $quantities_db[$item['barcode']] = $item;
            }
        }

        /*
         * Добавляем данные из других таблиц
         */
        foreach($quantities_db as $item){

            /*
             * Из таблицы "product" по текущему штрихкоду
             * получаем данные по товару
             */
            $product = Product::find()
                ->where(['barcode'=>$item['barcode']])
                ->asArray()->one();
            /*
             * Если в таблице "product"
             * по штрихкоду ничего не нашлось
             * пропускаем итерацию
             */
            if(!count($product)) continue;

            /*
             * Из таблицы "document" по текущему document_id
             * получаем данные по документу
             */
            $document = Document::find()
                ->where(['id'=>$item['document_id']])
                ->asArray()->one();
            /*
             * Если в таблице "document"
             * по штрихкоду ничего не нашлось
             * пропускаем итерацию
             */
            if(!count($document)) continue;

            // Номер блока section2 в таблице
            $result['goods_movement'][$item['id']]['number_section'] =
                $data['number_section'];

            /*
             * Вместо счетчика $i,
             * используем текущий ID документа - $item[id]
             */

            // ID документа
            $result['goods_movement'][$item['id']]['document_id'] =
                ($item['document_id'])?$item['document_id']:'0';
            // Дата документа
            $result['goods_movement'][$item['id']]['document_date'] =
                $document['document_date'];

            /*
             * Получаем информацию из таблицы "Номенклатура товара"
             * по коду номенклатуры (по ID номенклатуры)
             */
            $nomenclature = ($nre = ProductNomenclature::findOne([
                'id' => $product['item_code']])) ? $nre : '';

            if ($nomenclature) {

                // Наименование номенклатуры
                $result['goods_movement'][$item['id']]['nomenclature_name'] =
                    $nomenclature['nomenclature_name'];
                // Получаем "цвет" по ID
                $result['goods_movement'][$item['id']]['color'] = ($cor = Color::findOne([
                    'id' => $nomenclature['code_color']])) ? $cor['name'] : '';
                // Получаем "рисунок/узор" по ID
                $result['goods_movement'][$item['id']]['design'] = ($dgn = Design::findOne([
                    'id' => $nomenclature['code_pattern']])) ? $dgn['name'] : '';
            }

            // Номер строки
//            $result['goods_movement'][$item['id']]['str_dock'] = $item['str_dock'];
            // Получаем "Размер производителя" по ID
            $result['goods_movement'][$item['id']]['manufacturer_size'] =
                ($sm = SizeManufacturer::findOne([
                    'id' => $product['code_manufacturer_size']])) ? $sm['name'] : '';
            // Себестоимость
            $result['goods_movement'][$item['id']]['cost_price'] = $product['cost_of_goods'];
            // Розничная цена
            $result['goods_movement'][$item['id']]['retail_price'] = $product['retail_price'];
            // Цена по акции
            $result['goods_movement'][$item['id']]['action_price'] = $product['action_price'];
            // Автоматическая скидка
            $result['goods_movement'][$item['id']]['automatic_discount'] = $product['automatic_discount'];
            // Штрихкод
            $result['goods_movement'][$item['id']]['barcode'] = $product['barcode'];

            /*
             * Остаток на учете
             * ================
             * Если в списке "Выберите документ"
             * вырано значение "Добавить новый"
             */
            if($data['action_type'] == 'new') {
                $result['goods_movement'][$item['id']]['remainder_list'] =
                    $item['total_count_uniq_barcode'];
            }else{
            // Если выбран существующий документ

                // Если тип документа "Оприходование"
                if($data['document_type'] == '04'){
                    $result['goods_movement'][$item['id']]['remainder_list'] =
                        ($item['total_count_uniq_barcode'] -
                            (
                                $data['existing_barcodes'][$item['barcode']] +
                                $data['new_barcodes'][$item['barcode']]
                            )
                        );
                }else{
                // Если тип не оприходование
                    $result['goods_movement'][$item['id']]['remainder_list'] =
                        ($item['total_count_uniq_barcode'] +
                            $data['existing_barcodes'][$item['barcode']]) -
                                $data['new_barcodes'][$item['barcode']];
                }
            }

            // Количество
            $result['goods_movement'][$item['id']]['quantity'] =
                $data['new_barcodes'][$item['barcode']];

            /*
             * Остаток факт
             * ============
             * Если тип документа выбран
             */
            if($data['document_type']){
                // Если тип документа "Оприходование"
                if($data['document_type'] == '04'){
                    $result['goods_movement'][$item['id']]['remainder_fact'] =
                        ($item['total_count_uniq_barcode'] +
                            $data['new_barcodes'][$item['barcode']]);

                }else{
                // Если тип не оприходование
                    $result['goods_movement'][$item['id']]['remainder_fact'] =
                        ($item['total_count_uniq_barcode'] -
                            $data['new_barcodes'][$item['barcode']]);
                }
            }else $result['goods_movement'][$item['id']]['remainder_fact'] = '';
        }


//        d::pe($data);
//        d::pe($result);

        return $result;
    }

    /**
     * Страница "Заказы"
     * =================
     * Кнопка "Сохранить"
     */
    public static function orderStatusChange($data)
    {
        $time = Yii::$app->getFormatter()->asTimestamp(time());

        $order = Orders::findOne(['id'=>$data['order_id']]);

        // Если "Новый" заказ
        if($data['type'] == 'new_orders'){
            // Если чекбокс отмечен
            if($data['type_status'] == '1') $order->ready_time = $time;
        }else{
            // Если "Готовый" заказ

            // Если одна из кнопок радио выбрана
            if($data['type_status'] != ''){
                // Проверка - какая кнопка радио выбрана
                if($data['type_status'] == 'completed') $order->complete_time = $time;
                else $order->cancel_time = $time;
            }
        }

        // Если поле комментарий не пусто
        if($data['comment'] != '') $order->comment = $data['comment'];

        if($order->save()) return true;
        else return false;

    }// function orderStatusChange(...)





    /*
     * =======================================================================
     * Ниже идущие методы, нужно определить в другой класс.
     */



    /*
     * Получение списка артикулов по бренду
     */
    public static function getArticlesByBrand($brand_code){
        return ProductNomenclature::find()
            ->where(['brand_code' => $brand_code])
            ->orderBy('article_of_manufacture')
            ->all();
    }

}// Class Ajax