<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'sourceLanguage' => 'en',
    'language' => 'ru-RU',
    /*
     * Таймзона кемеровской области
     * после установки сайта на сервер - надо проверить
     * правильно ли показывает время, и если что подкорректировать.
     */
    'timeZone' => 'Asia/Novokuznetsk',
    'aliases' => [
        // Домен сайта
        '@site' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'],

        // для функции number_format
        /*
         * // количество знаков "копеек" kopecks
         * Когда из БД приходит сумма с нулями копеек,
         * но нужна без нулей (без копеек)
         */
        '@ko0' => 0,
        // количество знаков "копеек" kopecks
        '@ko' => 2,
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

        // Имя файла для экспорта реестра в excel
        '@export_registry' => '/common/files/excel/export/export_registry.xls',
        // Имя файла для экспорта этикеток в excel
        '@export_labels' => '/common/files/excel/export/export_labels.xls',
        /*
         * Шаблон пустой строки tr
         * переменную можно получить и в PHP
         * и в JS
         * Один шаблон для всех частей сайта
         */
        '@tr_empty' => '<tr class="empty"><td colspan="16">Пока пусто</td></tr>',
        '@no_size' => '0 без размера',
        '@documents_not_found' =>
            '<option value="">Выберите документ</option>
             <option value="new">Добавить новый</option>',
        '@begin_year' => '2019',
//        '',
    ],
    'components' => [
        'formatter' => [
            'dateFormat' => 'Y-MM-dd',
            'timeFormat' => 'H_mm_ss',
            'datetimeFormat' => 'dd.MM.Y H:mm',
        ],
        'request' => [
            'cookieValidationKey' => $params['cookieValidationKey'],
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
//            'enableCsrfValidation' => true,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
			'class' => 'app\components\User', // extend User component
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced',
            'cookieParams' =>[
                'httpOnly' => true,
            ]
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
//            'maxSourceLines' => 5,
        ],
        'urlManager' => [
            'enablePrettyUrl' =>true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<action>' => 'site/<action>',
//                '<action>' => 'ajax/<action>',
            ],
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>['http://code.jquery.com/jquery-latest.min.js']
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
