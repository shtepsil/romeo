<?php // todo Раздел 3 Обмен, возврат товара от покупателя ?>
<tr class="section3">
    <td class="w-checkbox">
<!--1        номер раздела-->
        <input type="hidden" name="section" value="3" />
<!--        <input class="checkbox" type="checkbox" name="checkbox"><br>-->
        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
    </td>
<!--    Надписи "дисконтная карта","банковская карта","документа контрагента, комментарий", "ФИО, документ покупателя, коммнетрий" -->
    <td
        class="ptmd"
        <? // атрибут для JS. Собираем строки где есть штрихкод дисконтной карты
           // т.е. атрибут "data-discount-card" не пуст ?>
        data-discount-card="<?=$arr_p['discount_card']?>"
        <? // атрибут для JS. Собираем строки, у которых способ оплаты "1" (наличные) ?>
        data-payment-method="<?=$arr_p['payment_method_bank_card']?>"
        <? // атрибут для JS. Возврат, обмен по карте ?>
        data-return-exchange-by-card="<?=$arr_p['return_exchange_by_card']?>"
    >
        <input type="hidden" name="employee_code" value="<?=$arr_p['row_user_id']?>" />
        <?=$arr_p['fio']?>
    </td>
<!--    Наименование номенклатуры товара, цвет, рисунок/узор, размер производителя -->
    <td
            class="description"
            data-info-kkm="<?=$arr_p['for_kkm']?>"
    >
        <!--    Код документа по которому произошло выбытие товара -->
        <span class="document-id"><?=$arr_p['document_id']?></span>,
        <?=$arr_p['description']?>
    </td>
<!--1    Штрихкод -->
    <td class="td-barcode3" id="barcode"><?=$arr_p['barcode']?></td>
    <!--1    Розничная -->
    <td class="retail-price3" id="retail_price_on_day_of_sale"><?=$arr_p['retail_price']?></td>
<!--1    Количество -->
    <td class="quantity3" id="quantity">
        <span><?=$arr_p['quantity']?></span>
        <input type="hidden" value="<?=abs($arr_p['total'])?>" />
    </td>
<!--    Сумма без скидок -->
    <td class="amount-without-discounts3"><?=$arr_p['amount_without_discounts']?></td>
<!--1    Скидка по дисконтной карте -->
    <td class="discount-on-a-discount-card3"id="discount_on_discount_card"><?=$arr_p['discount_on_a_discount_card']?></td>
<!--1    Автоматическая скидка -->
    <td class="automatic-discount3" id="automatic_discount"><?=$arr_p['automatic_discount']?></td>
<!--1    Ручная скидка -->
    <td class="manual-discount3" id="manual_discount" data-common="<?=$arr_p['common_manual_discount']?>"><?=$arr_p['manual_discount']?></td>
<!--    Сумма скидок -->
    <td class="sum-of-discounts3"><?=$arr_p['sum_of_discounts']?></td>
<!--    Сумма за вычетом скидок -->
    <td class="amount-after-deduction-of-discounts3"><?=$arr_p['amount_after_deduction_of_discounts']?></td>
<!--1    Скидка по подарочнымм сертификатам -->
    <td class="discount-on-gift-certificates3" data-common="<?=$arr_p['common_discount_on_gift_certificates']?>" id="discount_on_gift_certificates"><?=$arr_p['discount_on_gift_certificates']?></td>
<!--    Итого скидки -->
    <td class="total-discounts3"><?=$arr_p['total_discounts']?></td>
<!--1    Сумма продажи -->
    <td class="text-right sales-amount3" id="sales_amount"  data-common="<?=$arr_p['common_sales_amount']?>"><?=$arr_p['sales_amount']?></td>
</tr>