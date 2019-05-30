<?php // todo страница "Товарный учет" строка tr

use backend\controllers\MainController as d;

$content = (($nomenclature_name)?$nomenclature_name:'').
    (($color)?', '.$color:'').
    (($design)?', '.$design:'');

?>
<tr class="section<?=$number_section?> <?=$dn?>" data-visible="<?=$dn?>">
    <td class="w-checkbox">
        <!--        номер раздела-->
        <input type="hidden" name="section" value="1">
        <input type="hidden" name="provider_row" value="<?=$provider?>">
        <input class="checkbox" type="checkbox" name="checkbox">
    </td>
    <!--    номер строки-->
    <td class="counter1"><?=$str_dock?></td>
    <!--    док-->
    <td class="dock1"><?=$document_id?></td>
    <!--    описание-->
    <?php // Наименование номерклатуры товара, цвет, рисунок ?>
    <td class="description1" data-nomenclature-name="<?=$nomenclature_name?>"><?=$content?></td>
    <!--    размер производителя-->
    <td class="size-manufacturer1"><?=$manufacturer_size?></td>
    <!--    дата поступления-->
    <td class="receipt-date1"><?=d::changeDate($document_date,'format','dd.mm.yyyy')?></td>
    <!--    штрихкод-->
    <td class="td-barcode1" id="barcode"><?=$barcode?></td>
    <!--    себестоимость-->
    <td class="cost-price1"><?=$cost_price?></td>
    <!--    розничная цена-->
    <td class="retail-price1"><?=$retail_price?></td>
    <!--    остаток на учете-->
    <td class="account-balance1"><?=$remainder_list?></td>
    <!--    количество-->
    <td class="quantity1" id="quantity" data-quantity="0"><?=($quantity != '')?$quantity:'1'?></td>
    <!--    остаток факт-->
    <td class="remainder-fact1"><?=$remainder_fact?></td>
</tr>