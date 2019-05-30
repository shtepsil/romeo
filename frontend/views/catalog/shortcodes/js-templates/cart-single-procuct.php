<?php
// todo Элемент товара в выпадающем меню просмотра корзины в верхней панели

use backend\controllers\MainController as d;
use yii\helpers\Html;

if(!$pt){
    $pt = [
        'id'=>'',
        'barcode'=>'',
        'path_img'=>'',
        'name'=>'',
        'new_price'=>'',
    ];
}
if($pt['id'] != ''){
    if($pt['path_img'] == 'img_default') $img = '/images/product/01.jpg';
    else $img = \Yii::getAlias('@photos_rel').$pt['path_img'];
}else $img = '{path_img}';

?>
<?if($pt['id'] == ''):?><script type="html/tpl" id="cart-single-top"><?endif;?>
<div class="cart-single-wraper" data-key="<?=($pt['id'] != '')?$pt['id']:'{product_key}'?>" data-barcode="<?=($pt['barcode'] != '')?$pt['barcode']:'{barcode}'?>">
    <div class="cart-img">
        <a href="#">
            <img src="<?=$img?>" />
        </a>
    </div>
    <div class="cart-content">
        <div class="cart-name"> <a href="#"><?=($pt['name'] != '')?$pt['name']:'{name}'?></a> </div>
        <div class="cart-price" data-price="<?=($pt['new_price'] != '')?$pt['new_price']:''?>"> <i class="fa fa-rub" aria-hidden="true"></i> <span class="price"><?=($pt['new_price'] != '')?number_format($pt['new_price'],2,'.',''):'{new_price}'?></span> </div>
<!--                                                    <div class="cart-qty"> Количество: <span>1</span> </div>-->
    </div>
    <div class="remove">
        <button
            class="delete-single-product"
            data-product-key="<?=($pt['id'] != '')?$pt['id']:'{product_key}'?>"
            onclick="removeProductFromBucket(this)"
            data-url="/ajax/delete-single-product"
            method="post"
        >
            <i class="zmdi zmdi-close"></i>
            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
        </button>
    </div>
</div>
<?if($pt['id'] == ''):?></script><?endif;?>