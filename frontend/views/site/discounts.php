<?php

use yii\helpers\Html;
use frontend\components\CustomBreadcrumbs as CBreadcrumbs;

$this->title = 'Моя скидка';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'coption' => [
        'serial_number' => '2',
    ]
];

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

<!-- discounts-area start-->
<div class="discounts-area ptb-50">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase"><?=Html::encode($this->title)?></h5>
                </div>
            </div>
            <div class="discounts-wrap">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    <p><?=Html::img('@web/images/discount_card.jpg',['class'=>'img-discount-card','alt'=>'Изображение'])?></p>
                    <br>
                    <div class="h4">Моя скидка</div>

                    <p>Начать пользоваться персональными скидками можно получив накопительную дисконтную карту, которая выдается при совершении покупки на сумму от 5000 рублей.</p>
                    <p>Минимальная скидка по дисконтной карте составляет 5%.</p>
                    <p>Следующие уровни скидок зависят от накопленной суммы покупок по данной дисконтной карте: более 15000 – 10 %, более 30000 – 15 %, более 50000 – 20 %.</p>
                    <p>Скидка по накопительной дисконтной карте представляет собой скидку постоянного и активного покупателя, уровень скидки определяется накопленной суммой за текущий и предыдущий календарные годы.</p>
                    <p>Магазин оставляет за собой право изменять условия предоставления скидок.</p>
                    <p><?=Html::img('@web/images/best-price.jpg',['class'=>'best-price','alt'=>'Изображение'])?></p>

                    <div class="h4">Лучшая цена</div>

                    <p>Мы постоянно заботимся о том, чтобы цены, по которым Вы совершаете покупки в Ромео, были лучшими. Лучшими, применительно к таким вещам как одежда, это конечно не обязательно самыми дешевыми, а лучшими именно в сравнении. И поэтому мы готовы с радостью отреагировать на Ваши замечания относительно розничных цен на идентичный товар и предложить Вам цену не выше, чем у наших коллег. Вы можете позвонить нам: Торговый дом «Ромео», +7 (908) 930-03-30, г. Кемерово, Советский просп., 35. Акции и сезонные распродажи у нас проходят регулярно.</p>
                    <p>Мы заботимся не только о цене, но и о комфортных условиях для совершения покупки, вежливом и ненавязчивом обслуживании, умении продавца-консультанта при необходимости оказать помощь в подборе одежды по стилю и фигуре, потому что мы уверены, что это важно для наших покупателей.</p>
                    <p>Здесь мы также хотим напомнить о наших скидках для постоянных покупателей. Активный покупатель позволяет магазину быстрее обновлять ассортимент и обеспечивает себе лучшие условия покупки. Зарегистрируйтесь на сайте чтобы получать эксклюзивные предложения.</p>
                    <p>И еще. Если Вас интересует цена ниже розничной, то Вы можете отправить заявку на групповую покупку, условия которой будут зависеть от вида товара и суммы покупки.</p>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- discounts-area end-->
