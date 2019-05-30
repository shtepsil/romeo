<?php

use frontend\controllers\MainController as d;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\components\CustomBreadcrumbs as CBreadcrumbs;

$this->title = 'Контакты';

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
                'itemtype' => '//schema.org/BreadcrumbList'
            ],
            'links' =>
                isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]);?>


    </div>
</div>
<!--breadcumb area end -->

<!-- contacts-area start-->
<div class="contacts-area ptb-50">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="heading-title heading-style pos-rltv mb-50 text-center">
                    <h5 class="uppercase"><?=Html::encode($this->title)?></h5>
                </div>
            </div>

            <div class="contacts-wrap">

                <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1">
                    <div class="col-md-4">

                        <?=Html::img('@web/images/contacts/enter.jpg',['class'=>'img-enter','alt'=>'Изображение'])?>

                    </div>
                    <div class="col-md-8">
                        <p>Формы оплаты: Наличные, Банковские карты VISA, MasterCard <?=Html::img('@web/images/contacts/cards.png',['class'=>'img-cards','alt'=>'Изображение'])?></p>
                        <p>Адрес: Кемерово, проспект Советский, 35</p>
                        <p>Телефон (торговый зал): +7-908-930-03-30</p>
                    </div>

                    <div class="col-md-12">
                        <div class="h3">Часы работы:</div>

                        <table class="table table-hover table-striped" style="overflow: hidden">
                            <tr><td>Понедельник</td><td>10-00</td><td>20-00</td></tr>
                            <tr><td>Вторник</td><td>	 10-00</td><td>20-00</td></tr>
                            <tr><td>Среда</td><td>		 10-00</td><td>20-00</td></tr>
                            <tr><td>Четверг</td><td>	 10-00</td><td>20-00</td></tr>
                            <tr><td>Пятница</td><td>	 10-00</td><td>20-00</td></tr>
                            <tr><td>Суббота</td><td>	 10-00</td><td>20-00</td></tr>
                            <tr><td>Воскресенье</td><td>	 10-00</td><td>20-00</td></tr>
                        </table>
                    </div>

                    <div class="col-md-12 gis-map">
                        <br>
                        <div class="h3">Местоположение магазина на карте 2GIS:</div>
                        <div id="2gis-map" style="width:100%; height:300px"></div>
<?php /*
                        <a class="dg-widget-link" href="//2gis.ru/kemerovo/firm/704215723357571/center/86.07247352600099,55.358221677123915/zoom/16?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=bigMap">
                            Посмотреть на карте Кемерова
                        </a>
                        <div class="dg-widget-link">
                            <a href="//2gis.ru/kemerovo/center/86.072467,55.358077/zoom/16/routeTab/rsType/bus/to/86.072467,55.358077╎Ромео, торговый дом?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=route">
                                Найти проезд до Ромео, торговый дом
                            </a>
                        </div>

                        <script charset="utf-8" src="//widgets.2gis.com/js/DGWidgetLoader.js"></script>
 */ ?>
                        <script src="//maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
<script charset="utf-8">
    /*
    new DGWidgetLoader({
        "width":"100%",
        "height":300,
        "borderColor":"#a3a3a3",
//        "center": [55.35896572, 86.03663921],
        "pos":{
//            "lat":55.358221677123915,
//            "lon":86.07247352600099,
            "lat":55.35822167,
            "lon":87.10094858,
            "zoom":16,
        },
        "opt":{
            "city":"kemerovo"
        },
        "org":[{
            "id":"704215723357571"
        }]
    });
    */
    var map;
    DG.then(function () {
        map = DG.map('2gis-map', {
            center: [55.35864859, 86.07228041],
            zoom: 16,
            scrollWheelZoom:false
        });

        var content = 'Советский проспект, 35<br>Центральный район, Кемерово';

        DG.marker([55.35807531, 86.07225895]).addTo(map).bindPopup(content);

    });
</script><noscript style="color:#c00;font-size:16px;font-weight:bold;">Виджет карты использует JavaScript. Включите его в настройках вашего браузера.</noscript>

                    </div>
                </div>
            </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- contacts-area end-->
