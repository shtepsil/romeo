<?php
// todo Страница Товарный учет, шаблон tr строки, для сборки строк section3, по кнопке "Остатки Размер"
?>
<script type="html/tpl" id="section3">
<tr class="section3">
    <td></td>
    <!--    номер строки-->
    <td class="counter1">0</td>
    <td>{dock}</td>
    <!--    описание-->
    <td class="description1">{description}</td>
    <!--    размер производителя-->
    <td class="size-manufacturer1">{size_manufacturer}</td>
    <!--    дата поступления-->
    <td class="receipt-date1">{receipt_date}</td>
    <!--    штрихкод-->
    <td class="td-barcode1">{barcode}</td>
    <!--    себестоимость-->
    <td class="cost-price1">{cost_price}</td>
    <!--    розничная цена-->
    <td class="retail-price1">{retail_price}</td>
    <!--    остаток на учете-->
    <td class="account-balance1">{account_balance}</td>
    <!--    количество-->
    <td class="quantity1">{quantity}</td>
    <!--    остаток факт-->
    <td class="remainder-fact1">{remainder_fact}</td>
</tr>
</script>