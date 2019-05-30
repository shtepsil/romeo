<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\controllers\MainController as d;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\widgets\Menu;
use frontend\components\MainMenu;
use yii\helpers\Url;
use frontend\components\BasketProduct;

AppAsset::register($this);

// $classes - Классы для шаблона ссылок 2го уровня
$classes = [];
$classes[] = 'sm no-link';// Ссылки с выпадающими меню
/*
 * Ссылки с URL
 * sm-url - класс для hover underline
 */
$classes[] = 'sm sm-url';
$dropdown_menu_list = MainMenu::getMenuItems($classes);

//d::pri($dropdown_menu_list);
//d::pri(Yii::$app);
//d::pre();
$host = Yii::$app->request->hostName;
$uri1 = Yii::$app->request->url;
$uri2 = Yii::$app->request->pathInfo;

//d::pre($uri1);
//d::prebl($uri2);

//d::pre(d::getPartStrByCharacter($uri,'/'));

//$session = Yii::$app->session;
//$session->destroy();

if($_COOKIE['backet']){
    $backet = d::jsonToArray($_COOKIE['backet']);
}else $backet = [];

//d::pri($backet);
//d::prebl(time());
//d::pri(date('Y-m-d H:i:s',1555915745));
//d::pri(date('Y-m-d H:i:s',1555918383));

$basket_products = Yii::$app->params['basket_products'];
//d::pri($basket_products);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html class="no-js" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<input type="hidden" name="user_auth" value="<?=(Yii::$app->session['user']['auth'])?'1':'0'?>" />
<input type="hidden" name="user_id" value="<?=($user_id = Yii::$app->session['user']['id'])?$user_id:'0'?>" />
<div class="wrapper <?='class-page'?>">


    <!-- Start of header area -->
    <header class="header-area header-wrapper">
       <div class="header-top-bar black-bg clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="area-log-auth login-register-area  <?=(Yii::$app->session['user']['auth'])?'dn':''?>">
                            <ul>
                                <li><a href="#" data-tooltip="Quick View" class="" data-toggle="modal" data-target="#modal-auth" tabindex="0" onclick="cea()">Войти</a></li>
                                <li><a href="#" data-tooltip="Quick View" class="" data-toggle="modal" data-target="#modal-register" tabindex="0" onclick="cea()">Регистрация</a></li>
                            </ul>
                            <?//='<div class="res">result</div>'?>
                        </div>
                        <div class="user-authorized <?=(!Yii::$app->session['user']['auth'])?'dn':''?>">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>" class="csrf"/>

                            <a href="#" title="Личный кабинет" class="dropdown-toggle user-menu-toggle" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-user"></span></a>
                            <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
<!--                                <li>-->
<!--                                    <a href=""><i class="zmdi zmdi-account"></i><span>Профиль</span></a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#"><i class="zmdi zmdi-card"></i><span>Баланс</span></a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="inbox.html"><i class="zmdi zmdi-email"></i><span>Почта</span></a>-->
<!--                                </li>-->
                                <li>
                                    <a href="/user/"><i class="zmdi zmdi-settings"></i><span>Настройки</span></a>
                                </li>
                                <li class="divider"></li>
                                <li>
<!--                                    <a href="#"><i class="zmdi zmdi-power"></i><span>Выйти</span></a>-->
                                    <a
                                            type="button"
                                            name="logout"
                                            class="logout"
                                            title="Выйти"
                                            data-url="/ajax/logout"
                                            method="post"
                                    >
                                        <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                        <i class="zmdi zmdi-power"></i><span>Выйти</span>
                                    </a>
                                </li>
                            </ul>
                            <span class="user-id"></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 hidden-xs">
                        <?php /*
                        <div class="social-search-area text-center">
                            <div class="social-icon socile-icon-style-2">
                                <ul>
                                    <li><a href="#" title="facebook"><i class="fa fa-facebook"></i></a> </li>
                                    <li><a href="#" title="twitter"><i class="fa fa-twitter"></i></a> </li>
                                    <li> <a href="#" title="dribble"><i class="fa fa-dribbble"></i></a></li>
                                    <li> <a href="#" title="behance"><i class="fa fa-behance"></i></a> </li>
                                    <li> <a href="#" title="rss"><i class="fa fa-rss"></i></a> </li>
                                </ul>
                            </div>
                        </div>
                        */ ?>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="cart-currency-area login-register-area text-right">
                            <ul>
                                <?php /*
                                <li>
                                    <div class="header-currency">
                                        <select>
                                            <option value="1">USD</option>
                                            <option value="2">Pound</option>
                                            <option value="3">Euro</option>
                                            <option value="4">Dinar</option>
                                        </select>
                                    </div>
                                </li>
                                */ ?>
                                <li>
                                    <div class="header-cart">
                                        <div class="cart-icon"> <a href="#">Корзина<i class="zmdi zmdi-shopping-cart"></i></a> <span class="count-backet"><?=($basket_products)?count($basket_products):'0'?></span> </div>
                                        <div class="cart-content-wraper">

                                            <div class="main-cart-content <?=(!$basket_products)?'dn':''?>">
                                                <div class="list-products">
                                                    <?php
                                                    if($basket_products) {
                                                        $total_payment = 0;
                                                        foreach ($basket_products as $pt) {
                                                            $total_payment += $pt['price'];

                                                            echo Yii::$app->view->renderFile(
                                                                '@app/views/catalog/shortcodes/js-templates/cart-single-procuct.php',
                                                                ['pt' => $pt]
                                                            );
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <div class="cart-subtotal"> Итого: <span><i class="fa fa-rub" aria-hidden="true"></i> <span class="total-summ"><?=number_format($total_payment,2,'.',' ')?></span></span> </div>
                                                <div class="cart-check-btn">
                                                    <div class="view-cart"> <a class="btn-def" href="/site/cart">Просмотр</a> </div>
                                                    <div class="check-btn"> <a class="btn-def" href="checkout.html">Заказать</a> </div>
                                                </div>
                                            </div>

                                            <div class="no-products <?=($basket_products)?'dn':''?>">
                                                Товаров пока нет
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sticky-header"  class="header-middle-area">
            <div class="container">
                <div class="full-width-mega-dropdown">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 w-logo">
                            <div class="logo ptb-0">
                                <a href="/">
<!--                                    <img src="/" alt="main logo">-->
                                    <?=Html::img('@web/images/logo/logo.png',['alt'=>'Логотип','title'=>'На главную'])?>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7 desctop">
                            <nav class="clearfix">
                            <?php

                            echo Menu::widget([
                                'items' => [
                                    [
                                        'label' => 'Каталог',
                                        'url' => ['#'],
//                                        'active' => d::active('catalog'),
                                        'template' =>
                                            '<a href="{url}" class="lmm no-link '.((d::active('catalog'))?'active':'').'">{label}</a>',
                                        'items' => $dropdown_menu_list,
                                        'submenuTemplate' => "\n<ul class='drop-menu s2' style='display: none;'>\n{items}\n</ul>\n",
                                    ],
                                    [
                                        'label' => 'Сегодня акция',
                                        'url' => [$host.'/site/stocks'],
//                                        'active' => d::activeMainMenu('stocks'),
                                        'template' =>
                                            '<a href="{url}" class="lmm '.(d::activeMainMenu('stocks')?'active':'').'">{label}</a>',
                                    ],
                                    [
                                        'label' => 'Моя скидка',
                                        'url' => [$host.'/site/discounts'],
//                                        'active' => d::activeMainMenu('discounts'),
                                        'template' =>
                                            '<a href="{url}" class="lmm '.(d::activeMainMenu('discounts')?'active':'').'">{label}</a>',
                                    ],
                                    [
                                        'label' => 'О компании',
                                        'url' => [$host.'/site/about'],
//                                        'active' => d::activeMainMenu('about'),
                                        'template' =>
                                            '<a href="{url}" class="lmm '.(d::activeMainMenu('about')?'active':'').'">{label}</a>',
                                    ],
                                    [
                                        'label' => 'Контакты',
                                        'url' => [$host.'/site/contacts'],
                                        'active' => d::activeMainMenu('contacts'),
                                        'template' =>
                                            '<a href="{url}" class="lmm '.(d::activeMainMenu('contacts')?'active':'').'">{label}</a>',
                                    ]

                                ],
                                'options'=>['class'=>'clearfix s1'],
                                'submenuTemplate' =>
                                    "\n<ul class='sub-menu s3' style='display: none;'>\n{items}\n</ul>\n",
                            ]);

                            ?>
                            </nav>

                        </div>
                        <div class="col-md-7 mobile">
                            <nav class="clearfix">
                                <a href="#" class="no-link slide-mobile-menu" id="pull">Меню</a>
                                <?php

                                echo Menu::widget([
                                    'items' => [
                                        ['label' => 'Каталог',
                                            'url' => ['#'],
                                            'options'=>[],
                                            'template' => '<a href="{url}" class="dm no-link '.((d::active('catalog'))?'active':'').'">{label}</a>',
                                            'items' => $dropdown_menu_list,
                                            'submenuTemplate' => "\n<ul class='drop-menu s2'>\n{items}\n</ul>\n",
                                        ],
                                        [
                                            'label' => 'Сегодня акция',
                                            'url' => [$host.'/site/stocks'],
                                            'template' =>
                                                '<a href="{url}" class="lmm '.(d::activeMainMenu('stocks')?'active':'').'">{label}</a>',
                                        ],
                                        [
                                            'label' => 'Моя скидка',
                                            'url' => [$host.'/site/discounts'],
                                            'template' =>
                                                '<a href="{url}" class="lmm '.(d::activeMainMenu('discounts')?'active':'').'">{label}</a>',
                                        ],
                                        [
                                            'label' => 'О компании',
                                            'url' => [$host.'/site/about'],
                                            'template' =>
                                                '<a href="{url}" class="lmm '.(d::activeMainMenu('about')?'active':'').'">{label}</a>',
                                        ],
                                        [
                                            'label' => 'Контакты',
                                            'url' => [$host.'/site/contacts'],
                                            'template' =>
                                                '<a href="{url}" class="lmm '.(d::activeMainMenu('contacts')?'active':'').'">{label}</a>',
                                        ],

                                    ],
                                    'options'=>['class'=>'clearfix s1'],
                                    'submenuTemplate' => "\n<ul class='sub-menu s3'>\n{items}\n</ul>\n",
                                ]);

                                ?>
                            </nav>
                        </div>

                        <?if(!Yii::$app->user->isGuest):?>
                        <style type="text/css">
                            .administrator{
                                position: absolute;
                                right: 0;
                                top: 0;
                                background: #1CD000;
                                color: white;
                                padding: 0px 11px 3px;
                                border-radius: 0px 0px 5px 5px;
                            }
                        </style>
                        <div class="administrator">администратор</div>
                        <?endif?>

                        <div class="col-md-3 hidden-sm hidden-xs dn">
                            <div class="search-box global-table">
                                <div class="global-row">
                                    <div class="global-cell">
                                        <form action="#">
                                            <div class="input-box">
                                                <input class="single-input" placeholder="Поиск" type="text">
                                                <button class="src-btn"><i class="fa fa-search"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End of header area -->

    <div class="wrap-main-content">
        <?= $content ?>
    </div>

    <!-- footer area start-->
    <div class="footer-area ptb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="single-footer contact-us">
                        <div class="footer-title uppercase">
                            <h5>Контакты</h5> </div>
                        <ul>
                            <li>
                                <div class="contact-icon"> <i class="zmdi zmdi-pin-drop"></i> </div>
                                <div class="contact-text">
                                    <p><span>Торговый дом “Ромео”</span> <span>г.Кемерово, Советский просп., 35</span></p>
                                </div>
                            </li>
                            <?php /*
                            <li>
                                <div class="contact-icon"> <i class="zmdi zmdi-email-open"></i> </div>
                                <div class="contact-text">
                                    <p>
                                        <span>
                                            <a href="mailto:info@romeo-man.ru">
                                                info@romeo-man.ru</a>
                                        </span>
<!--                                        <span>-->
<!--                                            <a href="#">-->
<!--                                                admin@devitems.com</a>-->
<!--                                        </span>-->
                                    </p>
                                </div>
                            </li>
                            */ ?>
                            <li>
                                <div class="contact-icon"> <i class="zmdi zmdi-phone-paused"></i> </div>
                                <div class="contact-text">
                                    <p>
                                        <span>Тел. +7 (908) 930-03-30</span>
<!--                                        <span>+11 (018) 50950555</span>-->
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php /*
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                    <div class="single-footer informaton-area">
                        <div class="footer-title uppercase">
                            <h5>Информация</h5> </div>
                        <div class="informatoin">
                            <ul>
                                <li><a href="#">My Account</a></li>
                                <li><a href="#">Order History</a></li>
                                <li><a href="#">Wishlist</a></li>
                                <li><a href="#">Returnes</a></li>
                                <li><a href="#">Private Policy</a></li>
                                <li><a href="#">Site Map</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                */ ?>
                <?php /*
                <div class="col-lg-3 col-md-4 hidden-sm col-xs-12">
                    <div class="single-footer instagrm-area">
                        <div class="footer-title uppercase">
                            <h5>Наш инстаграм</h5>
                        </div>
                        <div class="instagrm">
                            <?php /*
                            <ul>
                                <li><a href="#"><img src="images/gallery/01.jpg" alt=""></a></li>
                                <li><a href="#"><img src="images/gallery/02.jpg" alt=""></a></li>
                                <li><a href="#"><img src="images/gallery/03.jpg" alt=""></a></li>
                                <li><a href="#"><img src="images/gallery/04.jpg" alt=""></a></li>
                                <li><a href="#"><img src="images/gallery/05.jpg" alt=""></a></li>
                                <li><a href="#"><img src="images/gallery/06.jpg" alt=""></a></li>
                            </ul>
                            */ ?>
                        </div>
                    </div>
                </div>

                <?php /*
                <div class="col-lg-3 col-md-3 col-sm-4 col-lg-offset-1 col-xs-12">
                    <div class="single-footer newslatter-area">
                        <div class="footer-title uppercase">
                            <h5>Подписка на новости</h5>
                        </div>
                        <div class="newslatter">
                            <form action="#" method="post">
                                <div class="input-box pos-rltv">
                                    <input placeholder="Укажите ваш Email" type="text">
                                    <a href="#" class="no-link">
                                        <i class="zmdi zmdi-arrow-right"></i>
                                    </a>
                                </div>
                            </form>
                            <?php /*
                            <div class="social-icon socile-icon-style-3 mt-40">
                                <div class="footer-title uppercase">
                                    <h5>Мы в соцсетях</h5>
                                </div>
                                <ul>
                                    <li><a href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                    <li><a href="#"><i class="zmdi zmdi-linkedin"></i></a></li>
                                    <li><a href="#"><i class="zmdi zmdi-pinterest"></i></a></li>
                                    <li><a href="#"><i class="zmdi zmdi-google"></i></a></li>
                                    <li><a href="#"><i class="zmdi zmdi-twitter"></i></a></li>
                                </ul>
                            </div>
                            */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--footer area start-->

    <!--footer bottom area start-->
    <div class="footer-bottom global-table">
        <div class="global-row">
            <div class="global-cell">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="copyrigth"> Copyright @
                                Romeo-Man All right reserved
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php /*
                            <ul class="payment-support text-right">
                                <li>
                                    <a href="#"><img src="images/icons/pay1.png" alt="" /></a>
                                </li>
                                <li>
                                    <a href="#"><img src="images/icons/pay2.png" alt="" /></a>
                                </li>
                                <li>
                                    <a href="#"><img src="images/icons/pay3.png" alt="" /></a>
                                </li>
                                <li>
                                    <a href="#"><img src="images/icons/pay4.png" alt="" /></a>
                                </li>
                                <li>
                                    <a href="#"><img src="images/icons/pay5.png" alt="" /></a>
                                </li>
                            </ul>
 */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--footer bottom area end-->




<?=Yii::$app->view->renderFile('@app/views/site/shortcodes/modal-register.php',[
    'alerts' => Yii::$app->view->renderFile('@app/views/site/shortcodes/alerts.php')
]);?>
<?=Yii::$app->view->renderFile('@app/views/site/shortcodes/modal-auth.php',[
    'alerts' => Yii::$app->view->renderFile('@app/views/site/shortcodes/alerts.php')
]);?>
<?=Yii::$app->view->renderFile('@app/views/catalog/shortcodes/js-templates/cart-single-procuct.php');?>
<?=Yii::$app->view->renderFile('@app/views/site/shortcodes/js-templates/cart-single-product.php',['pt'=>['js'=>true]]);?>
<?=Yii::$app->view->renderFile('@app/views/site/shortcodes/js-templates/cart-total-single-product.php',['pt'=>['js'=>true]]);?>

</div>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter36490840 = new Ya.Metrika({
                    id:36490840,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/36490840" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-140225440-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-140225440-1');
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
