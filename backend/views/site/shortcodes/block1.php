<?php // todo Страница Товарный учет - тестовые tr блока 1 ?>
<?php

$bs = [
//    '0141009000209','0141009000209','0141009000209',
//    '0141009000179','0141009000162','0141009000155',
//    '0141009000193','0141009000179','0141009000155',

    '0141009000209','0141009000193','0141009000209',
    '0141009000193','0141009000209','0141009000193',
    '0141009000209','0141009000193','0141009000209',

//    '0141009000179',
//
//    '0141009000193'
];
$i = 1;
foreach($bs as $b):?>
<tr class="section1">
    <td class="w-checkbox">
        <!--        номер раздела-->
        <input type="hidden" name="section" value="1">
        <input type="hidden" name="provider" value="<?='2'?>">
        <input class="checkbox" type="checkbox" name="checkbox">
    </td>
    <!--    номер строки-->
    <td class="counter1"><?=$i?></td>
    <!--    док-->
    <td class="dock1">0</td>
    <!--    описание-->
    <td class="description1">Наименование номерклатуры товара, цвет, рисунок</td>
    <!--    размер производителя-->
    <td class="size-manufacturer1">70-152</td>
    <!--    дата поступления-->
    <td class="receipt-date1">2019-02-26</td>
    <!--    штрихкод-->
    <td class="td-barcode1"><?=$b?></td>
    <!--    себестоимость-->
    <td class="cost-price1">250</td>
    <!--    розничная цена-->
    <td class="retail-price1">350.00</td>
    <!--    остаток на учете-->
    <td class="account-balance1"></td>
    <!--    количество-->
    <td class="quantity1"><?=$i?></td>
    <!--    остаток факт-->
    <td class="remainder-fact1"></td>
</tr>
<? $i++; endforeach;/*?>
<tr class="section1">
    <td class="w-checkbox">
        <!--        номер раздела-->
        <input type="hidden" name="section" value="1">
        <input class="checkbox" type="checkbox" name="checkbox">
    </td>
    <!--    номер строки-->
    <td class="counter">1</td>
    <!--    док-->
    <td class="dock1">0</td>
    <!--    описание-->
    <td class="description1">Наименование номерклатуры товара, цвет, рисунок</td>
    <!--    размер производителя-->
    <td class="size-manufacturer1">70-152</td>
    <!--    дата поступления-->
    <td class="receipt-date1">2017-05-16</td>
    <!--    штрихкод-->
    <td class="td-barcode1">0011001000029</td>
    <!--    себестоимость-->
    <td class="cost-price1">450.00</td>
    <!--    розничная цена-->
    <td class="retail-price1">650.00</td>
    <!--    остаток на учете-->
    <td class="account-balance1">3</td>
    <!--    количество-->
    <td class="quantity1">9</td>
    <!--    остаток факт-->
    <td class="remainder-fact1">5</td>
</tr>
<tr class="section1">
    <td class="w-checkbox">
        <!--        номер раздела-->
        <input type="hidden" name="section" value="1">
        <input class="checkbox" type="checkbox" name="checkbox">
    </td>
    <!--    номер строки-->
    <td class="counter">1</td>
    <!--    док-->
    <td class="dock1">0</td>
    <!--    описание-->
    <td class="description1">Наименование номерклатуры товара, цвет, рисунок</td>
    <!--    размер производителя-->
    <td class="size-manufacturer1">75-157</td>
    <!--    дата поступления-->
    <td class="receipt-date1">2013-07-13</td>
    <!--    штрихкод-->
    <td class="td-barcode1">0031002000019</td>
    <!--    себестоимость-->
    <td class="cost-price1">750.00</td>
    <!--    розничная цена-->
    <td class="retail-price1">950.00</td>
    <!--    остаток на учете-->
    <td class="account-balance1">3</td>
    <!--    количество-->
    <td class="quantity1">5</td>
    <!--    остаток факт-->
    <td class="remainder-fact1">2</td>
</tr>
*/ ?>