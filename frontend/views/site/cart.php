<?php

use backend\controllers\MainController as d;
use \yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use frontend\components\CustomBreadcrumbs as CBreadcrumbs;

$this->title = 'Корзина';

$this->params['breadcrumbs'][] = [
    'label' => Html::encode($this->title),
    'coption' => [
        'serial_number' => '2',
    ]
];

$products = Yii::$app->params['basket_products'];
$zero = Yii::getAlias('@zero');
$zero_one = Yii::getAlias('@zero_one');
$user_auth = Yii::$app->session['user']['auth'];
//d::pre();
//d::prebl($_SESSION);
//d::pri($products);

//$dd = date('Y-m-d H:i:s',1555346065);
//d::pre($dd);

// Данные о пользователе
$ud = Yii::$app->params['user'];
//d::pre($ud);
// main-bt - main-basket
?>

<div class="main-bt">
    <input type="hidden" name="type_basket" value="main-bt" />
    <input type="hidden" name="request_url" value="<?=Yii::$app->request->url?>" />

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

    <?'<div class="res">result</div>'?>

    <div class="container no-products <?=($products)?'dn':''?>">
        <div class="row">
            <div class="col-xs-12 text-center" style="min-height: 500px;">
                <br>
                <br>
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase">Товаров пока нет</h5>
                </div>
                <?=$alerts?>
            </div>
        </div>
    </div>

    <!--cart-checkout-area start -->
    <div class="cart-checkout-area pt-30 is-products <?=(!$products)?'dn':''?>">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="heading-title heading-style pos-rltv mb-50">
                        <h5 class="uppercase">
                            <?= Html::encode($this->title) ?>
                        </h5>
                    </div>
                </div>
                <br>
                <br>
                <div class="product-area">
                    <div class="title-tab-product-category">
                        <div class="col-md-12 text-center pb-60">
                            <ul class="nav heading-style-3" role="tablist">
                                <li role="presentation" class="active shadow-box"><a href="#cart" aria-controls="cart" role="tab" data-toggle="tab"><span>01</span> Корзина</a></li>
                                <li role="presentation" class="shadow-box"><a href="#checkout" class="steps" data-step="s2" aria-controls="checkout" role="tab" data-toggle="tab"><span>02</span> Оформление заказа</a></li>
                                <li role="presentation" class="shadow-box"><a href="#complete-order" class="steps" data-step="s3" aria-controls="complete-order" role="tab" data-toggle="tab"><span>03</span> Подтвердить заказ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?'<div class="res">result</div>'?>
                    <div class="col-sm-12">
                        <div class="content-tab-product-category pb-70">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="cart">
                                    <!-- cart are start-->
                                    <div class="cart-page-area">
                                        <div class="table-responsive mb-20">
                                            <table class="shop_table-2 cart table">
                                                <thead>
                                                <tr>
                                                    <th class="product-thumbnail ">&nbsp;&nbsp;Изображение&nbsp;&nbsp;</th>
                                                    <th class="product-name ">Наименование</th>
                                                    <th class="product-subtotal ">Цена</th>
                                                    <th class="product-discount-price ">Цена со скидкой</th>
                                                    <th class="product-discount">Скидка</th>
                                                    <th class="product-remove">Удалить</th>
                                                </tr>
                                                </thead>
                                                <tbody>
<?php
if($products){
    foreach($products as $pt){
        echo Yii::$app->view->renderFile(
                '@app/views/site/shortcodes/js-templates/cart-single-product.php',
                ['pt'=>$pt]
        );
    }
}
?>
                                                </tbody>
                                            </table>
                                        </div>


                                        <div class="cart-bottom-area">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="update-continue-btn text-right pb-20">
                                                        <a href="#" class="btn-def btn2 no-link" onclick="location.reload()">Обновить корзину</a>
                                                        <a href="#checkout" aria-controls="checkout" role="tab" data-toggle="tab" class="btn-def btn2 no-link steps" data-step="to-2">Продолжить</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8 col-sm-7 col-xs-12">
                                                    <div class="update-coupne-area dn">
                                                        <div class="coupn-area">
                                                            <div class="catagory-title cat-tit-5 mb-20">
                                                                <h3>Персональная скидка</h3>
                                                                <p>Пожалуйста введтие штрихкод вашей дисконтной карты и нажмите кнопку применить</p>
                                                            </div>
                                                            <div class="input-box input-box-2 mb-20">
                                                                <input type="text" placeholder="Coupn" class="info" name="subject">
                                                            </div>
                                                            <a href="#" class="btn-def btn2">Применить</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-5 col-xs-12">
                                                    <div class="cart-total-area">
                                                        <div class="catagory-title cat-tit-5 mb-20 text-right">
                                                            <h3>Итого к оплате</h3>
                                                        </div>

                                                        <?php /*
                                                        <div class="sub-shipping">
                                                            <p>Итоговая сумма <span><i class="fa fa-rub" aria-hidden="true"></i><?=$total_amount?></span></p>
                                                        </div>

                                                        <div class="shipping-method text-right">
                                                            <div class="shipp">
                                                                <input type="radio" name="ship" id="pay-toggle1">
                                                                <label for="pay-toggle1">Flat Rate</label>
                                                            </div>
                                                            <div class="shipp">
                                                                <input type="radio" name="ship" id="pay-toggle3">
                                                                <label for="pay-toggle3">Direct Bank Transfer</label>
                                                            </div>
                                                            <p><a href="#">Calculate Shipping</a></p>
                                                        </div>
                                                        */ ?>
                                                        <div class="process-cart-total">
                                                            <p> <span><i class="fa fa-rub" aria-hidden="true"></i>&nbsp;<span class="p-c-t"><?=$zero?></span></span></p><br>
                                                        </div>
                                                        <?php /*
                                                        <div class="process-checkout-btn text-right">
                                                            <a class="btn-def btn2" href="#">Process To Checkout</a>
                                                        </div>
                                                        */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- cart are end-->
                                </div>
                                <div role="tabpanel" class="tab-pane  fade in " id="checkout">
                                    <!-- Checkout are start-->
                                    <div class="checkout-area">
                                        <div class="">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="coupne-customer-area mb50">
                                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                            <?if(!$user_auth):?>
                                                            <div class="panel panel-checkout">
                                                                <div class="panel-heading" role="tab" id="headingTwo">
                                                                    <h4 class="panel-title">
                                                                        <?=Html::img('@web/images/icons/acc.jpg',['alt'=>''])?>
                                                                        Постоянный клиент?
                                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                            Нажмите здесь, чтобы войти или зарегистрироваться
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                                    <div class="panel-body">
                                                                        <?php /*
                                                                        <div class="sm-des pb20">
                                                                            If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing & Shipping section.
                                                                        </div>

                                                                        <div class="first-last-area">
                                                                            <div class="input-box mtb-20">
                                                                                <label>Введите ваш Email</label>
                                                                                <input type="email" placeholder="Your Email" class="info" name="email">
                                                                            </div>
                                                                            <div class="input-box mb-20">
                                                                                <label>Password</label>
                                                                                <input type="password" placeholder="Password" class="info" name="padd">
                                                                            </div>
                                                                            <div class="frm-action cc-area">
                                                                                <div class="input-box tci-box">
                                                                                    <a href="#" class="btn-def btn2">Login</a>
                                                                                </div>
                                                                                <span>
                                                            <input type="checkbox" class="remr"> Remember me
                                                            </span>
                                                                                <a class="forgotten forg" href="#">Forgotten Password</a>
                                                                            </div>
                                                                        </div>
 */ ?>
                                                                        <a href="#" class="btn-def btn2 no-link" data-toggle="modal" data-target="#modal-auth" tabindex="0">Войти</a>
                                                                        <a href="#" class="btn-def btn2 no-link" data-toggle="modal" data-target="#modal-register" tabindex="0">Зарегистрироваться</a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?endif?>
                                                            <div class="panel panel-checkout">
                                                                <div class="panel-heading" role="tab" id="headingThree">
                                                                    <h4 class="panel-title info-for-user">

                                                                        <?if(!$user_auth):?>
                                                                        <div class="not-auth">
                                                                            <?=Html::img('@web/images/icons/acc.jpg',['alt'=>'','width'=>'15'])?>&nbsp;&nbsp;
                                                                            Мы подготовим товар к примерке и выдаче и уведомим Вас.<br>
                                                                            Пожалуйста, заполните контакты для уведомления. Оплата производится после получения товара.<br>
                                                                            Ваши контакты не будут сохранены для следующих заказов.
                                                                        </div>
                                                                        <?else:?>
                                                                        <div class="is-auth">
                                                                            <?=Html::img('@web/images/icons/acc.jpg',['alt'=>'','width'=>'15'])?>&nbsp;&nbsp;
                                                                            Мы подготовим товар к примерке и выдаче и уведомим Вас.<br>
                                                                            Оплата производится после получения товара.
                                                                        </div>
                                                                        <?endif?>
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-checkout dn">
                                                                <div class="panel-heading" role="tab" id="headingThree">
                                                                    <h4 class="panel-title">
                                                                        <?=Html::img('@web/images/icons/acc.jpg',['alt'=>''])?>
                                                                        Have A Coupon?
                                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                                            Click here to enter your code
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                                                    <div class="panel-body coupon-body">

                                                                        <div class="first-last-area">
                                                                            <div class="input-box mtb-20">
                                                                                <input type="text" placeholder="Coupon Code" class="info" name="code">
                                                                            </div>
                                                                            <div class="frm-action">
                                                                                <div class="input-box tci-box">
                                                                                    <a href="#" class="btn-def btn2">Apply Coupon</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <div class="alert-s2"><?=$alerts?></div>
                                                            <div class="billing-details">
                                                                <div class="contact-text right-side">
                                                                    <h2>Уточните ваши данные</h2>
                                                                    <form action="#" class="user-data">
                                                                        <div class="row">
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Имя или Имя Отчество<em class="error">*</em></label>
                                                                                    <input type="text" name="first_name" class="info" placeholder="Имя или Имя Отчество" value="<?=($ud['first_name'])?$ud['first_name']:''?>" <?=($ud['first_name'])?'disabled':''?> />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Фамилия (можно пропустить)</label>
                                                                                    <input type="text" name="last_name" class="info" placeholder="Фамилия" value="<?=($ud['last_name'])?$ud['last_name']:''?>" <?=($ud['last_name'])?'disabled':''?> />
                                                                                </div>
                                                                            </div>
                                                                            <?php /*
                                                                            <div class="col-md-12 col-sm-12 col-xs-12 dn">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Company Name</label>
                                                                                    <input type="text" name="cpany" class="info" placeholder="Company Name" />
                                                                                </div>
                                                                            </div>
*/ ?>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Email</label>
                                                                                    <input type="email" name="email" class="info" placeholder="Email" value="<?=($ud['email'])?$ud['email']['text']:''?>" <?=($ud['email']['text']!='')?'disabled':''?> />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Телефон<em class="error">*</em></label>
                                                                                    <input type="text" name="phone" class="info"  placeholder="+7(___)___-__-__" value="<?=($ud['phone'])?$ud['phone']['text']:''?>" <?=($ud['phone']['text']!='')?'disabled':''?> />
                                                                                </div>
                                                                            </div>




                                                                            <?php /*

                                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Country <em>*</em></label>
                                                                                    <select class="selectpicker select-custom" data-live-search="true">
                                                                                        <option data-tokens="Bangladesh">Bangladesh</option>
                                                                                        <option data-tokens="India">India</option>
                                                                                        <option data-tokens="Pakistan">Pakistan</option>
                                                                                        <option data-tokens="Pakistan">Pakistan</option>
                                                                                        <option data-tokens="Srilanka">Srilanka</option>
                                                                                        <option data-tokens="Nepal">Nepal</option>
                                                                                        <option data-tokens="Butan">Butan</option>
                                                                                        <option data-tokens="USA">USA</option>
                                                                                        <option data-tokens="England">England</option>
                                                                                        <option data-tokens="Brazil">Brazil</option>
                                                                                        <option data-tokens="Canada">Canada</option>
                                                                                        <option data-tokens="China">China</option>
                                                                                        <option data-tokens="Koeria">Koeria</option>
                                                                                        <option data-tokens="Soudi">Soudi Arabia</option>
                                                                                        <option data-tokens="Spain">Spain</option>
                                                                                        <option data-tokens="France">France</option>
                                                                                    </select>

                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Address <em>*</em></label>
                                                                                    <input type="text" name="add1" class="info mb-10" placeholder="Street Address">
                                                                                    <input type="text" name="add2" class="info mt10" placeholder="Apartment, suite, unit etc. (optional)">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                <div class="input-box mb-20">
                                                                                    <label>Town/City <em>*</em></label>
                                                                                    <input type="text" name="add1" class="info" placeholder="Town/City">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="input-box">
                                                                                    <label>State/Divison <em>*</em></label>
                                                                                    <select class="selectpicker select-custom" data-live-search="true">
                                                                                        <option data-tokens="Barisal">Barisal</option>
                                                                                        <option data-tokens="Dhaka">Dhaka</option>
                                                                                        <option data-tokens="Kulna">Kulna</option>
                                                                                        <option data-tokens="Rajshahi">Rajshahi</option>
                                                                                        <option data-tokens="Sylet">Sylet</option>
                                                                                        <option data-tokens="Chittagong">Chittagong</option>
                                                                                        <option data-tokens="Rangpur">Rangpur</option>
                                                                                        <option data-tokens="Maymanshing">Maymanshing</option>
                                                                                        <option data-tokens="Cox">Cox's Bazar</option>
                                                                                        <option data-tokens="Saint">Saint Martin</option>
                                                                                        <option data-tokens="Kuakata">Kuakata</option>
                                                                                        <option data-tokens="Sajeq">Sajeq</option>
                                                                                    </select>

                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="input-box">
                                                                                    <label>Post Code/Zip Code<em>*</em></label>
                                                                                    <input type="text" name="zipcode" class="info" placeholder="Zip Code">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                <div class="create-acc clearfix mtb-20">
                                                                                    <div class="acc-toggle">
                                                                                        <input type="checkbox" id="acc-toggle">
                                                                                        <label for="acc-toggle">Create an Account ?</label>
                                                                                    </div>
                                                                                    <div class="create-acc-body">
                                                                                        <div class="sm-des">
                                                                                            Create an account by entering the information below. If you are a returning customer please login at the top of the page.
                                                                                        </div>
                                                                                        <div class="input-box">
                                                                                            <label>Account password <em>*</em></label>
                                                                                            <input type="password" name="pass" class="info" placeholder="Password">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>



                                                                            */ ?>
                                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                <a href="#complete-order" aria-controls="checkout" role="tab" data-toggle="tab" class="btn-def btn2 no-link steps" data-step="to-3">Продолжить</a>
                                                                            </div>

                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 dn">
                                                            <div class="billing-details">
                                                                <div class="right-side">
                                                                    <div class="ship-acc clearfix">
                                                                        <div class="ship-toggle pb20">
                                                                            <input type="checkbox" id="ship-toggle">
                                                                            <label for="ship-toggle">Ship to a different address?</label>
                                                                        </div>
                                                                        <div class="ship-acc-body">
                                                                            <form action="#">
                                                                                <div class="row">
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>First Name <em>*</em></label>
                                                                                            <input type="text" name="namm" class="info" placeholder="First Name"> </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Last Name<em>*</em></label>
                                                                                            <input type="text" name="namm" class="info" placeholder="Last Name"> </div>
                                                                                    </div>
                                                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Company Name</label>
                                                                                            <input type="text" name="cpany" class="info" placeholder="Company Name"> </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Email Address<em>*</em></label>
                                                                                            <input type="email" name="email" class="info" placeholder="Your Email"> </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Phone Number<em>*</em></label>
                                                                                            <input type="text" name="phone" class="info" placeholder="Phone Number"> </div>
                                                                                    </div>
                                                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Country <em>*</em></label>
                                                                                            <select class="selectpicker select-custom" data-live-search="true">
                                                                                                <option data-tokens="Bangladesh">Bangladesh</option>
                                                                                                <option data-tokens="India">India</option>
                                                                                                <option data-tokens="Pakistan">Pakistan</option>
                                                                                                <option data-tokens="Pakistan">Pakistan</option>
                                                                                                <option data-tokens="Srilanka">Srilanka</option>
                                                                                                <option data-tokens="Nepal">Nepal</option>
                                                                                                <option data-tokens="Butan">Butan</option>
                                                                                                <option data-tokens="USA">USA</option>
                                                                                                <option data-tokens="England">England</option>
                                                                                                <option data-tokens="Brazil">Brazil</option>
                                                                                                <option data-tokens="Canada">Canada</option>
                                                                                                <option data-tokens="China">China</option>
                                                                                                <option data-tokens="Koeria">Koeria</option>
                                                                                                <option data-tokens="Soudi">Soudi Arabia</option>
                                                                                                <option data-tokens="Spain">Spain</option>
                                                                                                <option data-tokens="France">France</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Address <em>*</em></label>
                                                                                            <input type="text" name="add1" class="info mb-10" placeholder="Street Address">
                                                                                            <input type="text" name="add2" class="info mt10" placeholder="Apartment, suite, unit etc. (optional)"> </div>
                                                                                    </div>
                                                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Town/City <em>*</em></label>
                                                                                            <input type="text" name="add1" class="info" placeholder="Town/City"> </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>State/Divison <em>*</em></label>
                                                                                            <select class="selectpicker select-custom" data-live-search="true">
                                                                                                <option data-tokens="Barisal">Barisal</option>
                                                                                                <option data-tokens="Dhaka">Dhaka</option>
                                                                                                <option data-tokens="Kulna">Kulna</option>
                                                                                                <option data-tokens="Rajshahi">Rajshahi</option>
                                                                                                <option data-tokens="Sylet">Sylet</option>
                                                                                                <option data-tokens="Chittagong">Chittagong</option>
                                                                                                <option data-tokens="Rangpur">Rangpur</option>
                                                                                                <option data-tokens="Maymanshing">Maymanshing</option>
                                                                                                <option data-tokens="Cox">Cox's Bazar</option>
                                                                                                <option data-tokens="Saint">Saint Martin</option>
                                                                                                <option data-tokens="Kuakata">Kuakata</option>
                                                                                                <option data-tokens="Sajeq">Sajeq</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="input-box mb-20">
                                                                                            <label>Post Code/Zip Code<em>*</em></label>
                                                                                            <input type="text" name="zipcode" class="info" placeholder="Zip Code"> </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form">
                                                                        <div class="input-box">
                                                                            <label>Order Notes</label>
                                                                            <textarea placeholder="Notes about your order, e.g. special notes for delivery." class="area-tex"></textarea>
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
                                    <!-- Checkout are end-->
                                </div>
                                <div role="tabpanel" class="tab-pane  fade in" id="complete-order">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="checkout-payment-area">
                                                <div class="checkout-total mt20">
                                                    <h3>Ваш заказ</h3>
                                                    <form action="#" method="post">
                                                        <div class="table-responsive">
                                                            <table class="checkout-area table">
                                                                <thead>
                                                                <tr class="cart_item check-heading">
                                                                    <td class="ctg-type"> Наименование товара</td>
                                                                    <td class="cgt-des"> Цена</td>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr class="begin dn"></tr>

<?php
if($products){
    foreach($products as $pt){
        echo Yii::$app->view->renderFile(
            '@app/views/site/shortcodes/js-templates/cart-total-single-product.php',
            ['pt'=>$pt]
        );
    }
}
?>

                                                                <?php /*
                                                                <tr class="cart_item check-item prd-name">
                                                                    <td class="ctg-type"> Aenean sagittis × <span>1</span></td>
                                                                    <td class="cgt-des"> <i class="fa fa-rub" aria-hidden="true"></i> 1,026.00</td>
                                                                </tr>
                                                                <tr class="cart_item check-item prd-name">
                                                                    <td class="ctg-type"> Aenean sagittis × <span>1</span></td>
                                                                    <td class="cgt-des"> <i class="fa fa-rub" aria-hidden="true"></i> 1,026.00</td>
                                                                </tr>
                                                                */ ?>

                                                                <tr class="cart_item">
                                                                    <td class="ctg-type"> Цена (с учетом скидки)</td>
                                                                    <td class="cgt-des discount-price"> <i class="fa fa-rub" aria-hidden="true"></i> <span><?$zero?></span></td>
                                                                </tr>
                                                                <tr class="cart_item">
                                                                    <td class="ctg-type"> Ваша скидка составила</td>
                                                                    <td class="cgt-des discount-amount"> <i class="fa fa-rub" aria-hidden="true"></i> <span><?=$zero?></span></td>
                                                                </tr>
                                                                <tr class="cart_item dn">
                                                                    <td class="ctg-type">Shipping</td>
                                                                    <td class="cgt-des ship-opt">
                                                                        <div class="shipp">
                                                                            <input type="radio" id="pay-toggle" name="ship">
                                                                            <label for="pay-toggle">Flat Rate: <span>$03</span></label>
                                                                        </div>
                                                                        <div class="shipp">
                                                                            <input type="radio" id="pay-toggle2" name="ship">
                                                                            <label for="pay-toggle2">Free Shipping</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="cart_item">
                                                                    <td class="ctg-type crt-total"> Сумма заказа</td>
                                                                    <td class="cgt-des prc-total"> <i class="fa fa-rub" aria-hidden="true"></i> <span><?=$zero?></span> </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="payment-section mt-20 clearfix">
                                                    <div class="pay-toggle">
                                                        <form action="#">
                                                            <div class="pay-type-total dn">
                                                                <div class="pay-type">
                                                                    <input type="radio" id="pay-toggle01" name="pay">
                                                                    <label for="pay-toggle01">Direct Bank Transfer</label>
                                                                </div>
                                                                <div class="pay-type">
                                                                    <input type="radio" id="pay-toggle02" name="pay">
                                                                    <label for="pay-toggle02">Cheque Payment</label>
                                                                </div>
                                                                <div class="pay-type">
                                                                    <input type="radio" id="pay-toggle03" name="pay">
                                                                    <label for="pay-toggle03">Cash on Delivery</label>
                                                                </div>
                                                                <div class="pay-type">
                                                                    <input type="radio" id="pay-toggle04" name="pay">
                                                                    <label for="pay-toggle04">Paypal</label>
                                                                </div>
                                                            </div>
                                                            <div class="input-box mt-20">
                                                                <a
                                                                    class="btn-def btn2 no-link add-order"
                                                                    href="#"
                                                                    data-url="/ajax/add-order"
                                                                    method="post"
                                                                >
                                                                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                                                    подтвердить заказ
                                                                </a>
                                                            </div>
                                                            <?'<div class="res">result</div>'?>
                                                        </form>
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
    </div>
    <!--cart-checkout-area end-->

</div>
