<?php

use frontend\controllers\MainController as d;
use yii\helpers\Html;
use frontend\components\CustomBreadcrumbs as CBreadcrumbs;

$this->params['breadcrumbs'][] = [
    'label' => 'О нас',
    'coption' => [
        'serial_number' => '2',
    ]
];

//d::pre(time());

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

<!-- about-us-area start-->
<div class="about-us-area ptb-50">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">О нас</h5>
                </div>
            </div>

            <div class="about-us-wrap">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="about-img"><?=Html::img('@web/images/about.jpg',['alt'=>'Изображение'])?></div>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12">

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

                    <!--
                    <a href="/td-romeo/" class="btn btn-default">Новости</a>
                    <a href="/useful/" class="btn btn-default">Полезная информация</a>
                    -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- about-us-area end-->

<?php /*
<!--choose us area start-->
<div class="choose-us-area pb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">Why choose us</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="single-choose text-center">
                    <div class="choose-icon pos-rltv">
                        <i class="zmdi zmdi-shopping-cart-plus"></i>
                    </div>
                    <div class="choose-title">
                        <h5>Best Product</h5>
                    </div>
                    <div class="choose-des">
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="single-choose text-center">
                    <div class="choose-icon pos-rltv">
                        <i class="zmdi zmdi-headset-mic"></i>
                    </div>
                    <div class="choose-title">
                        <h5>24/7 Support</h5>
                    </div>
                    <div class="choose-des">
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="single-choose text-center">
                    <div class="choose-icon pos-rltv">
                        <i class="zmdi zmdi-format-strikethrough-s"></i>
                    </div>
                    <div class="choose-title">
                        <h5>Secure</h5>
                    </div>
                    <div class="choose-des">
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 hidden-sm col-xs-12">
                <div class="single-choose text-center">
                    <div class="choose-icon pos-rltv">
                        <i class="zmdi zmdi-trending-up"></i>
                    </div>
                    <div class="choose-title">
                        <h5>Best Product</h5>
                    </div>
                    <div class="choose-des">
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--choose us area start-->

<!--better-area start-->
<div class="better-area pb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">We Are Better</h5>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="skill-content">
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
                    <div class="powerfull-skills">
                        <div class="single-prograss">
                            <div class="progess-heading"> Photoshop <span>(79%)</span> </div>
                            <div class="progress">
                                <div class="progress-bar pr-blue wow fadeInLeft" style="width:79%" data-wow-duration="2s" data-wow-delay="1s"> </div>
                            </div>
                        </div>
                        <div class="single-prograss">
                            <div class="progess-heading"> Illustrator <span>(96%)</span> </div>
                            <div class="progress">
                                <div class="progress-bar pr-green wow fadeInLeft" style="width:96%" data-wow-duration="2s" data-wow-delay="1s"> </div>
                            </div>
                        </div>
                        <div class="single-prograss">
                            <div class="progess-heading pr-voilate"> HTML <span>(85%)</span> </div>
                            <div class="progress">
                                <div class="progress-bar pr-violate wow fadeInLeft" style="width:85%" data-wow-duration="2s" data-wow-delay="1s"> </div>
                            </div>
                        </div>
                        <div class="single-prograss">
                            <div class="progess-heading"> Wordpress <span>(92%)</span> </div>
                            <div class="progress">
                                <div class="progress-bar pr-ornage wow fadeInLeft" style="width:92%" data-wow-duration="2s" data-wow-delay="1s"> </div>
                            </div>
                        </div>
                    </div>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. will be distracted by the readable content of a page when looking at its layout. </p>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="skill-img"><img src="images/blog/skill.jpg" alt=""></div>
            </div>
        </div>
    </div>
</div>
<!--better-area end-->

<!--out team area start-->
<div class="our-team-area pb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">Our Team</h5>
                </div>
            </div>
            <div class="total-team team-carasoul">
                <div class="col-md-3">
                    <!-- single team start-->
                    <div class="single-product single-member">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="#"> <img alt="" src="images/team/01.jpg"></a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Facebook" class="add-cart" data-placement="left"><i class="zmdi zmdi-facebook"></i></a></li>
                                    <li><a href="#" data-tooltip="Twitter" class="w-list"><i class="zmdi zmdi-twitter"></i></a></li>
                                    <li><a href="#" data-tooltip="Pinterest" class="cpare"><i class="zmdi zmdi-pinterest"></i></a></li>
                                    <li><a href="#" data-tooltip="Vimeo" class="cpare"><i class="zmdi zmdi-vimeo"></i></a></li>
                                </ul>
                            </div>
                            <div class="member-info">
                                <h5>Momen Rana</h5>
                                <p>Designer</p>
                            </div>
                        </div>
                    </div>
                    <!-- single team end-->
                </div>
                <div class="col-md-3">
                    <!-- single team start-->
                    <div class="single-product single-member">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="#"> <img alt="" src="images/team/02.jpg"></a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Facebook" class="add-cart" data-placement="left"><i class="zmdi zmdi-facebook"></i></a></li>
                                    <li><a href="#" data-tooltip="Twitter" class="w-list"><i class="zmdi zmdi-twitter"></i></a></li>
                                    <li><a href="#" data-tooltip="Pinterest" class="cpare"><i class="zmdi zmdi-pinterest"></i></a></li>
                                    <li><a href="#" data-tooltip="Vimeo" class="cpare"><i class="zmdi zmdi-vimeo"></i></a></li>
                                </ul>
                            </div>
                            <div class="member-info">
                                <h5>Shakara Tasnim</h5>
                                <p>Facebooker</p>
                            </div>
                        </div>
                    </div>
                    <!-- single team end-->
                </div>
                <div class="col-md-3">
                    <!-- single team start-->
                    <div class="single-product single-member">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="#"> <img alt="" src="images/team/03.jpg"></a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Facebook" class="add-cart" data-placement="left"><i class="zmdi zmdi-facebook"></i></a></li>
                                    <li><a href="#" data-tooltip="Twitter" class="w-list"><i class="zmdi zmdi-twitter"></i></a></li>
                                    <li><a href="#" data-tooltip="Pinterest" class="cpare"><i class="zmdi zmdi-pinterest"></i></a></li>
                                    <li><a href="#" data-tooltip="Vimeo" class="cpare"><i class="zmdi zmdi-vimeo"></i></a></li>
                                </ul>
                            </div>
                            <div class="member-info">
                                <h5>Nasir Liton</h5>
                                <p>Class Expert</p>
                            </div>
                        </div>
                    </div>
                    <!-- single team end-->
                </div>
                <div class="col-md-3">
                    <!-- single team start-->
                    <div class="single-product single-member">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="#"> <img alt="" src="images/team/04.jpg"></a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Facebook" class="add-cart" data-placement="left"><i class="zmdi zmdi-facebook"></i></a></li>
                                    <li><a href="#" data-tooltip="Twitter" class="w-list"><i class="zmdi zmdi-twitter"></i></a></li>
                                    <li><a href="#" data-tooltip="Pinterest" class="cpare"><i class="zmdi zmdi-pinterest"></i></a></li>
                                    <li><a href="#" data-tooltip="Vimeo" class="cpare"><i class="zmdi zmdi-vimeo"></i></a></li>
                                </ul>
                            </div>
                            <div class="member-info">
                                <h5>Ashim Baroi</h5>
                                <p>Downloder</p>
                            </div>
                        </div>
                    </div>
                    <!-- single team end-->
                </div>
                <div class="col-md-3">
                    <!-- single team start-->
                    <div class="single-product single-member">
                        <div class="product-img">
                            <div class="single-prodcut-img  product-overlay pos-rltv">
                                <a href="#"> <img alt="" src="images/team/03.jpg"></a>
                            </div>
                            <div class="product-icon socile-icon-tooltip text-center">
                                <ul>
                                    <li><a href="#" data-tooltip="Facebook" class="add-cart" data-placement="left"><i class="zmdi zmdi-facebook"></i></a></li>
                                    <li><a href="#" data-tooltip="Twitter" class="w-list"><i class="zmdi zmdi-twitter"></i></a></li>
                                    <li><a href="#" data-tooltip="Pinterest" class="cpare"><i class="zmdi zmdi-pinterest"></i></a></li>
                                    <li><a href="#" data-tooltip="Vimeo" class="cpare"><i class="zmdi zmdi-vimeo"></i></a></li>
                                </ul>
                            </div>
                            <div class="member-info">
                                <h5>Rafiq Rana</h5>
                                <p>Fashion Expert</p>
                            </div>
                        </div>
                    </div>
                    <!-- single team end-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--out team area end-->
*/ ?>