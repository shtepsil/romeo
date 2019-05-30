<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AdminAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use \yii\widgets\Menu;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use \backend\controllers\MainController as d;
use \backend\phpexcel\PHPExcel\PHPExcel_IOFactory;
use \backend\libraries\barcode\BarcodeImage;

$this->title = 'Отладка';

//d::pri($options);

?>
<div class="wrap debug">
    <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

    <div class="h6">Заголовок</div>
    <br>

    <button
        type="button"
        class="debug"
        name="exp_excel"
        data-url="ajax/debug"
        data-type="post"
    >Экспорт Excel</button>

    <br>
    <br>

    <?=$alerts?>

    <br>
    <br>

    <div class="res">res</div>

    <br>
    <br>
    <br>
    <br>

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

</div>
