<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use Yii;
use yii\helpers\Json;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/jquery.jgrowl.css',
        'css/core.css',
        'css/shortcode/shortcodes.css',
        'css/site.css',
        'css/responsive.css',
        'css/custom.css',
        'css/color/skin-default.css',
    ];
    public $js = [
        /*
         * JQuery подключена в config/main.php
         * components => assetManager
         */
        'js/jquery.session.js',
        'js/jquery.cookie.js',
        'js/jquery.jgrowl.min.js',
        'js/jquery.masked.min.js',
        'js/bootstrap.min.js',
        'js/slider/jquery.nivo.slider.pack.js',
        'js/slider/nivo-active.js',
        'js/jquery.countdown.min.js',
        'js/plugins.js',
        'js/common.js',
//        'js/main.js',
        'js/custom.js',
    ];
    public $jsOptions = [
        'position'=>\yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();
//        // resetting BootstrapAsset to not load own css files
//        \Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
//            'css' => [],
//            'js' => []
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
            "var zero_one = '".Yii::getAlias('@zero_one')."';".
            "var zero = '".Yii::getAlias('@zero')."';".
            "var tr_empty = " . Json::encode(Yii::getAlias('@tr_empty')) . ";".
            "var csrf = '".Yii::$app->request->csrfParam."';",
            \yii\web\View::POS_HEAD
        );
        Yii::$app->view->registerJsFile(
            Yii::getAlias('@web').'/js/main.js',
            ['position' => \yii\web\View::POS_END]
        );

        /**
         * Простой JS скрипт подключаем перед закрывающим тегом </body>
         * чтобы переменные заданные из PHP могли попадать и в обычный JS
         */
        Yii::$app->view->registerJsFile(
            Yii::getAlias('@web').'/js/functions.js',
            ['position' => \yii\web\View::POS_END]
        );
        Yii::$app->view->registerJsFile(
            Yii::getAlias('@web').'/js/vendor/modernizr-2.8.3.min.js',
            ['position' => \yii\web\View::POS_HEAD]
        );
    }
}
