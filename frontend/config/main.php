<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'on beforeRequest' => ['\frontend\components\SlasherRemover', 'run'],
    'id' => 'app-frontend',
    'name' => 'Магазин Ромео',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'sourceLanguage' => 'en',
    'language' => 'ru-RU',
    /*
     * Таймзона кемеровской области
     * после установки сайта на сервер - надо проверить
     * правильно ли показывает время, и если что подкорректировать.
     */
    'timeZone' => 'Asia/Novokuznetsk',
    'aliases' => [
        '@site' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'],
        '@host' => $_SERVER['SERVER_NAME'],
        // для функции number_format
        '@ko' => 2,// количество знаков "копеек" kopecks
        '@fl' => '.',// "знак" между "рублями" и "копейками" FLoat
        '@th' => '',// "знак между сотнями/тысячами THousand
        // =========================
        // значение для нулевых числовых значений
        '@zero' => '0.00',
        '@zero,' => '0,00',
        '@zero_one' => '0',
        /*
         * Строка для поля code в справочниках
         * потому что в справочниках поля code
         * пока не задействованы (кроме бренд|пол|товарная группа)
         */
        '@empty_data_field' => '00',
        '@files_excel' => '@common/files/excel',
        '@photos' => '@common/files/photos',
        '@photos_rel' => '../../../common/files/photos',

        // в каталоге "catalog", количество элементов на странице
        '@catalogItemsPerPage' => 16,
        /*
         * Переменные URL attributes
         */
        // get - commodity_group_code
        '@commodity_group_code' => 'cgc',
        // get - ID номенклатуры
        '@id_nomenclature' => 'id',
        // Строка файла view - catalog.php
        '@catalog' => '/catalog',

        /*
         * Данные таблицы "data_type"
         * если вдруг у какого типа сменится ID
         * то переписать его можно здесь
         */
        '@data_type_email' => '4',// Тип данных "Email"
        '@data_type_confirmed' => '7',// Тип данных "Подтвержден"
        /*
         * Шаблон пустой строки tr
         * переменную можно получить и в PHP
         * и в JS
         * Один шаблон для всех частей сайта
         */
        '@tr_empty' => '<tr class="empty"><td colspan="16">Пока пусто</td></tr>',
    ],
    'components' => [
        'opengraph' => [
            'class' => 'frontend\components\OpenGraph',
        ],
        'formatter' => [
            'dateFormat' => 'Y-MM-dd',
            'timeFormat' => 'H_mm_ss',
            'datetimeFormat' => 'dd.MM.Y H:mm',
        ],
        'request' => [
            'cookieValidationKey' => $params['cookieValidationKey'],
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '/',
//            'enableCsrfValidation' => true,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced',
            'cookieParams' =>[
                'httpOnly' => true,
            ]
//            'class' => 'yii\web\Session',
//            'cookieParams' => ['httponly' => true, 'lifetime' => 3600 * 4],
//            'timeout' => 3600*4,
//            'useCookies' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
//            'suffix' => '.html',
            'rules' => [
                'site' => 'site/index',
                'catalog' => 'catalog/catalog',
            ],
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>['//code.jquery.com/jquery-latest.min.js']
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
            //                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
            //                    'css' => [],
                ],
            ],
        ],
    ],
    'params' => $params,
];
