<?php // todo Страница Кассовый отчет row html table ?>
<tr>
<!--    Код документа-->
    <td><?=$cr['id']?></td>
<!--    Время составления документа-->
    <td><?=$cr['document_time']?></td>
<!--    Документ контрагента, комментарий-->
    <td><?=$cr['counterparty_document_comment']?></td>
<!--    <td>Работник</td>-->
<!--    ФИО, документ покупателя, комментарий-->
    <td><?=$cr['name_buyers_document_comment']?></td>
<!--    Дисконтная карта-->
    <td><?=$cr['discount_card']?></td>
<!--    <td>Банковская карта</td>-->
<!--    Сумма оплата наличными-->
    <td class="amount-payment-in-cash"><?=$cr['cash']?></td>
<!--    Сумма оплата банковскими катами-->
    <td class="amount-payment-by-bank-cards"><?=$cr['bank_card']?></td>
<!--    Сумма возврата наличными-->
    <td class="cash-repayment-amount"><?=$cr['cash_repayment_amount']?></td>
<!--    Сумма возврата на банковскую карту-->
    <td class="amount-of-refund-to-bank-cards"><?=$cr['amount_of_refund_to_bank_card']?></td>
</tr>