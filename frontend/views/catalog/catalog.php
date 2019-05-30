<?php

use backend\controllers\MainController as d;
use yii\helpers\Html;
use frontend\components\CustomBreadcrumbs as CBreadcrumbs;

$this->title = 'Каталог';

//d::pri(Yii::$app->request->get()['level_id']);

//d::pri(d::objectToArray($menu2));
//d::pre(Yii::$app->request->pathInfo);

$session = Yii::$app->session;

$this->params['breadcrumbs'][] = [
    'label' => $menu1['name'],
    'coption' => [
        'serial_number' => '2',
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => $menu2['name'],
    'coption' => [
        'serial_number' => '3',
    ]
];
if($menu3) {
    $this->params['breadcrumbs'][] = [
        'label' => $menu3['name'],
        'coption' => [
            'serial_number' => '4',
        ]
    ];
}

?>
<!--breadcumb area start -->
<div class="breadcumb-area breadcumb-2 overlay pos-rltv">
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

<!--        <ol class="breadcrumb">-->
<!--            <li class="home"><a title="Go to Home Page" href="index.html">Home</a></li>-->
<!--            <li class="active">Shop</li>-->
<!--        </ol>-->


    </div>
</div>
<!--breadcumb area end -->

<?if(!$ptne OR !$_GET['cgc']):?>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-xs-12 text-center" style="min-height: 500px;">
            <div class="heading-title heading-style pos-rltv mb-50 text-center">
                <h5 class="uppercase">Каталог пока пуст</h5>
            </div>
        </div>
    </div>
</div>
<?else:?>
<!--shop main area are start-->
<div class="shop-main-area ptb-70">
    <div class="container">
        <div class="row">
<?php /* // Фильтры. Левый SideBar
            <!--shop sidebar start-->
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="row">
                    <div class="shop-sidebar">
                        <!--single aside start-->
                        <aside class="single-aside search-aside search-box">
                            <form action="#">
                                <div class="input-box">
                                    <input class="single-input" placeholder="Search...." type="text">
                                    <button class="src-btn sb-2"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </aside>
                        <!--single aside end-->

                        <!--single aside start-->
                        <aside class="single-aside catagories-aside">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">categories</h5>
                            </div>
                            <div id="cat-treeview" class="product-cat">
                                <ul>
                                    <li class="closed"><a href="#">Men (05)</a>
                                        <ul>
                                            <li><a href="#">T-Shirt</a></li>
                                            <li><a href="#">Shirt</a></li>
                                            <li><a href="#">Pant</a></li>
                                            <li><a href="#">Shoe</a></li>
                                            <li><a href="#">Gifts</a></li>
                                        </ul>
                                    </li>
                                    <li class="closed"><a href="#">Women (10)</a>
                                        <ul>
                                            <li><a href="#">T-Shirt</a>
                                                <ul>
                                                    <li><a href="#">T-Shirt 01</a></li>
                                                    <li><a href="#">T-Shirt 02</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Shirt</a>
                                                <ul>
                                                    <li><a href="#">Shirt 01</a></li>
                                                    <li><a href="#">Shirt 02</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Pant</a>
                                                <ul>
                                                    <li><a href="#">Pant 01</a></li>
                                                    <li><a href="#">Pant 02</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Shoe</a>
                                                <ul>
                                                    <li><a href="#">Shoe 01</a></li>
                                                    <li><a href="#">Shoe 02</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Gifts</a>
                                                <ul>
                                                    <li><a href="#">Gift 01</a></li>
                                                    <li><a href="#">Gift 02</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="closed"><a href="#">Accessories (07)</a>
                                        <ul>
                                            <li><a href="#">Accessories 01</a></li>
                                            <li><a href="#">Accessories 02</a></li>
                                            <li><a href="#">Accessories 03</a></li>
                                        </ul>
                                    </li>
                                    <li class="closed"><a href="#">Beauty (06)</a>
                                        <ul>
                                            <li><a href="#">Beauty 01</a></li>
                                            <li><a href="#">Beauty 02</a></li>
                                            <li><a href="#">Beauty 03</a></li>
                                        </ul>
                                    </li>
                                    <li class="closed"><a href="#">Watch (09)</a>
                                        <ul>
                                            <li><a href="#">Watch 01</a></li>
                                            <li><a href="#">Watch 02</a></li>
                                            <li><a href="#">Watch 03</a></li>
                                        </ul>
                                    </li>
                                    <li class="closed"><a href="#">Sports</a></li>
                                    <li class="closed"><a href="#">Others</a></li>
                                </ul>
                            </div>
                        </aside>
                        <!--single aside end-->

                        <!--single aside start-->
                        <aside class="single-aside price-aside fix">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">price</h5>
                            </div>
                            <div class="price_filter">
                                <div id="slider-range"></div>
                                <div class="price_slider_amount">
                                    <input type="text" id="amount" name="price" placeholder="Add Your Price" />
                                    <input type="submit" value="Filter" />
                                </div>
                            </div>
                        </aside>
                        <!--single aside end-->

                        <!--single aside start-->
                        <aside class="single-aside color-aside">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">Color</h5>
                            </div>
                            <ul class="color-filter mt-30">
                                <li><a href="#" class="color-1"></a></li>
                                <li><a href="#" class="color-2 active"></a></li>
                                <li><a href="#" class="color-3"></a></li>
                                <li><a href="#" class="color-4"></a></li>
                                <li><a href="#" class="color-5"></a></li>
                                <li><a href="#" class="color-6"></a></li>
                                <li><a href="#" class="color-7"></a></li>
                                <li><a href="#" class="color-8"></a></li>
                                <li><a href="#" class="color-9"></a></li>
                            </ul>
                        </aside>
                        <!--single aside end-->

                        <!--single aside start-->
                        <aside class="single-aside size-aside">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">Size Option</h5>
                            </div>
                            <ul class="size-filter mt-30">
                                <li><a href="#" class="size-s">S</a></li>
                                <li><a href="#" class="size-m">M</a></li>
                                <li><a href="#" class="size-l">L</a></li>
                                <li><a href="#" class="size-xl">XL</a></li>
                                <li><a href="#" class="size-xxl">XXL</a></li>
                            </ul>
                        </aside>

                        <!--single aside start-->
                        <aside class="single-aside tag-aside">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">Product Tags</h5>
                            </div>
                            <ul class="tag-filter mt-30">
                                <li><a href="#">Fashion</a></li>
                                <li><a href="#">Women</a></li>
                                <li><a href="#">Winter</a></li>
                                <li><a href="#">Street Style</a></li>
                                <li><a href="#">Style</a></li>
                                <li><a href="#">Shop</a></li>
                                <li><a href="#">Collection</a></li>
                                <li><a href="#">Spring 2016</a></li>
                            </ul>
                        </aside>
                        <!--single aside end-->

                        <!--single aside start-->
                        <aside class="single-aside product-aside">
                            <div class="heading-title aside-title pos-rltv">
                                <h5 class="uppercase">Recent Product</h5>
                            </div>
                            <div class="recent-prodcut-wraper total-rectnt-slider">
                                <div class="single-rectnt-slider">
                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp01.jpg" class="primary-image"> <img alt="" src="images/product/rp02.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->

                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp03.jpg" class="primary-image"> <img alt="" src="images/product/rp04.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->

                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp02.jpg" class="primary-image"> <img alt="" src="images/product/rp03.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->

                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp04.jpg" class="primary-image"> <img alt="" src="images/product/rp01.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="single-rectnt-slider">
                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp01.jpg" class="primary-image"> <img alt="" src="images/product/rp02.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->

                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp03.jpg" class="primary-image"> <img alt="" src="images/product/rp04.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->

                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp02.jpg" class="primary-image"> <img alt="" src="images/product/rp03.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->

                                    <!-- single product start-->
                                    <div class="single-product recent-single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/rp04.jpg" class="primary-image"> <img alt="" src="images/product/rp01.jpg" class="secondary-image"> </a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Copenhagen Spitfire Chair</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting"> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star"></i></a> <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                            </div>

                        </aside>
                        <!--single aside end-->

                        <!--single aside start-->
                        <aside class="single-aside add-aside">
                            <a href="single-product.html"><img src="images/banner/add.jpg" alt=""></a>
                        </aside>
                        <!--single aside end-->
                    </div>
                </div>
            </div>
            <!--shop sidebar end-->
*/ ?>
            <!--main-shop-product start-->
            <?php // <div class="col-md-9 col-sm-8 col-xs-12"> ?>
            <div class="col-md-12">
                <p>Друзья, с 01 мая 2019 мы запустили новый сайт!<br>
                    Каталог находится на стадии наполнения и сейчас здесь представлена лишь малая часть нашего ассортимента!<br>
                    Приносим извинения за временные неудобства, мы почти каждый день выкладываем новые позиции на сайт и скоро Вы сможете увидеть полный каталог.<br>
                    Приглашаем Вас в ТД Ромео, мы убеждены - только "in real life" можно реально (smile) хорошо подобрать мужской костюм и другие предметы гардероба и у нас есть все для этого!<br>
                    Мы рады будем ответить на Ваши вопросы, пожалуйста звоните нам, телефон торгового зала +7 (908) 930-03-30, Кемерово, проспект Советский, 35</p>

                <br>
                <br>

            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="shop-wraper">
                    <?php /* // Фильтр TopListProduct
                    <div class="col-xs-12">
                        <div class="shop-area-top">
                            <div class="row">
                                <div class="col-lg-6 col-md-9 col-sm-9 col-xs-12">
                                    <div class="sort product-show">
                                        <label>View</label>
                                        <select id="input-amount">
                                            <option value="volvo">10</option>
                                            <option value="saab">15</option>
                                            <option value="vw">20</option>
                                            <option value="audi" selected>25</option>
                                        </select>
                                    </div>
                                    <div class="sort product-type">
                                        <label>Sort By</label>
                                        <select id="input-sort">
                                            <option value="#" selected>Default</option>
                                            <option value="#">Name (A - Z)</option>
                                            <option value="#">Name (Z - A)</option>
                                            <option value="#">Price (Low &gt; High)</option>
                                            <option value="#">Price (High &gt; Low)</option>
                                            <option value="#">Rating (Highest)</option>
                                            <option value="#">Rating (Lowest)</option>
                                            <option value="#">Model (A - Z)</option>
                                            <option value="#">Model (Z - A)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="list-grid-view text-center">
                                        <ul class="nav" role="tablist">
                                            <li role="presentation"  class="active"><a href="#grid" aria-controls="grid" role="tab" data-toggle="tab"><i class="zmdi zmdi-widgets"></i></a></li>
                                            <li role="presentation"><a href="#list" aria-controls="list" role="tab" data-toggle="tab"><i class="zmdi zmdi-view-list-alt"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-3 hidden-md hidden-sm hidden-xs">
                                    <div class="total-showing text-right">
                                        Showing - <span>10</span> to <span>18</span>  Of  Total <span>36</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    */ ?>
                    <div class="clearfix"></div>
                    <div class="shop-total-product-area clearfix mt-35">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!--tab grid are start-->
                            <div role="tabpanel" class="tab-pane fade in active" id="grid">
                                <div class="total-shop-product-grid">

                                    <?php foreach($ptne as $item):

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


<!--<li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>-->
<!--<li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>-->
<!--<li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>-->
<!--<li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>-->
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

                            <!--pagination start-->
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="pagination-btn text-center">

                                    <?=$pagination?>

                                    <?php /*
                                    <!-- Пагинация шаблона сайта -->
                                    <ul class="page-numbers">
                                        <li>
                                            <a href="#" class="next page-numbers">
                                                <i class="zmdi zmdi-long-arrow-left"></i>
                                            </a>
                                        </li>
                                        <li><span class="page-numbers current">1</span></li>
                                        <li><a href="#" class="page-numbers">2</a></li>
                                        <li><a href="#" class="page-numbers">3</a></li>
                                        <li>
                                            <a href="#" class="next page-numbers">
                                                <i class="zmdi zmdi-long-arrow-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    */ ?>

                                </div>
                            </div>
                            <!--pagination end-->
                        </div>
                    </div>
                </div>
            </div>
            <!--main-shop-product start-->
        </div>
    </div>
</div>
<!--shop main area are end-->
<?endif;?>