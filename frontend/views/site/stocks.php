<?php

use backend\controllers\MainController as d;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\components\CustomBreadcrumbs as CBreadcrumbs;

$this->title = 'Сегодня акция';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'coption' => [
        'serial_number' => '2',
    ]
];

//d::pri($catalog);

?>
    <!--breadcumb area start -->
    <div class="breadcumb-area breadcumb-2 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center dn">
                <h5><?= Html::encode($this->title) ?></h5> </div>

            <?=CBreadcrumbs::widget([
                'homeLink' => [
                    'label' => 'Главная',
                    'coption' => [
                        'serial_number' => '1',
                    ],
                    'url' => '/',
                    'itemprop' => 'item',
                    'template' => '<li><span itemscope="" itemprop="itemListElement" itemtype="//schema.org/ListItem">{link}</span></li>'
                ],
                'options' => [
                    'class' => 'breadcrumb',
                    'itemscope' => '',
                    'itemtype' => 'http://schema.org/BreadcrumbList'
                ],
                'links' =>
                    isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);?>


        </div>
    </div>
    <!--breadcumb area end -->

    <!-- stocks-area start-->
    <div class="stocks-area ptb-50">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="heading-title heading-style pos-rltv mb-50 text-center">
                        <h5 class="uppercase"><?=Html::encode($this->title)?></h5>
                    </div>
                </div>
                <div class="stocks-wrap">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?=Html::img('@web/images/gift_certificate.jpg',['alt'=>'Изображение'])?>
                        <br>
                        <br>

                        <div class="h4">Подарочный сертификат каждому выпускнику!</div>

                        <p>Ромео дарит подарки всем выпускникам.</p>
                        <p>Купите мужской костюм до 31.05.2019, при покупке предъявите подарочный сертификат и Вы сможете выбрать подарки на сумму 3000 рублей!</p>
                        <p>Выбранные подарки выдаются вместе с основной покупкой без дополнительной оплаты, если сумма подарков не превышает 3000 рублей. Покупатель может выбирать товар из любых товарных групп: брюки, трикотаж, рубашки, галстуки, ремни и галантерея, плащи, пальто, куртки, ветровки, костюмы мужские, белье, носки и т.д., но не более одного изделия из каждой товарной группы.</p>
                        <p>Если Вы еще не получили Подарочный сертификат Ромео, позвоните нам: Торговый дом «Ромео», +7 (908) 930-03-30, г. Кемерово, Советский просп., 35.</p>

                    </div>
                </div>





                <?php /* ================================================
                                    Временный каталог
                =================================================== */ ?>


                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="shop-wraper">

                        <div class="clearfix"></div>
                        <div class="shop-total-product-area clearfix mt-35">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!--tab grid are start-->
                                <div role="tabpanel" class="tab-pane fade in active" id="grid">
                                    <div class="total-shop-product-grid">

                                        <?php foreach($catalog as $item):

                                            // Ссылка на просмотр товара (на страницу: single_product)
                                            $view_product = "/catalog/single-product?id=".Yii::$app->request->get()['id']."&cgc=".Yii::$app->request->get()['cgc']."&level_id=".Yii::$app->request->get()['level_id']."&product_id={$item['id']}";

                                            ?>

                                            <div class="col-md-3 col-sm-6 item">
                                                <!-- single product start-->
                                                <div class="single-product">
                                                    <div class="product-img">
                                                        <?if($item['novelty_of_the_season'] != ''):?>
                                                            <div class="product-label red">
                                                                <div class="new">New</div>
                                                            </div>
                                                        <?endif?>
                                                        <div class="single-prodcut-img  product-overlay pos-rltv">
                                                            <a href="<?=$view_product?>">
                                                                <?$img_preview = ($item['photos'][0])?$item['photos'][0]:'images/product/01.jpg'?>
                                                                <img alt="" src="<?=$img_preview?>" class="primary-image" width="272">
                                                            </a>
                                                        </div>
                                                        <div class="product-icon socile-icon-tooltip text-center">
                                                            <ul>
                                                                <li>
                                                                    <a href="<?=$view_product?>" class="btn btn-primery">
                                                                        Смотреть
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-text">
                                                        <div class="prodcut-name">
                                                            <a href="<?=$view_product?>">
                                                                <?=$item['labeling']?></a>
                                                        </div>
                                                        <?if(0):?>
                                                            <div class="prodcut-ratting-price">
                                                                <div class="prodcut-price">
                                                                    <div class="new-price"> $220 </div>
                                                                    <div class="old-price"> <del>$250</del> </div>
                                                                </div>
                                                            </div>
                                                        <?endif;?>
                                                    </div>
                                                </div>
                                                <!-- single product end-->
                                            </div>

                                        <?endforeach;?>

                                    </div>
                                </div>
                                <!--shop grid are end-->

                            </div>
                        </div>
                    </div>
                </div>


                <?php /* ================================================
                                    /временный каталог
                =================================================== */ ?>







            </div>
        </div>
    </div>
    <!-- stocks-area end-->
