<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AdminAppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use \yii\widgets\Menu;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use \backend\controllers\MainController as d;

AdminAppAsset::register($this);

$type_user = Yii::$app->user->role;

$menu_items = [

    'user'=>[
//        ['label' => 'Debugg', 'url' => ['debugg'], 'active' =>  d::active('debugg')],
//				['label' => 'Форма поиска', 'url' => ['search'], 'active' =>  d::active('search')],
        ['label' => 'Поиск чека', 'url' => ['check-search'], 'active' =>  d::active('check-search')],
        ['label' => 'Товарный чек', 'url' => ['sales-receipt'], 'active' =>  d::active('sales-receipt')],
//        ['label' => 'Открыть документ', 'url' => ['open-document'], 'active' =>  d::active('open-document')],
        ['label' => 'Кассовый отчет', 'url' => ['cash-report'], 'active' =>  d::active('cash-report')],
        ['label' => 'Заказы', 'url' => ['orders'], 'active' =>  d::active('orders')],
    ],

    'admin'=>[
        ['label' => 'Поступление товара', 'url' => ['goods-receipt'], 'active' =>  d::active('goods-receipt')],
        ['label' => 'Номенклатура товара', 'url' => ['product-nomenclature'], 'active' =>  d::active('product-nomenclature')],
        ['label' => 'Оприходование товара', 'url' => ['capitalization-goods'], 'active' =>  d::active('capitalization-goods')],
        ['label' => 'Товарный учет', 'url' => ['commodity-accounting'], 'active' =>  d::active('commodity-accounting')],
        ['label' => 'Выгрузка этикеток', 'url' => ['unloading-labels'], 'active' =>  d::active('unloading-labels')],
//				['label' => 'Возврат комитенту', 'url' => ['return-to-principal'], 'active' =>  d::active('return-to-principal')],
//				['label' => 'Возврат брака поставщику', 'url' => ['return-marriage-to-supplier'], 'active' =>  d::active('return-marriage-to-supplier')],
//				['label' => 'Списание товара', 'url' => ['write-off-goods'], 'active' =>  d::active('write-off-goods')],
//				['label' => 'Инвентаризация', 'url' => ['inventory'], 'active' =>  d::active('inventory')]
    ],

    'sadmin'=>[
        ['label' => 'Справочники', 'url' => ['reference-books'], 'active' =>  d::active('reference-books')],
        ['label' => 'Загрузка Excel CDB', 'url' => ['export-excel-c-d-b'], 'active' =>  d::active('export-excel-c-d-b')],
//				['label' => 'Товарная группа', 'url' => ['product-group'], 'active' =>  d::active('product-group')],
        ['label' => 'Работники', 'url' => ['workers'], 'active' =>  d::active('workers')],
        ['label' => 'Оприходование сертификата', 'url' => ['capitalization-certificate'], 'active' =>  d::active('capitalization-certificate')],
//				['label' => 'Дисконтные карты', 'url' => ['discount-cards'], 'active' =>  d::active('discount-cards')],
        ['label' => 'Загрузка файлов Excel', 'url' => ['import-files'], 'active' =>  d::active('import-files')],
//        ['label' => 'Открыть документ', 'url' => ['open-document'], 'active' =>  d::active('open-document')],
    ],

    'webmaster'=>[
        ['label' => 'Отправка Email', 'url' => ['email'], 'active' =>  d::active('email')],
    ],
];

$dropdownMenuItems = [];// для сборки элементов массива для меню

switch($type_user){
    case 'user':
        $logo_str = 'Привет пользователь!';
        $dropdownMenuItems = $menu_items['user'];
        break;
    case 'admin':
        $logo_str = 'Привет, Админ!';
        $dropdownMenuItems = array_merge($menu_items['user'],$menu_items['admin']);
        break;
    default:
        $logo_str = 'Привет, Супер Админ!';
        if(Yii::$app->user->username != 'webmaster'){
            $dropdownMenuItems = array_merge($menu_items['user'],$menu_items['admin'],$menu_items['sadmin']);
        }else{
            $dropdownMenuItems = array_merge($menu_items['user'],$menu_items['admin'],$menu_items['sadmin'],$menu_items['webmaster']);
        }
}

$co = Yii::$app->getView()->params['count_orders'];

Yii::$app->name = $logo_str;

//d::pre(Yii::$app->user->username);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    // Html::csrfMetaTags() - для безопасности форм
    echo Html::csrfMetaTags()
    ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php // d::pri(Yii::$app->request); ?>
<?php // d::pre(Yii::$app->request->headers['host']); ?>
<div class="wrap
    <?=(Yii::$app->request->url == '/admin/login')?'auth-body':''?>
    <?=(Yii::$app->request->url == '/admin/' || Yii::$app->request->url == '/admin')?'main-body':''?>
">

    <nav class="navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".js-navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/" target="_blank" style="padding:15px 15px;">Перейти на сайт</a>
            </div>
            <div class="collapse navbar-collapse js-navbar">

                <?
                if (Yii::$app->user->isGuest) {
                    $menu = [
//                        ['label' => 'Войти', 'url' => ['/site/login']]
                    ];
                }else {
                    $menu = [
//                        ['label' => 'Тест ККМ', 'url' => ['/kkm']],
//                        ['label' => 'Gii', 'url' => ['/gii']],
                        ['label' => 'На главную', 'url' => ['/cash-report']],
                        [
                            'label' => 'Меню',
                            'url' => ['#'],
                            'items' => $dropdownMenuItems,
                            'options' => [
                                'id' => 'menu',
                                'class' => 'dropdown',
                                'data-id' => 'menu',
                            ],
                            'template' => '
                            <a  
                                id="drop1" 
                                href="{url}" 
                                class="dropdown-toggle" 
                                data-toggle="dropdown"
                            >{label}</a>',
                        ],
                        [
                            'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                            'url' => ['site/logout'],
                            'options' => [
                                'class' => 'logout',
                            ],
                            'template' =>
                                Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                    'Выйти (' . Yii::$app->user->identity->username . ')',
                                    ['class' => 'btn btn-link logout']
                                )
                                . Html::endForm()
                        ],
                    ];
                }

                echo Menu::widget([
                    'items' => $menu,
                    'activeCssClass'=>'active',
                    'firstItemCssClass'=>'first-item2',
                    'lastItemCssClass' =>'last-item3',
                    'options' => [
                        'id'=>'menu',
                        'class' => 'menu nav navbar-nav navbar-right',
                        'data-id'=>'menu',
                    ],
//                    'itemOptions'=>['class'=>'myclass', 'style'=>'background: #444;'],
                    'submenuTemplate' => "\n<ul class='dropdown-menu' role='menu'>\n{items}\n</ul>\n",
                ]); ?>

            </div>
        </div>
    </nav>

	<?if($_SERVER['HTTP_HOST'] != 'romeo-man.ru'):?>
    <style type="text/css">
        .test-site{
            background: #1CD000;
            padding: 0px 13px 4px;
            color: white;
            font-size: 14px;
            border-radius: 0px 0px 8px 8px;
            position: fixed;
            top: 50px;
            left: 10px;
            z-index: 1030;
        }
    </style>
    <div class="test-site">Тестовый сайт</div>
	<?endif?>

    <div class="container">

        <?if(!Yii::$app->user->isGuest):?>
        <div class="count-orders-online">Заказов: <span><?=($co != 0)?$co:'0'?></span></div>
        <?endif?>
        <?'<br><br><br><br><div class="res">result</div>'?>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?/*
        <a href="<?=Url::home()?>">
            <?=Html::img('@web/images/design.png',['alt'=>'На главную','width'=>'100'])?>
        </a>
        */?>
        <br>
        <?= $content ?>
    </div>
</div>

<footer class="footer <?=(Yii::$app->request->url == '/admin/login')?'auth-footer':''?>">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
