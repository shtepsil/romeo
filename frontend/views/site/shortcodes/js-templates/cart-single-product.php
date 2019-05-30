<?php
// todo Строка товара на странице корзина (первая вкладка)

use backend\controllers\MainController as d;
use yii\helpers\Html;

$zero = Yii::getAlias('@zero');
$zero_one = Yii::getAlias('@zero_one');
$img_src = Yii::getAlias('@photos_rel');
$old_price = '';
$new_price = '';

if(!$pt['js']){
    if($pt['path_img']){
        if($pt['path_img'] == 'img_default')
            $img_src = Yii::getAlias('@web').'/images/product/01.jpg';
        else $img_src .= $pt['path_img'];
    }else $img_src .= '{path_img}';

    if($pt['old_price'] == $pt['new_price']){
        $old_price = $pt['old_price'];
        $new_price = $zero;
    }else{
        $old_price = $pt['old_price'];
        $new_price = $pt['new_price'];
    }

}else $img_src = '{path_img}';

?>
<?if($pt['js']):?><script type="html/tpl" id="cart-single-list"><?endif;?>
<tr class="cart_item" data-key="<?=($pt['id'])?$pt['id']:'{product_key}'?>">
    <td class="item-img">
        <a href="#" class="no-link"><img src="<?=$img_src?>" alt=""> </a>
    </td>
    <td class="item-title">
        <input type="hidden" name="barcode" value="<?=$pt['barcode']?>" />
        <a href="#" class="no-link"><?=($pt['name'])?$pt['name']:'{name}'?></a>
    </td>
    <td class="total-price"><strong> <i class="fa fa-rub" aria-hidden="true"></i> <span class="old-p"><?=($old_price != '')?number_format($old_price,2,'.',''):'{old_price}'?></span></strong></td>
    <td><strong><i class="fa fa-rub" aria-hidden="true"></i> <span class="new-p"><?=($new_price != '')?number_format($new_price,2,'.',''):'{new_price}'?></span></strong></td>
    <td><strong><?=($pt['discount'] != '')?$pt['discount']:'{discount}'?>%</strong></td>
    <td class="remove-item">
        <button
            data-product-key="<?=($pt['id'])?$pt['id']:'{product_key}'?>"
            onclick="removeProductFromBucket(this)"
            data-url="/ajax/delete-single-product"
            method="post"
        >
            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
            <i class="fa fa-trash-o"></i>
        </button>
    </td>
</tr>
<?if($pt['id'] == ''):?></script><?endif;?>