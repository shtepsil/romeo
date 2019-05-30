<?php
namespace backend\controllers;

use app\models\Brand;
use app\models\Document;
use app\models\DocumentType;
use app\models\FilesExcel;
use app\models\Product;
use app\models\ProductGroup;
use app\models\ProductNomenclature;
use app\models\Universal;
use backend\components\GetData;
use \backend\controllers\MainController as d;
use backend\libraries\barcode\BarcodeImage;
use backend\models\DiscountCards;
use backend\models\Orders;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\helpers\BaseHtml;
use yii\helpers\Url;
use common\components\GeneralRepository;
use common\models\User;
use backend\models\ExcelFiles;
use backend\models\Ajax;
use backend\models\ReferenceBooksForm;
use app\models\ReferenceBooks;

/**
 * Site controller
 */
class SiteController extends MainController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    $this->getActoinsByRole()
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Метод возвращает тип массива разрешенных actions
     * в зависимости от роли пользователя
     */
    private function getActoinsByRole(){

        $access_rights = [
            'user'=>[
                'logout',
                'index',
                'search',
                'sales-receipt',
                'open-document',
                'cash-report',
                'offline',
                'check-search',
                'orders',
//                '',
            ],
            'admin'=>[
                'goods-receipt',
                'product-nomenclature',
                'capitalization-goods',
                'return-to-principal',
                'return-marriage-to-supplier',
                'write-off-goods',
                'inventory',
                'unloading-labels',
                'commodity-accounting',
            ],
            'sadmin'=>[
                'document',
                'reference-books',
                'product-group',
                'workers',
                'capitalization-certificate',
                'discount-cards',
                'import-files',
                'ajax',
                'debugg',
                'barcode-image',
                'kkm',
                'export-excel-c-d-b',
            ],
            'webmaster'=>[
                'email',
            ]
        ];

        switch(Yii::$app->user->role){
            case 'user':
                $actions = $access_rights['user'];
                break;
            case 'admin':
                $actions = array_merge(
                    $access_rights['user'],
                    $access_rights['admin']);
                break;
            default:
                if(Yii::$app->user->username != 'webmaster'){
                    $actions = array_merge(
                        $access_rights['user'],
                        $access_rights['admin'],
                        $access_rights['sadmin']);
                }else{
                    $actions = array_merge(
                        $access_rights['user'],
                        $access_rights['admin'],
                        $access_rights['sadmin'],
                        $access_rights['webmaster']);
                }


        }

        $arr_access = [
            'actions' => $actions,
            'allow' => true,
            'roles' => [
                User::ROLE_USER,
                User::ROLE_ADMIN,
                User::ROLE_SADMIN
            ],
        ];

        return $arr_access;

    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->response->redirect(Url::to('admin/cash-report'));
//        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // после авторизации - редиректим на страницу "форма поиска"
            Yii::$app->response->redirect(Url::to('cash-report'));
//            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /* =========================================== */
    //            ПРОСТОЙ ПОЛЬЛЗОВАЬТЕЛЬ
    /* =========================================== */

    /**
     * Страница "форма поиска".
     *
     * @return string
     */
    public function actionSearch()
    {
        return $this->render('search',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
        ]);
    }

    /**
     * Страница "Поиск чека".
     *
     * @return string
     */
    public function actionCheckSearch()
    {
        return $this->render('check-search',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty' => $this->renderAjax('shortcodes/tr-empty'),
        ]);
    }

    /**
     * Страница "Товарный чек".
     *
     * @return string
     */
    public function actionSalesReceipt()
    {
        return $this->render('sales-receipt',[
            /**
             * Используем renderAjax
             * чтобы получить только верстку файла alerts.php
             */
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty' => $this->renderAjax('shortcodes/tr-empty'),
            'but' => $this->renderAjax('shortcodes/but'),
            /**
             * Тип документа "Товарный чек"
             * имеет ID '3' в таблице document_type
             */
            'dock_type' => DocumentType::getOne(['code'=>'03'])
        ]);
    }

    /**
     * Страница "Открыть документ".
     *
     * @return string
     */
    public function actionDocument()
    {
        return $this->render('document');
    }

    /**
     * Страница "Кассовый отчет".
     *
     * @return string
     */
    public function actionCashReport()
    {

        return $this->render('cash-report',[
            'role'=>User::ROLE_USER,
            /**
             * Используем renderAjax
             * чтобы получить только верстку файла alerts.php
             */
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty' => $this->renderAjax('shortcodes/tr-empty'),
        ]);
    }

    /* =========================================== */
    //               ПРОСТО АДМИН
    /* =========================================== */

    /**
     * Страница "Поступление товара".
     *
     * @return string
     */
    public function actionGoodsReceipt()
    {
        $zero = [
            'zero'=>Yii::getAlias('@zero'),
            'zero,'=>Yii::getAlias('@zero,'),
            'zero_one'=>Yii::getAlias('@zero_one'),
        ];
        return $this->render('goods-receipt',[
            'alerts'=>$this->renderAjax('shortcodes/alerts'),
            'zero' => $zero
        ]);
    }

    /**
     * Страница "Номенклатура товара".
     *
     * @return string
     */
    public function actionProductNomenclature()
    {
        return $this->render('product-nomenclature',[
            'alerts'=>$this->renderAjax('shortcodes/alerts'),
            'modal_upload_files' =>
                $this->renderAjax('shortcodes/modal-file-upload')
        ]);
    }

    /**
     * Страница "Оприходование товара".
     *
     * @return string
     */
    public function actionCapitalizationGoods()
    {
        return $this->render('capitalization-goods',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty' => str_replace('%section%','',$this->renderAjax('shortcodes/tr-empty')),
            /**
             * Тип документа "Товарный чек"
             * имеет ID '3' в таблице document_type
             */
            'dock_type' => DocumentType::getOne(['code'=>'04'])['code']
        ]);
    }

    /**
     * Страница "Возврат комитенту".
     *
     * @return string
     */
    public function actionReturnToPrincipal()
    {
        return $this->render('return-to-principal');
    }

    /**
     * Страница "Возврат брака поставщику".
     *
     * @return string
     */
    public function actionReturnMarriageToSupplier()
    {
        return $this->render('return-marriage-to-supplier');
    }

    /**
     * Страница "Списание товара".
     *
     * @return string
     */
    public function actionWriteOffGoods()
    {
        return $this->render('write-off-goods');
    }

    /**
     * Страница "Инвентаризация".
     *
     * @return string
     */
    public function actionInventory()
    {
        return $this->render('inventory');
    }

    /**
     * Страница "Инвентаризация".
     *
     * @return string
     */
    public function actionOpenDocument()
    {
        return $this->render('open-document');
    }


    /* =========================================== */
    //               СУПЕР АДМИН
    /* =========================================== */

    /**
     * Страница "Справочники".
     *
     * @return string
     */
    public function actionReferenceBooks()
    {
        return $this->render('reference-books');
    }

    /**
     * Страница "товарная группа".
     *
     * @return string
     */
    public function actionProductGroup()
    {
        return $this->render('product-group');
    }

    /**
     * Страница "Работники".
     *
     * @return string
     */
    public function actionWorkers()
    {
        return $this->render('workers',[
            'alerts' => $this->renderAjax('shortcodes/alerts'),
        ]);
    }

    /**
     * Страница "Оприходование сертификата".
     *
     * @return string
     */
    public function actionCapitalizationCertificate()
    {
        return $this->render('capitalization-certificate',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty' => str_replace('%section%','',$this->renderAjax('shortcodes/tr-empty')),
        ]);
    }

    /**
     * Страница "Дисконтные карты".
     *
     * @return string
     */
    public function actionDiscountCards()
    {
        return $this->render('discount-cards');
    }

    /**
     * Страница "Импорт файлов Excel штрихкодов/сертификатов".
     *
     * @return string
     */
    public function actionImportFiles()
    {

        $list_files = FilesExcel::find()->all();
        $rows_list_files = '';
        $count_files = 0;
        foreach($list_files as $file){
            $count_files++;
            $fs['id'] = $file['id'];
            $fs['name'] = $file['name'];
            $fs['full_name'] = $file['name'].'.'.$file['ext'];
            $arr_fn = explode('_',$file['name']);
            $fs['date'] = $arr_fn[1];
            $te = explode('-',$arr_fn[2]);
            $fs['time'] = $te[0].':'.$te[1];

            $rows_list_files .=
                Yii::$app->view->renderFile(
                    '@app/views/ajax/shortcodes/list_excel_files.php',$fs);
        }

        return $this->render('import-files',[
            'alerts' => $this->renderAjax('shortcodes/alerts'),
            'list_files'  => $rows_list_files,
            'count_files' => $count_files,
        ]);
    }

    /**
     * Страница "Выгрузка этикетки".
     *
     * @return string
     */
    public function actionUnloadingLabels()
    {

//        require_once $_SERVER['DOCUMENT_ROOT'].'/backend/libraries/barcode/BarcodeImage.php';

        return $this->render('unloading-labels',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty' => str_replace('%section%','',$this->renderAjax('shortcodes/tr-empty')),
            'tpl'=>$this->renderAjax('shortcodes/unl-tpl'),
            'js_tpl' => $this->renderAjax('shortcodes/js-tpl-unloading-labels'),
        ]);
    }

    /**
     * Страница "Загрузка файлов CDB".
     * CDB - Clean DB
     *
     * @return string
     */
    public function actionExportExcelCDB()
    {

        $list_files = FilesExcel::find()->all();
        $rows_list_files = '';
        $count_files = 0;
        foreach($list_files as $file){
            $count_files++;
            $fs['id'] = $file['id'];
            $fs['name'] = $file['name'];
            $fs['full_name'] = $file['name'].'.'.$file['ext'];
            $arr_fn = explode('__', $file['name']);
            // $arr_fn[1]: 2018-12-18_16-37-38
            $dt = explode('_', $arr_fn[1]);
            $fs['date'] = $dt[0];
            $te = explode('-', $dt[1]);
            $fs['time'] = $te[0] . ':' . $te[1];

            $rows_list_files .=
                Yii::$app->view->renderFile(
                    '@app/views/ajax/shortcodes/list_excel_files.php',$fs);
        }

        return $this->render('export-excel-c-d-b',[
            'alerts' => $this->renderAjax('shortcodes/alerts'),
            'list_files'  => $rows_list_files,
            'count_files' => $count_files,
        ]);
    }

    /**
     * Страница "Товарный учет"
     */
    public function actionCommodityAccounting()
    {
        $document_options = '';
        /*
         * Из таблицы document
         * делаем выборку за текущий месяц
         */
        $documents = Document::find()
            ->select(['document_type','id','document_date'])
            ->where(['like','document_date',date('Y-m',time())])
            ->andWhere([
                'document_type'=>['04','05','06','07'],
                'document_correction_code'=>null
            ])->asArray()->all();
        if(count($documents)) {

            /*
             * Добавляем два элемента option
             * в начало массива
             */
            array_unshift($documents, ['id' => ''], ['id' => 'new']);

            for ($i = 0; $i < count($documents); $i++) {
                /*
                 * В атрибут value
                 * вставляем тип документа
                 */
                $attr['value'] = $documents[$i]['id'];

                switch ($documents[$i]['id']) {
                    case '':
                        $string = 'Выберите документ';
                        break;
                    case 'new':
                        $string = 'Добавить новый';
                        break;
                    default:

                        /*
                         * Из таблицы document_type
                         * выбираем поле name по номеру документа (code)
                         * переписываем основной массив
                         * и вместо номера документа,
                         * подставляем наименование документа
                         */
                        $dock_type = $documents[$i]['document_type'];
                        $documents[$i]['document_type'] =
                            DocumentType::find()
                                ->where(['code' => $dock_type])
                                ->asArray()->one()['name'];

                        /*
                         * Дополняем массив форматом даты
                         * которая будет выводится на экран
                         */
                        $documents[$i]['date_view'] = d::changeDate(
                            $documents[$i]['document_date'],
                            'format', 'dd.mm.yyyy'
                        );

                        /*
                         * В атрибут data-date
                         * вставляем дату документа
                         */
                        $attr['data-date'] = $documents[$i]['document_date'];
                        $attr['data-type'] = $dock_type;

                        $string = $documents[$i]['document_type']
                            . ' № ' . $documents[$i]['id']
                            . ' от ' . $documents[$i]['date_view'];
                }

                $document_options .=
                    Yii::$app->view->renderFile(
                        '@app/views/ajax/shortcodes/options_list.php', [
                        'attributes' => BaseHtml::renderTagAttributes($attr),
                        'string' => $string,
                    ]);
            }

            $document_style = '
                border: 1px solid rgba(77,193,0,.5);
                color:rgba(68,169,0,1);';

        }else{

            $document_options .= Yii::getAlias('@documents_not_found');

            $document_style = '
                border: 1px solid rgba(0,0,0,.2);
                color:#55595c;';
        }

        return $this->render('commodity-accounting',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty'  => $this->renderAjax('shortcodes/tr-empty'),
            'document_options' => $document_options,
            'block1' => $this->renderAjax('shortcodes/block1'),
            'js_tpl' => $this->renderAjax('shortcodes/js-tpl-commodity-accounting'),
            'but' => $this->renderAjax('shortcodes/but'),
            'document_style' => $document_style,
        ]);
    }

    /**
     * Страница "Заказы".
     *
     * @return string
     */
    public function actionOrders()
    {
        return $this->render('orders',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'tr_empty' => str_replace('%section%','',$this->renderAjax('shortcodes/tr-empty')),
        ]);
    }

































    /**
     * Страница "Отправка Email".
     *
     * @return string
     */
    public function actionEmail()
    {
        return $this->render('email');
    }

    /**
     * Страница "Тестирования KKM".
     *
     * @return string
     */
    public function actionKkm()
    {
        return $this->render('kkm');
    }

    /**
     * Страница "Для разработки".
     *
     * @return string
     */
    public function actionDebugg()
    {
        $arr_value = false;
        $arr_value = [];
        $arr_value['img'] = '';




//        $update = Yii::$app->db->createCommand()
//            ->update('certificates', [
//                'cooked' => '1',
//                'date_of_digestion' => Yii::$app->getFormatter()->asDate(time()),
//                'document_id_digestion' => $last_insert_id_document
//            ], ['barcode' => $arr_todb_certificates4]);
//        try {
//            $update->execute();
//        }catch (Exception $e){
//            $result['errors'] .= d::getMessage('SECTION_4_ERROR');
//        }

        return $this->render('debugg', [
            'alerts' => $this->renderAjax('shortcodes/alerts'),
            'options' => $arr_value
        ]);
    }

    /**
     * Страница "Для разработки".
     *
     * @return string
     */
    public function actionBarcodeImage()
    {
        $arr_value = false;
        $arr_value = '';

        // ===============================

//        require_once $_SERVER['DOCUMENT_ROOT'].
//            '/backend/libraries/barcode/BarcodeImage.php';
//
//        ob_start();
//        $_GET['barcode'] = '0031002000026';
//        BarcodeImage::barcode_print(
//            $_GET['barcode'], $scale = 2 ,$mode = "png", $total_y
//        );
//        $image = ob_get_contents();
//        ob_end_clean();



        // ==============================


        $image = '<img src="'.$_SERVER['DOCUMENT_ROOT'].
    '/backend/libraries/barcode/BarcodeImage.php" alt="barcode" />';

//        echo $image;




        $image = $this->renderAjax('barcode-image');


        $arr_value['img'] = $image;
////        d::pre();
        return $this->render('debugg',[
            'alerts'    => $this->renderAjax('shortcodes/alerts'),
            'options'=>$arr_value
        ]);
    }

    /**
     * Страница "Сайт отключен".
     *
     * @return string,
     */
    public function actionOffline()
    {
        return $this->render('offline');
    }




    public function beforeAction($action)
    {
        // Получение количества заказов
        if($orders = GetData::getOrders()){
            Yii::$app->getView()->params['count_orders'] = count($orders);
        }

        return parent::beforeAction($action);
    }

}// Class
