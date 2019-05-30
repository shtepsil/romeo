<?php
// todo Строка товара на странице корзина (третья вкладка)

use backend\controllers\MainController as d;

$zero = Yii::getAlias('@zero');
$old_price = '';
$new_price = '';

if($pt['old_price'] != '' OR $pt['new_price'] != ''){
    if($pt['old_price'] == $pt['new_price']){
        $old_price = $pt['old_price'];
        $new_price = $pt['old_price'];
    }else{
        $old_price = $pt['old_price'];
        $new_price = $pt['new_price'];
    }
}

//d::pri($pt);

/*
 * Так как в table присутствуют ещё tr с общей информацией
 * Класс pt добавлен для стиля шрифта только для добавленных товаров
 */
?>

<?if($pt['js']):?><script type="html/tpl" id="cart-single-total-list"><?endif;?>
<tr class="cart_item check-item prd-name" data-key="<?=($pt['id'])?$pt['id']:'{product_key}'?>">
    <td class="ctg-type pt">
        <input type="hidden" name="barcode" value="<?=$pt['barcode']?>" />
        <?=($pt['name'])?$pt['name']:'{name}'?>
    </td>
    <td class="cgt-des pt">
        <i class="fa fa-rub" aria-hidden="true"></i>
        <?=($new_price != '')?number_format($new_price,2,'.',''):'{new_price}'?>
    </td>
</tr>
<?if($pt['id'] == ''):?></script><?endif;?>
