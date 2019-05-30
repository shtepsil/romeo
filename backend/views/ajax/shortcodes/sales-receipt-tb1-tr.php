<?php // todo Раздел 1 Выбытие товара или выдача на обмен

use yii\helpers\Html;

use common\components\AddArrayHelper;

?>
<tr class="section1">
    <td class="w-checkbox">
<!--1        номер раздела-->
        <input type="hidden" name="section" value="1" />
<!--        <input class="checkbox" type="checkbox" name="checkbox"><br>-->
        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
    </td>
<!--1    список работников-->
    <td class="table-input">
        <?php
        // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
        $items = \yii\helpers\ArrayHelper::map($arr_p['users'],'id','fio');
        $options = [
            'prompt'      => 'Выберите работника',
            'title'       => 'Выберите работника',
            'class'       => 'form-control c-input-sm employees',
        ];
        ?>
        <?= Html::dropDownList('employee_code', Yii::$app->user->id, $items, $options); ?>
    </td>
<!--    описание-->
    <td
        class="description"
        data-info-kkm="<?=$arr_p['for_kkm']?>"
    ><?=$arr_p['description']?></td>
<!--1    штрихкод-->
    <td class="td-barcode1" id="barcode"><?=$arr_p['barcode']?></td>
    <!--1    текущая цена-->
    <td class="retail-price1" id="retail_price_on_day_of_sale"><?=$arr_p['retail_price']?></td>
<!--1    количество-->
    <td class="quantity1" id="quantity"><?=$arr_p['quantity']?></td>
<!--    сумма без скидок-->
    <td class="amount-without-discounts1"><?=$arr_p['amount_without_discounts']?></td>
<!--1    скидка по диконтной карте-->
    <td class="discount-on-a-discount-card1" id="discount_on_discount_card"><?=$arr_p['discount_on_a_discount_card']?></td>
<!--1    автоматическая скидка-->
    <td class="automatic-discount1" id="automatic_discount"><?=$arr_p['automatic_discount']?></td>
<!--1    ручная скидка-->
    <td class="table-input1">
        <input type="text" name="manual_discount" class="form-control c-input-sm" title="Ручная скидка, руб." onkeyup="
                isNumeric(this,'n,')
<? // isNumeric(this,'n,') ?>

" oninput="changeManualDiscount(this);" data-default="<?=$arr_p['sum_of_discounts']?>" />
    </td>
<!--    сумма скидок-->
    <td class="sum-of-discounts1"><?=$arr_p['sum_of_discounts']?></td>
<!--    сумма за вычетом скидок-->
    <td class="amount-after-deduction-of-discounts1"><?=$arr_p['amount_after_deduction_of_discounts']?></td>
<!--1    скидка по подарочным сертификатам-->
    <td class="discount-on-gift-certificates1" id="discount_on_gift_certificates"><?=$arr_p['discount_on_gift_certificates']?></td>
<!--    итого скидки-->
    <td class="total-discounts1"><?=$arr_p['total_discounts']?></td>
<!--1    сумма продажи-->
    <td class="text-right sales-amount1" id="sale_amount"><?=$arr_p['sales_amount']?></td>
</tr>