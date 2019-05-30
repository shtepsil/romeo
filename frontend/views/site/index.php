<?php

use backend\controllers\MainController as d;
use yii\helpers\Html;

//d::prebl(time());

$session = Yii::$app->session;

//d::pre($session['captcha.number']);

//// проверяем что сессия уже открыта
//if ($session->isActive){
//    d::pre('Сессия открыта');
//}else{
//    d::pre('Сессия была открыта ранее');
//
//}

//// открываем сессию
// $session->open();
//
//// закрываем сессию
// $session->close();
//
//// уничтожаем сессию и все связанные с ней данные.
//$session->destroy();


?>
<!--slider area start-->
<div class="slider-area pos-rltv carosule-pagi cp-line">
    <div class="active-slider">
        <div class="single-slider pos-rltv">
            <div class="slider-img"><img src="images/slider/0001.jpg" alt=""></div>
            <div class="slider-content pos-abs">
                <h4 class="dn">Best Collection</h4>
                <h1 class="uppercase pos-rltv underline">Безупречный стиль для торжественных событий</h1>
                <a href="/catalog?cgc=012&level_id=2" class="btn-def btn-white fade-alert">Купить сейчас</a>
            </div>
        </div>
        <div class="single-slider pos-rltv">
            <div class="slider-img"><img src="images/slider/0002.jpg" alt=""></div>
            <div class="slider-content pos-abs">
                <h4 class="dn">Best Collection</h4>
                <h1 class="uppercase pos-rltv underline">Атрибуты успешности, создающие деловой образ</h1>
                <a href="/catalog?cgc=012&level_id=2" class="btn-def btn-white">Купить сейчас</a>
            </div>
        </div>
        <div class="single-slider pos-rltv">
            <div class="slider-img"><img src="images/slider/0003.jpg" alt=""></div>
            <div class="slider-content pos-abs">
                <h4 class="dn">Best Collection</h4>
                <h1 class="uppercase pos-rltv underline">Smart Casual<br>важно подчернуть индивидуальность</h1>
                <a href="/catalog?cgc=012&level_id=2" class="btn-def btn-white">Купить сейчас</a>
            </div>
        </div>
    </div>
</div>
<!--slider area start-->

<br>
<br>

<div class="container content">
    <div class="row">
        <div class="col-md-12">

            <h2>О магазине мужской одежды Ромео</h2>

            <p>Открытие Торгового Дома Ромео в городе Кемерово состоялось в 2006 году и сейчас магазин имеет уже более чем 10 лет успешного опыта работы.</p>

            <p>Название магазина мужской одежды "Ромео" было выбрано не случайно. Слово,
                произошедшее от латинского «идущий в Рим», в применении к мужской
                одежде является отсылкой к стремлению придерживаться элегантного
                современного мужского стиля, законодателями мод в котором в наше время
                по праву считаются итальянские модельеры и дизайнеры одежды.</p>

            <p>Сегодня мы стремимся представить Вашему вниманию ассортимент, охватывающий следующие направления в мужской одежде и сопутствующих товарах: </p>

            <ul class="main-text">
                <li class="">строгий стиль для важных формальных событий, таких как свадьба, защита диплома, выпускной вечер, собеседование, выступление, прием, переговоры, официальные церемонии;</li>
                <li class="">деловой стиль, представляющий то что Вы можете надевать каждый день на престижной работе, можно подразделить на более строгий, где обязательным элементом является пиджак, и менее строгий, где обязательный элементом выступают брюки, но не джинсы;</li>
                <li class=""><span style="font-family:'Arial';">smart casual</span> это качественная одежда, в которой уместно прийти в солидный офис в пятницу, или в кино, на вечер, другие неформальные мероприятия.</li>
                <li class="">а также сопутствующие товары: белье, носки, бельевой трикотаж, одежда для отдыха, аксессуары, кожгалантерея, то есть те вещи, которые необходимы для создания завершенного мужского образа.</li>
            </ul>








        </div>
    </div>
</div>

<br>
<br>
















<?php /*
<!--delivery service start-->
<div class="delivery-service-area ptb-30">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-service shadow-box text-center">
                    <img src="images/icons/garantee.png" alt="">
                    <h5>Money Back Guarantee</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-service shadow-box text-center">
                    <img src="images/icons/coupon.png" alt="">
                    <h5>Gift Coupon</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-service shadow-box text-center">
                    <img src="images/icons/delivery.png" alt="">
                    <h5>Free Shipping</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="single-service shadow-box text-center">
                    <img src="images/icons/support.png" alt="">
                    <h5>24x7 Support</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!--delivery service start-->

<!--branding-section-area start-->
<div class="branding-section-area">
    <div class="container">
        <div class="row">
            <div class="active-slider pos-rltv carosule-pagi cp-line pagi-02">
                <div class="single-slider">
                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
                        <div class="brand-img text-center">
                            <img src="images/team/branding.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                        <div class="brand-content ptb-55">
                            <div class="brand-text color-lightgrey">
                                <h6>New Fashion</h6>
                                <h2 class="uppercase montserrat">Brand Cortta</h2>
                                <h3 class="montserrat">$200.00</h3>
                                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                                <div class="social-icon-wraper mt-35">
                                    <div class="social-icon">
                                        <ul>
                                            <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i></a></li>
                                            <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-repeat"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="brand-timer shadow-box-2 mt-50">
                                <div class="timer-wraper text-center">
                                    <div class="timer">
                                        <div data-countdown="2015/02/01"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="single-slider">
                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
                        <div class="brand-img text-center">
                            <img src="images/team/branding.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                        <div class="brand-content ptb-55">
                            <div class="brand-text color-lightgrey">
                                <h6>New Fashion</h6>
                                <h2 class="uppercase montserrat">Brand Cortta</h2>
                                <h3 class="montserrat">$200.00</h3>
                                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                                <div class="social-icon-wraper mt-35">
                                    <div class="social-icon">
                                        <ul>
                                            <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i></a></li>
                                            <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-repeat"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="brand-timer shadow-box-2 mt-50">
                                <div class="timer-wraper text-center">
                                    <div class="timer">
                                        <div data-countdown="2017/02/01"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="single-slider">
                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
                        <div class="brand-img text-center">
                            <img src="images/team/branding.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                        <div class="brand-content ptb-55">
                            <div class="brand-text color-lightgrey">
                                <h6>New Fashion</h6>
                                <h2 class="uppercase montserrat">Brand Cortta</h2>
                                <h3 class="montserrat">$200.00</h3>
                                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                                <div class="social-icon-wraper mt-35">
                                    <div class="social-icon">
                                        <ul>
                                            <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i></a></li>
                                            <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-repeat"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="brand-timer shadow-box-2 mt-50">
                                <div class="timer-wraper text-center">
                                    <div class="timer">
                                        <div data-countdown="2019/02/01"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--branding-section-area end-->

<!--new arrival area start-->
<div class="new-arrival-area pt-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">New Arrival</h5>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="total-new-arrival new-arrival-slider-active carsoule-btn">
                <div class="col-md-3">
                    <!-- single product start-->
                    <div class="single-product">
                        <div class="product-img">
                            <div class="product-label">
                                <div class="new">New</div>
                            </div>
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="single-product.html"> <img alt="" src="images/product/01.jpg" class="primary-image"> <img alt="" src="images/product/02.jpg" class="secondary-image"> </a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-text">
                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                            <div class="prodcut-ratting-price">
                                <div class="prodcut-price">
                                    <div class="new-price"> $220 </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single product end-->
                </div>
                <div class="col-md-3">
                    <!-- single product start-->
                    <div class="single-product">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="single-product.html"> <img alt="" src="images/product/03.jpg" class="primary-image"> <img alt="" src="images/product/04.jpg" class="secondary-image"> </a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-text">
                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                            <div class="prodcut-ratting-price">
                                <div class="prodcut-price">
                                    <div class="new-price"> $220 </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single product end-->
                </div>
                <div class="col-md-3">
                    <!-- single product start-->
                    <div class="single-product">
                        <div class="product-img">
                            <div class="product-label">
                                <div class="new">Sale</div>
                            </div>
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="single-product.html"> <img alt="" src="images/product/02.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-text">
                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                            <div class="prodcut-ratting-price">
                                <div class="prodcut-ratting">
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star-o"></i></a>
                                </div>
                                <div class="prodcut-price">
                                    <div class="new-price"> $220 </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single product end-->
                </div>
                <div class="col-md-3">
                    <!-- single product start-->
                    <div class="single-product">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="single-product.html"> <img alt="" src="images/product/04.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-text">
                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                            <div class="prodcut-ratting-price">
                                <div class="prodcut-price">
                                    <div class="new-price"> $220 </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single product end-->
                </div>
                <div class="col-md-3">
                    <!-- single product start-->
                    <div class="single-product">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="single-product.html"> <img alt="" src="images/product/05.jpg" class="primary-image"> <img alt="" src="images/product/06.jpg" class="secondary-image"> </a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-text">
                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                            <div class="prodcut-ratting-price">
                                <div class="prodcut-ratting">
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                <div class="prodcut-price">
                                    <div class="new-price"> $220 </div>
                                    <div class="old-price"> <del>$250</del> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single product end-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--new arrival area end-->

<!--banner area start-->
<div class="banner-area pt-70">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="single-banner gray-bg">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="sb-img text-center">
                                <img src="images/banner/02.png" alt="">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="sb-content mt-60">
                                <div class="banner-text">
                                    <h5 class="lato">New Arrival</h5>
                                    <h2 class="montserrat">Grag T- Shirt</h2>
                                    <h3 class="montserrat">$99.99</h3>
                                    <div class="banner-list">
                                        <ul>
                                            <li>Best quality</li>
                                            <li>Best quality</li>
                                            <li>Best quality</li>
                                        </ul>
                                    </div>
                                    <div class="social-icon-wraper mt-25">
                                        <div class="social-icon socile-icon-style-1">
                                            <ul>
                                                <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i></a></li>
                                                <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                <li><a href="#"><i class="zmdi zmdi-repeat"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="single-banner gray-bg">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="sb-img text-center">
                                <img src="images/banner/01.png" alt="">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="sb-content mt-60">
                                <div class="banner-text">
                                    <h5 class="lato">New Arrival</h5>
                                    <h2 class="montserrat">Grag T- Shirt</h2>
                                    <h3 class="montserrat">$99.99</h3>
                                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                                    <a class="btn-def btn2" href="#">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--banner area end-->

<!--discunt-featured-onsale-area start -->
<div class="discunt-featured-onsale-area pt-60">
    <div class="container">
        <div class="row">
            <div class="product-area tab-cars-style">
                <div class="title-tab-product-category">
                    <div class="col-md-12 text-center">
                        <ul class="nav mb-40 heading-style-2" role="tablist">
                            <li role="presentation" class="active"><a href="#newarrival" aria-controls="newarrival" role="tab" data-toggle="tab">New Arrival</a></li>
                            <li role="presentation"><a href="#bestsellr" aria-controls="bestsellr" role="tab" data-toggle="tab">Best Seller</a></li>
                            <li role="presentation"><a href="#specialoffer" aria-controls="specialoffer" role="tab" data-toggle="tab">Special Offer</a></li>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="content-tab-product-category">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="newarrival">
                            <div class="total-new-arrival new-arrival-slider-active carsoule-btn">
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="product-label">
                                                <div class="new">New</div>
                                            </div>
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/01.jpg" class="primary-image"> <img alt="" src="images/product/02.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/03.jpg" class="primary-image"> <img alt="" src="images/product/04.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="product-label">
                                                <div class="new">Sale</div>
                                            </div>
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/02.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star-o"></i></a>
                                                </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/04.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/05.jpg" class="primary-image"> <img alt="" src="images/product/06.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                    <div class="old-price"> <del>$250</del> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane  fade in" id="bestsellr">
                            <div class="total-new-arrival new-arrival-slider-active carsoule-btn">
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="product-label">
                                                <div class="new">New</div>
                                            </div>
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/01.jpg" class="primary-image"> <img alt="" src="images/product/02.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="product-label">
                                                <div class="new">Sale</div>
                                            </div>
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/02.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star-o"></i></a>
                                                </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/04.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/03.jpg" class="primary-image"> <img alt="" src="images/product/04.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/05.jpg" class="primary-image"> <img alt="" src="images/product/06.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                    <div class="old-price"> <del>$250</del> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane  fade in" id="specialoffer">
                            <div class="total-new-arrival new-arrival-slider-active carsoule-btn">
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/04.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/05.jpg" class="primary-image"> <img alt="" src="images/product/06.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star-o"></i></a> </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                    <div class="old-price"> <del>$250</del> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="product-label">
                                                <div class="new">New</div>
                                            </div>
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/01.jpg" class="primary-image"> <img alt="" src="images/product/02.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/03.jpg" class="primary-image"> <img alt="" src="images/product/04.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                                <div class="col-md-3">
                                    <!-- single product start-->
                                    <div class="single-product">
                                        <div class="product-img">
                                            <div class="product-label">
                                                <div class="new">Sale</div>
                                            </div>
                                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                                <a href="single-product.html"> <img alt="" src="images/product/02.jpg" class="primary-image"> <img alt="" src="images/product/03.jpg" class="secondary-image"> </a>
                                            </div>
                                            <div class="product-icon socile-icon-tooltip text-center">
                                                <ul>
                                                    <li><a href="#" data-tooltip="Add To Cart" class="add-cart" data-placement="left"><i class="fa fa-cart-plus"></i></a></li>
                                                    <li><a href="#" data-tooltip="Wishlist" class="w-list"><i class="fa fa-heart-o"></i></a></li>
                                                    <li><a href="#" data-tooltip="Compare" class="cpare"><i class="fa fa-refresh"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal"><i class="fa fa-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <div class="prodcut-name"> <a href="single-product.html">Quisque fringilla</a> </div>
                                            <div class="prodcut-ratting-price">
                                                <div class="prodcut-ratting">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star-o"></i></a>
                                                </div>
                                                <div class="prodcut-price">
                                                    <div class="new-price"> $220 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single product end-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--discunt-featured-onsale-area end-->

<!--testimonial-area-start-->
<div class="testimonial-area overlay ptb-70 mt-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title color-lightgrey mb-40 text-center">
                    <h5 class="uppercase">Testimonial</h5>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="total-testimonial active-slider carosule-pagi pagi-03">
                    <div class="single-testimonial">
                        <div class="testimonial-img">
                            <img src="images/team/testi-03.jpg" alt="">
                        </div>
                        <div class="testimonial-content color-lightgrey">
                            <div class="name-degi pos-rltv">
                                <h5>Anik Islam</h5>
                                <p>Developer</p>
                            </div>
                            <div class="testi-text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
                            </div>
                        </div>
                    </div>
                    <div class="single-testimonial">
                        <div class="testimonial-img">
                            <img src="images/team/testi-02.jpg" alt="">
                        </div>
                        <div class="testimonial-content color-lightgrey">
                            <div class="name-degi pos-rltv">
                                <h5>Shakara Tasnim</h5>
                                <p>Facebook Liker</p>
                            </div>
                            <div class="testi-text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
                            </div>
                        </div>
                    </div>
                    <div class="single-testimonial">
                        <div class="testimonial-img">
                            <img src="images/team/testi-01.jpg" alt="">
                        </div>
                        <div class="testimonial-content color-lightgrey">
                            <div class="name-degi pos-rltv">
                                <h5>Momen Buhyan</h5>
                                <p>Designer</p>
                            </div>
                            <div class="testi-text">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--testimonial-area-end-->

<!--new-arrival on-sale Top-ratted area start-->
<div class="arrival-ratted-sale-area pt-70">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">New Arrival</h5>
                </div>
                <div class="ctg-slider-active">
                    <div class="single-ctg new-arrival-ctg">
                        <div class="single-ctg-item">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="product-ctg-img pos-rltv product-overlay">
                                        <a href="single-product.html"><img src="images/product/s01.jpg" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="product-ctg-content">
                                        <p>Primo Court Mid Suede</p>
                                        <p class="font-bold">$236.99</p>
                                        <div class="social-icon socile-icon-style-1 mt-15">
                                            <ul>
                                                <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="single-ctg-item">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="product-ctg-img pos-rltv product-overlay">
                                        <a href="single-product.html"><img src="images/product/s02.jpg" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="product-ctg-content">
                                        <p>Primo Court Mid Suede</p>
                                        <p class="font-bold">$236.99</p>
                                        <div class="social-icon socile-icon-style-1 mt-15">
                                            <ul>
                                                <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-ctg new-arrival-ctg">
                        <div class="single-ctg-item">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="product-ctg-img pos-rltv product-overlay">
                                        <a href="single-product.html"><img src="images/product/s01.jpg" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="product-ctg-content">
                                        <p>Primo Court Mid Suede</p>
                                        <p class="font-bold">$236.99</p>
                                        <div class="social-icon socile-icon-style-1 mt-15">
                                            <ul>
                                                <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="single-ctg-item">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="product-ctg-img pos-rltv product-overlay">
                                        <a href="single-product.html"><img src="images/product/s02.jpg" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="product-ctg-content">
                                        <p>Primo Court Mid Suede</p>
                                        <p class="font-bold">$236.99</p>
                                        <div class="social-icon socile-icon-style-1 mt-15">
                                            <ul>
                                                <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="single-ctg on-sale-ctg">
                    <div class="heading-title heading-style pos-rltv mb-50 text-center">
                        <h5 class="uppercase">On Sale</h5>
                    </div>
                    <div class="ctg-slider-active">
                        <div class="single-ctg new-arrival-ctg">
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s01.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s02.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="single-ctg new-arrival-ctg">
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s01.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s02.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="single-ctg top-rated-ctg">
                    <div class="heading-title heading-style pos-rltv mb-50 text-center">
                        <h5 class="uppercase">Top Rated</h5>
                    </div>
                    <div class="ctg-slider-active">
                        <div class="single-ctg new-arrival-ctg">
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s01.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s02.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="single-ctg new-arrival-ctg">
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s01.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-ctg-item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="product-ctg-img pos-rltv product-overlay">
                                            <a href="single-product.html"><img src="images/product/s02.jpg" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="product-ctg-content">
                                            <p>Primo Court Mid Suede</p>
                                            <p class="font-bold">$236.99</p>
                                            <div class="social-icon socile-icon-style-1 mt-15">
                                                <ul>
                                                    <li><a href="#"><i class="zmdi zmdi-shopping-cart"></i></a></li>
                                                    <li><a href="#" data-tooltip="Quick View" class="q-view" data-toggle="modal" data-target=".modal" tabindex="0"><i class="zmdi zmdi-eye"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--new-arrival on-sale Top-ratted area end-->

<!--brand area are start-->
<div class="brand-area ptb-60">
    <div class="container">
        <div class="row">
            <div class="total-brand">
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/01.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/02.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/03.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/04.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/05.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/06.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/01.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/02.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/03.png" alt=""></a></div>
                </div>
                <div class="col-md-3">
                    <div class="single-brand shadow-box"><a href="#"><img src="images/brand/04.png" alt=""></a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--brand area are start-->

<!--blog area are start-->
<div class="blog-area pb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">Blog</h5>
                </div>
            </div>
            <div class="total-blog">
                <div class="col-md-4">
                    <div class="single-blog">
                        <div class="blog-img pos-rltv product-overlay">
                            <a href="#"><img src="images/blog/01.jpg" alt=""></a>
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h5 class="uppercase font-bold"><a href="#">New fashion collection 2016</a></h5>
                                <div class="like-comments-date">
                                    <ul>
                                        <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i>13 Like</a></li>
                                        <li><a href="#"><i class="zmdi zmdi-comment-outline"></i>03 Comments</a></li>
                                        <li class="blog-date"><a href="#"><i class="zmdi zmdi-calendar-alt"></i>16 jun 2016</a></li>
                                    </ul>
                                </div>
                                <div class="blog-text">
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using.</p>
                                </div>
                                <a class="read-more montserrat" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-blog">
                        <div class="blog-img pos-rltv product-overlay">
                            <a href="#"><img src="images/blog/02.jpg" alt=""></a>
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h5 class="uppercase font-bold"><a href="#">New fashion collection 2016</a></h5>
                                <div class="like-comments-date">
                                    <ul>
                                        <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i>13 Like</a></li>
                                        <li><a href="#"><i class="zmdi zmdi-comment-outline"></i>03 Comments</a></li>
                                        <li class="blog-date"><a href="#"><i class="zmdi zmdi-calendar-alt"></i>16 jun 2016</a></li>
                                    </ul>
                                </div>
                                <div class="blog-text">
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using.</p>
                                </div>
                                <a class="read-more montserrat" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-blog">
                        <div class="blog-img pos-rltv product-overlay">
                            <a href="#"><img src="images/blog/03.jpg" alt=""></a>
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h5 class="uppercase font-bold"><a href="#">New fashion collection 2016</a></h5>
                                <div class="like-comments-date">
                                    <ul>
                                        <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i>13 Like</a></li>
                                        <li><a href="#"><i class="zmdi zmdi-comment-outline"></i>03 Comments</a></li>
                                        <li class="blog-date"><a href="#"><i class="zmdi zmdi-calendar-alt"></i>16 jun 2016</a></li>
                                    </ul>
                                </div>
                                <div class="blog-text">
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using.</p>
                                </div>
                                <a class="read-more montserrat" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-blog">
                        <div class="blog-img pos-rltv product-overlay">
                            <a href="#"><img src="images/blog/01.jpg" alt=""></a>
                        </div>
                        <div class="blog-content">
                            <div class="blog-title">
                                <h5 class="uppercase font-bold"><a href="#">New fashion collection 2016</a></h5>
                                <div class="like-comments-date">
                                    <ul>
                                        <li><a href="#"><i class="zmdi zmdi-favorite-outline"></i>13 Like</a></li>
                                        <li><a href="#"><i class="zmdi zmdi-comment-outline"></i>03 Comments</a></li>
                                        <li class="blog-date"><a href="#"><i class="zmdi zmdi-calendar-alt"></i>16 jun 2016</a></li>
                                    </ul>
                                </div>
                                <div class="blog-text">
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using.</p>
                                </div>
                                <a class="read-more montserrat" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--blog area are end-->
 */ ?>