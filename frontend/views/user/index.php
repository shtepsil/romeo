<?php

use backend\controllers\MainController as d;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->title = 'Настройки профиля';



$session = Yii::$app->session;
//$session->destroy();

//d::pre($this->params);

//d::prebl($session['myVar']);
//d::pri(date('Y-m-d H:i:s'),1555915745);

$this->params['breadcrumbs'][] = Html::encode($this->title);

$phone_confirm = true;
$email_confirm = true;
if($user_data['phone']){
    if(!$user_data['phone']['confirm']) $phone_confirm = false;
}
if($user_data['email']){
    if(!$user_data['email']['confirm']) $email_confirm = false;
}

// urpe - user_profile
?>
<div class="urpe">
    <!--breadcumb area start -->
    <div class="breadcumb-area breadcumb-2 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center dn">
                <h5><?= Html::encode($this->title) ?></h5> </div>

            <?=Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/'],
                'links' =>
                    isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [], ]);?>


        </div>
    </div>
    <!--breadcumb area end -->

    <!--service idea area are start -->
    <div class="idea-area  ptb-10">
        <div class="container">
            <div class="row">

                <?php /*

                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="idea-tab-menu">
                        <ul class="nav idea-tab" role="tablist">
                            <?php if(!$this->params['email_confirm']):?>
<!--                            <li role="presentation" class="active"><a href="#email_verification" aria-controls="creativity" role="tab" data-toggle="tab">Подтверждение Email</a></li>-->
                            <?else:?>
                            <li role="presentation" class="active"><a href="#creativity" aria-controls="creativity" role="tab" data-toggle="tab">Personal Info</a></li>
                            <li role="presentation"><a href="#ideas" aria-controls="ideas" role="tab" data-toggle="tab">Shipping Address</a></li>
                            <li role="presentation"><a href="#design" aria-controls="design" role="tab" data-toggle="tab">Billing Details</a></li>
                            <li role="presentation"><a href="#devlopment" aria-controls="devlopment" role="tab" data-toggle="tab">My Order</a></li>
                            <li role="presentation"><a href="#markenting" aria-controls="markenting" role="tab" data-toggle="tab">Payment Method</a></li>
                            <?endif?>
                        </ul>
                    </div>
                </div>

                */ ?>

                <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1">
                    <div class="idea-tab-content">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active user-data" id="creativity">
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>" data-csrf="csrf" />

                                <div class="col-md-12 col-sm-12 col-xs-12 h3 text-center">
                                    <?= Html::encode($this->title) ?>
                                </div>
                                <br>
                                <br>
                                <br>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Имя или Имя Отчество <em>*</em></label>
                                        <input type="text" name="first_name" value="<?=($first_name = $user_data['first_name'])?$first_name:''?>" data-change="<?=($first_name = $user_data['first_name'])?$first_name:''?>" class="info" placeholder="Имя или Имя Отчество">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Фамилия (можно пропустить)</label>
                                        <input type="text" name="last_name" value="<?=($last_name = $user_data['last_name'])?$last_name:''?>" data-change="<?=($last_name = $user_data['last_name'])?$last_name:''?>" class="info" placeholder="Фамилия">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Ваш Email<em>*</em> <span class="data-not-confirm type-email <?=($email_confirm)?'dn':''?>">(Email не подтвержден) <span class="glyphicon glyphicon-question-sign" id="popover" data-content="<?='Чтобы подтвердить вашу новую электронную почту, вам необходимо авторизоваться через новый Email'?>"></span></span></label>
                                        <input type="email" name="email" value="<?=($email = $user_data['email']['text'])?$email:$this->params['email']?>" data-change="<?=Yii::$app->session['user']['email']?>" data-value-email="<?=($user_data['email']['text'] AND ($user_data['email']['text'] != Yii::$app->session['user']['email']))?$user_data['email']['text']:''?>" class="info" placeholder="Email" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20 w-input-phone">
                                        <label>Номер телефона <span class="data-not-confirm type-phone <?=($phone_confirm)?'dn':''?>">(не подтвержден)<span class="glyphicon glyphicon-floppy-save phone-confirm" id="popover" data-content="<?='Подтвердить телефон'?>"><?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?></span></span></label>
                                        <input type="text" name="phone" value="<?=($phone = $user_data['phone']['text'])?$phone:''?>" data-change="<?=($phone = $user_data['phone']['text'])?$phone:''?>" class="info" placeholder="+7(___)___-__-__">
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-box mb-20">
                                        <div class="h4">Смена пароля</div>
                                    </div>
                                </div>

                                <div class="wrap-passwords">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-box mb-20">
                                            <label>Текущий пароль<em class="dn">*</em></label>
                                            <input type="password" name="current_password" class="info" placeholder="" value="" data-change="" />
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-box mb-20">
                                            <label>Новый пароль<em class="dn">*</em></label>
                                            <input type="password" name="new_password" class="info" placeholder="" value="" data-change="" />
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-box mb-20">
                                            <label>Повторите новый пароль<em class="dn">*</em></label>
                                            <input type="password" name="confirm_password" class="info" placeholder="" value="" data-change="" />
                                        </div>
                                    </div>
                                </div>

                                <?php /*

                                <div class="col-md-6 col-sm-6 col-xs-12">
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

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Town/City <em>*</em></label>
                                        <input type="text" name="add1" class="info" placeholder="Town/City">
                                    </div>
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
                                        <input type="text" name="zipcode" class="info" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Address <em>*</em></label>
                                        <input type="text" name="add1" class="info mb-10" placeholder="Street Address">
                                        <input type="text" name="add2" class="info mt10" placeholder="Apartment, suite, unit etc. (optional)">
                                    </div>
                                </div>
                                <div class=" col-md-6 col-sm-8 col-xs-12">
                                    <div class="checkbox checkbox-2">
                                        <label> <small>
                                                <input name="signup" type="checkbox">I wish to subscribe to the The clothing newsletter.
                                            </small> </label>
                                        <br>
                                        <label> <small>
                                                <input name="signup" type="checkbox">I have read and agree to the <a href="#">Privacy Policy</a>
                                            </small> </label>
                                    </div>
                                </div>

                            */ ?>

                                <div class="col-md-12">
                                    <?=$alerts?>
                                </div>
                                <br>
                                <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                                    <a
                                            href="#"
                                            class="btn-def btn2 no-link save-user-data"
                                            data-login="<?=Yii::$app->session['user']['email']?>"
                                            data-url="/ajax/save-user-data"
                                            method="post"
                                    >
                                        Сохранить
                                        <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                    </a>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 text-left w-del-user">
                                    <a href="#" class="delete-user no-link" data-toggle="modal" data-target="#confirm-delete">Удалить аккаунт</a>

<!--                                        <button class="btn btn-primary test">Тест</button>-->
                                </div>

                            </div>









                            <?php /*
                            <div role="tabpanel" class="tab-pane fade in" id="ideas">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>First Name <em>*</em></label>
                                        <input type="text" name="namm" class="info" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Last Name<em>*</em></label>
                                        <input type="text" name="namm" class="info" placeholder="Last Name">
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Email Address<em>*</em></label>
                                        <input type="email" name="email" class="info" placeholder="Your Email">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Phone Number<em>*</em></label>
                                        <input type="text" name="phone" class="info" placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
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

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Town/City <em>*</em></label>
                                        <input type="text" name="add1" class="info" placeholder="Town/City">
                                    </div>
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
                                        <input type="text" name="zipcode" class="info" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Address <em>*</em></label>
                                        <input type="text" name="add1" class="info mb-10" placeholder="Street Address">
                                        <input type="text" name="add2" class="info mt10" placeholder="Apartment, suite, unit etc. (optional)">
                                    </div>
                                </div>
                                <div class="col-xs-12 text-right">
                                    <a class="btn-def btn2" href="#">Save</a>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="design">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>First Name <em>*</em></label>
                                        <input type="text" name="namm" class="info" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Last Name<em>*</em></label>
                                        <input type="text" name="namm" class="info" placeholder="Last Name">
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Email Address<em>*</em></label>
                                        <input type="email" name="email" class="info" placeholder="Your Email">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Phone Number<em>*</em></label>
                                        <input type="text" name="phone" class="info" placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
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

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Town/City <em>*</em></label>
                                        <input type="text" name="add1" class="info" placeholder="Town/City">
                                    </div>
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
                                        <input type="text" name="zipcode" class="info" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Address <em>*</em></label>
                                        <input type="text" name="add1" class="info mb-10" placeholder="Street Address">
                                        <input type="text" name="add2" class="info mt10" placeholder="Apartment, suite, unit etc. (optional)">
                                    </div>
                                </div>
                                <div class="col-xs-12 text-right">
                                    <a class="btn-def btn2" href="#">Save</a>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="devlopment">
                                <form action="#" method="post">
                                    <div class="table-responsive">
                                        <table class="checkout-area table text-center">
                                            <thead>
                                            <tr class="cart_item check-heading">
                                                <td class="ctg-type"> Product</td>
                                                <td class="cgt-des"> Total</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="cart_item check-item prd-name">
                                                <td class="ctg-type"> Aenean sagittis × <span>1</span></td>
                                                <td class="cgt-des"> $1,026.00</td>
                                            </tr>
                                            <tr class="cart_item">
                                                <td class="ctg-type"> Subtotal</td>
                                                <td class="cgt-des">$1,026.00</td>
                                            </tr>
                                            <tr class="cart_item">
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
                                                <td class="ctg-type crt-total"> Total</td>
                                                <td class="cgt-des prc-total"> $ 1.029.00 </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-right">
                                        <a class="btn-def btn2" href="#">Save</a>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="markenting">
                                <div class="col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Card Type <em>*</em></label>
                                        <select class="selectpicker select-custom" data-live-search="true">
                                            <option data-tokens="paypal">Paypal</option>
                                            <option data-tokens="visa">visa</option>
                                            <option data-tokens="master-card">master-card</option>
                                            <option data-tokens="discover">discover</option>
                                            <option data-tokens="payneor">payneor</option>
                                            <option data-tokens="skrill">skrill</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Card Number<em>*</em></label>
                                        <input type="text" name="email" class="info" placeholder="Card Number">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Security Code<em>*</em></label>
                                        <input type="text" name="phone" class="info" placeholder="Security Code">
                                    </div>
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Month <em>*</em></label>
                                        <select class="selectpicker select-custom" data-live-search="true">
                                            <option data-tokens="Januray">Januray</option>
                                            <option data-tokens="February">February</option>
                                            <option data-tokens="March">March</option>
                                            <option data-tokens="April">April</option>
                                            <option data-tokens="May">May</option>
                                            <option data-tokens="June">June</option>
                                            <option data-tokens="July">July</option>
                                            <option data-tokens="August">August</option>
                                            <option data-tokens="September">September</option>
                                            <option data-tokens="Ocotober">Ocotober</option>
                                            <option data-tokens="November">November</option>
                                            <option data-tokens="December">December</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-box mb-20">
                                        <label>Year<em>*</em></label>
                                        <select class="selectpicker select-custom" data-live-search="true">
                                            <option data-tokens="2016">2016</option>
                                            <option data-tokens="2017">2017</option>
                                            <option data-tokens="2018">2018</option>
                                            <option data-tokens="2019">2019</option>
                                            <option data-tokens="2020">2020</option>
                                            <option data-tokens="2021">2021</option>
                                            <option data-tokens="2022">2022</option>
                                            <option data-tokens="2023">2023</option>
                                            <option data-tokens="2024">2024</option>
                                            <option data-tokens="2025">2025</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="payment-btn-area mt-20">
                                    <div class="col-md-4 col-sm-4 col-xs-12 text-left">
                                        <a class="btn-def btn2" href="#">Pay Now</a>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12 text-center">
                                        <a class="btn-def btn2" href="#">Cancel Order</a>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12 text-right">
                                        <a class="btn-def btn2" href="#">Continue</a>
                                    </div>
                                </div>
                            </div>
                            <? */ ?>
                        </div>
                    </div>
                </div>
            </div>
            <?'<div class="res">result</div>'?>
            <?'<div class="res2">result2</div>'?>
        </div>
    </div>
    <!--service idea area are end -->

    <?=Yii::$app->view->renderFile('@app/views/site/shortcodes/modal-confirm-delete.php');?>

</div><!-- /user_profile -->


