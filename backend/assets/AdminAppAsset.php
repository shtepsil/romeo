<?php

namespace backend\assets;

use yii\web\AssetBundle;
use Yii;
use yii\helpers\Json;

/**
 * Main backend application asset bundle.
 */
class AdminAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/jquery-ui.css',
        'css/jquery.jgrowl.css',
//        'css/dropzone.css',
        'css/debugs.css',
        'css/common.css',
        'css/style.css',
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/jquery.jgrowl.min.js',
//        'js/dropzone.js',
        'js/common.js',
        'js/download_excel_temp.js',
        'js/scripts.js',
    ];
    public $jsOptions = [
        'position'=>\yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',// сам скрипт фреймворка
        // подключает js скрипты ПОСЛЕ моих
//        'yii\bootstrap\BootstrapAsset',// зависимость от бутстрапа
        // подключает js скрипты ПЕРЕД моими
        'yii\bootstrap\BootstrapPluginAsset'
    ];

    public function init()
    {
        parent::init();
//        // resetting BootstrapAsset to not load own css files
//        \Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
////            'css' => [],
////            'js' => []
//        ];

        /*
         * Берем данные из PHP
         * и присваиваем эти данные переменным JS
         * ======================================
         * передаем данные из PHP в JS
         * ---------------------------
         * Строка скрипта подключается полсе всех JS подключений
         * и эти переменные можно получить только внутри $(function){}
         */
        Yii::$app->view->registerJs(
            "var dt = " .
            Json::encode(Yii::$app->params['discount_thresholds']) . ";".
            "var documents_not_found = " .
            Json::encode(Yii::getAlias('@documents_not_found')) . ";".
            "var zero_one = " . Json::encode(Yii::getAlias('@zero_one')) . ";".
            "var zero = " . Json::encode(Yii::getAlias('@zero')) . ";".
            "var zeroz = " . Json::encode(Yii::getAlias('@zero,')) . ";".
            "var codesBP = " . Json::encode(Yii::$app->params['codesBP']) . ";".
            "var codesG = " . Json::encode(Yii::$app->params['codesG']) . ";".
            "var common = " . Json::encode(Yii::getAlias('@common')) . ";".
            "var tr_empty = " . Json::encode(Yii::getAlias('@tr_empty')) . ";".
            "var no_size = " . Json::encode(Yii::getAlias('@no_size')) . ";",
            \yii\web\View::POS_HEAD
        );
        /**
         * Простой JS скрипт подключаем перед закрывающим тегом </body>
         * чтобы переменные заданные из PHP могли попадать и в обычный JS
         */
        Yii::$app->view->registerJsFile(
            Yii::getAlias('@web').'/js/test-kkm.js',
            ['position' => \yii\web\View::POS_END]
        );
        Yii::$app->view->registerJsFile(
            Yii::getAlias('@web').'/js/functions.js',
            ['position' => \yii\web\View::POS_END]
        );
    }
}
