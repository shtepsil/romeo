<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use backend\controllers\MainController as d;
use yii\helpers\Html;

$this->title = $name;
?>
<!--breadcumb area start -->
<div class="breadcumb-area breadcumb-3 overlay pos-rltv">
    <div class="bread-main">
        <div class="bred-hading text-center">
            <h5>About Details</h5> </div>
        <ol class="breadcrumb">
            <li class="home"><a title="Go to Home Page" href="index.html">Home</a></li>
            <li class="active">About Us</li>
        </ol>
    </div>
</div>
<!--breadcumb area end -->

<!-- about-us-area start-->
<div class="about-us-area ptb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase"><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            <div class="about-us-wrap">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="about-des">
                        <p><?=$name?></p>
                        <p><?=$message?></p>
                        <p><?$exception?></p>
                        <p style="font-size: 18px;"><code>Страница ошибки представления site, файл error.php</code></p>
                        <a href="/" class="btn-def small" tabindex="0">На главную</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- about-us-area end-->
