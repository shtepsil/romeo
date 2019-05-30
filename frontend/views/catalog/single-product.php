<?php

use backend\controllers\MainController as d;
use \yii\helpers\Html;
use frontend\components\CustomBreadcrumbs as CBreadcrumbs;
use yii\helpers\Url;

$this->title = ($pn['labeling'])?$pn['labeling']:'Описание товара';

$this->params['breadcrumbs'][] = [
    'label' => $menu1['name'],
    'coption' => [
        'serial_number' => '2',
    ]
];

if($menu3){
    $this->params['breadcrumbs'][] = [
        'label' => $menu2['name'],
        'coption' => [
            'serial_number' => '3',
        ]
    ];
}
else{
    $this->params['breadcrumbs'][] = [
        'template' => "<li><span itemscope=\"\" itemprop=\"itemListElement\" itemtype=\"http://schema.org/ListItem\"><b>{link}</b></span></li>\n", //  шаблон для этой ссылки
        'label' => $menu2['name'], // название ссылки
        'url' => [Yii::$app->request->serverName.'/catalog?id='.Yii::$app->request->get()['id'].'&cgc='.Yii::$app->request->get()['cgc'].'&level_id='.Yii::$app->request->get()['level_id']], // сама ссылка
        'itemprop' => 'item',
        'coption' => [
            'serial_number' => '3',
        ]
    ];
}
if($menu3) $this->params['breadcrumbs'][] = [
    'template' => "<li><span itemscope=\"\" itemprop=\"itemListElement\" itemtype=\"http://schema.org/ListItem\"><b>{link}</b></span></li>\n", //  шаблон для этой ссылки
    'label' => $menu3['name'], // название ссылки
    'url' => [Yii::$app->request->serverName.'/catalog?id='.Yii::$app->request->get()['id'].'&cgc='.Yii::$app->request->get()['cgc'].'&level_id='.Yii::$app->request->get()['level_id']], // сама ссылка
    'itemprop' => 'item',
    'coption' => [
        'serial_number' => '4',
    ]
];
if($pn['labeling']) $this->params['breadcrumbs'][] = $pn['labeling'];

/*
 * Если есть автоматическая скидка
 * Вычисляем цену со скидкой
 */
if($pp['automatic_discount'] != 0){
    $price_discount = $pp['retail_price']-(($pp['retail_price']/100)*$pp['automatic_discount']);
    $price = $pp['retail_price'];
    $discount = $pp['automatic_discount'];
}else{
    $price_discount = $pp['retail_price'];
    $price = $pp['retail_price'];
    $discount = $pp['automatic_discount'];
}

//d::pri($adm_info);

// sept - single-product
?>

<div class="sept">
<input type="hidden" name="type_basket" value="sept" />
    <?if(!$pn OR !Yii::$app->request->get()['product_id']):?>

        <br>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center" style="min-height: 500px;">
                    <div class="heading-title heading-style pos-rltv mb-50 text-center">
                        <h5 class="uppercase">Нет такого товара</h5>
                    </div>
                </div>
            </div>
        </div>

    <?else:?>

        <!--breadcumb area start -->
        <div class="breadcumb-area overlay pos-rltv">
            <div class="bread-main">
                <div class="bred-hading text-center dn">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
                <?=CBreadcrumbs::widget([
                    'homeLink' => [
                        'label' => 'Главная',
                        'coption' => [
                            'serial_number' => '1',
                        ],
                        'url' => '/',
                        'itemprop' => 'item',
                        'template' => '<li><span itemscope="" itemprop="itemListElement" itemtype="http://schema.org/ListItem">{link}</span></li>'
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

        <!--single-protfolio-area are start-->
        <div class="single-protfolio-area ptb-70">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="portfolio-thumbnil-area">
                            <div class="product-more-views">
                                <div class="tab_thumbnail" data-tabs="tabs">
                                    <div class="thumbnail-carousel">
                                        <ul>
        <?if(count($pn['photos'])){$i=0;foreach($pn['photos'] as $key=>$img):?>
        <li class="<?=($i==0)?'active':''?>">
            <a href="#view<?=$key?>" class="shadow-box" aria-controls="view<?=$key?>" data-toggle="tab">
                <img src="<?=$img['path']?>" alt="" />
            </a>
        </li>
        <?$i++;endforeach;}?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content active-portfolio-area pos-rltv">
                                <div class="social-tag dn">
                                    <a href="#"><i class="zmdi zmdi-share"></i></a>
                                </div>

        <?if(count($pn['photos'])){$i=0;foreach($pn['photos'] as $key=>$img):?>
        <div role="tabpanel" class="tab-pane <?=($i==0)?'active':''?>" id="view<?=$key?>">
            <div class="product-img">
                <a class="fancybox" data-fancybox-group="group" href="<?=$img['path']?>">
                    <img src="<?=$img['path']?>" alt="Single portfolio" class="img-single-product" data-img-name="<?=$img['name']?>" />
                </a>
            </div>
        </div>
        <?$i++;endforeach;}else{?>
            <div role="tabpanel" class="tab-pane active" id="">
                <div class="product-img">
                    <a class="fancybox" data-fancybox-group="group" href="<?=Yii::getAlias('@web').'/images/product/01.jpg'?>">
                        <?=Html::img('@web/images/product/01.jpg',['alt'=>'Single portfolio','class'=>'img-single-product','data-img-name'=>'img_default'])?>
                    </a>
                </div>
            </div>
        <?}?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="single-product-description">
                            <div class="sp-top-des">
                                <h3 class="label-product" data-barcode="<?=($pp)?$pp['barcode']:''?>"><?=$pn['labeling']?></h3>
                                <div class="prodcut-ratting-price">
                                    <?php /*
                                    <div class="prodcut-ratting">
                                        <a href="#" tabindex="0"><i class="fa fa-star-o"></i></a>
                                        <a href="#" tabindex="0"><i class="fa fa-star-o"></i></a>
                                        <a href="#" tabindex="0"><i class="fa fa-star-o"></i></a>
                                        <a href="#" tabindex="0"><i class="fa fa-star-o"></i></a>
                                        <a href="#" tabindex="0"><i class="fa fa-star-o"></i></a>
                                    </div>
                                    */ ?>
                                    <div class="prodcut-price">

                                            <div class="double-price <?=($pp['automatic_discount'] != 0)?'':'dn'?>" data-visible="<?=($pp['automatic_discount'] != 0)?'visible':''?>">
                                                <div class="new-price">
                                                    <?=Html::img('@web/images/icons/rub.png',['alt'=>'Рублей'])?>
                                                    <div class="price"><?=number_format($price_discount,0,'','')?></div>
                                                </div>
                                                <div class="old-price">
                                                    <del><?=($pp)?number_format($price,0,'',''):'0'?></del>
                                                </div>
                                                <div class="discount">
                                                    (Скидка: <span><?=$discount?></span>%)
                                                </div>
                                            </div>

                                            <div class="single-price <?=($pp['automatic_discount'] != 0)?'dn':''?>" data-visible="<?=($pp['automatic_discount'] != 0)?'':'visible'?>">
                                                <div class="new-price">
                                                    <?=Html::img('@web/images/icons/rub.png',['alt'=>'Рублей'])?>
                                                    <div class="price"><?=number_format($price,0,'','')?></div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                            </div>

                            <div class="sp-des">
                                <p><?=$pn['product_description_on_the_site']?></p>
                            </div>
                            <div class="sp-bottom-des">
                                <div class="single-product-option">
                                    <div class="sort product-type <?=(!$sizes)?'dn':''?>">
                                        <label>Размеры: </label>
                                        <?//=$pn['size']?>

                                        <ul class="size-blocks">
                                            <?if($sizes):foreach($sizes as $size):?>
                                            <li><button data-selected="" data-barcode="<?=$size['barcode']?>"><?=$size['size']['name']?></button></li>
                                            <?endforeach;else:?>
                                                <li><button disabled="disabled">Размеров нет</button></li>
                                            <?endif;?>
                                        </ul>
                                    </div>
                                    <?php /*
                                    <div class="sort product-type">
                                        <label>Size: </label>
                                        <select id="input-sort-size">
                                            <option value="#">S</option>
                                            <option value="#">M</option>
                                            <option value="#">L</option>
                                            <option value="#">XL</option>
                                            <option value="#">XXL</option>
                                            <option value="#" selected="">Chose Your Size</option>
                                        </select>
                                    </div>
                                    */ ?>
                                </div>
                                <?php /*
                                <div class="quantity-area">
                                    <label>Qty :</label>
                                    <div class="cart-quantity">
                                        <form action="#" method="POST" id="myform">
                                            <div class="product-qty">
                                                <div class="cart-quantity">
                                                    <div class="cart-plus-minus">
                                                        <div class="dec qtybutton">-</div>
                                                        <input type="text" value="02" name="qtybutton" class="cart-plus-minus-box">
                                                        <div class="inc qtybutton">+</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                */ ?>

                                <div class="social-icon socile-icon-style-1">
                                    <ul>
                                        <li>
                                            <?if($price):?>
                                            <button
                                                type="button"
                                                class="add-cart add-cart-text"
                                                data-placement="left"
                                                tabindex="0"
                                                data-url="/ajax/add-to-card"
                                                method="post"
                                            >
                                                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                                Добавить в корзину
                                                <i class="fa fa-cart-plus"></i>
                                            </button>
                                            <?endif;?>
                                        </li>

                                        <?php /*
                                        <li><a href="#" data-tooltip="Wishlist" class="w-list" tabindex="0"><i class="fa fa-heart-o"></i></a></li>
                                        <li><a href="#" data-tooltip="Compare" class="cpare" tabindex="0"><i class="fa fa-refresh"></i></a></li>
                                        <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="fa fa-eye"></i></a></li>
                                        */ ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--single-protfolio-area are start-->

        <?if(!Yii::$app->user->isGuest):?>
            <?if(count($adm_info)):?>

    <!-- admin info -->
    <div class="descripton-area">
        <div class="container">
            <div class="row">
                <div class="product-area tab-cars-style">
                    <div class="title-tab-product-category">
                        <div class="col-md-8 col-md-offset-2">

                <h3 class="text-center">Информация для администратора</h3>


                <div class="adm_table">
                    <table class="table table-bordered">
                        <?foreach($adm_info as $item):?>
                            <tr>
                                <td><?=$item['content']?></td>
                                <td><?=$item['value']?></td>
                            </tr>
                        <?endforeach;?>
                    </table>
                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /admin info -->

            <?endif?>
        <?endif?>

        <!--descripton-area start -->
        <div class="descripton-area dn">
            <div class="container">
                <div class="row">
                    <div class="product-area tab-cars-style">
                        <div class="title-tab-product-category">
                            <div class="col-md-12 text-center">
                                <ul class="nav mb-40 heading-style-2" role="tablist">
                                    <li role="presentation"><a href="#newarrival" aria-controls="newarrival" role="tab" data-toggle="tab">Описание</a></li>

                                    <?php /*
                                    <li role="presentation" class="active"><a href="#bestsellr" aria-controls="bestsellr" role="tab" data-toggle="tab">Review</a></li>
                                    <li role="presentation"><a href="#specialoffer" aria-controls="specialoffer" role="tab" data-toggle="tab">Tags</a></li>

                                    */ ?>
                                </ul>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12">
                            <div class="content-tab-product-category">
                                <!-- Tab panes -->
                                <div class="tab-content">

                                    <div role="tabpanel" class="tab-pane fix fade in active" id="newarrival">
                                        <div class="review-wraper">
                                            <p><?=$pn['product_description_on_the_site']?></p>
                                        </div>
                                    </div>

        <?php /*

                                    <div role="tabpanel" class="tab-pane fix fade in" id="bestsellr">
                                        <div class="review-wraper">
                                            <p>Lorem2 ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim <br> veniam, quis nostrud exercitation.</p>
                                            <h5>SIZE & FIT</h5>
                                            <ul>
                                                <li>Model wears: Style Photoliya U2980</li>
                                                <li>Model's height: 185”66</li>
                                            </ul>
                                            <h5>ABOUT ME</h5>
                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English</p>
                                            <h5>Overview</h5>
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
                                        </div>
                                    </div>

                                    <div role="tabpanel" class="tab-pane fix fade in" id="specialoffer">
                                        <ul class="tag-filter">
                                            <li><a href="#">Fashion</a></li>
                                            <li><a href="#">Women</a></li>
                                            <li><a href="#">Winter</a></li>
                                            <li><a href="#">Street Style</a></li>
                                            <li><a href="#">Style</a></li>
                                            <li><a href="#">Shop</a></li>
                                            <li><a href="#">Collection</a></li>
                                            <li><a href="#">Spring 2016</a></li>
                                            <li><a href="#">Street Style</a></li>
                                            <li><a href="#">Style</a></li>
                                            <li><a href="#">Shop</a></li>
                                        </ul>
                                    </div>

                                    */ ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--descripton-area end-->
        <br>
        <br>
        <br>

    <?endif;?>
</div>
