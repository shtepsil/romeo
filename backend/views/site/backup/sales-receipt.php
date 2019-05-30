<?php

/* @var $this yii\web\View */
use backend\controllers\MainController as d;
use yii\helpers\Html;
use common\models\User;
use common\components\AddArrayHelper;
use yii\helpers\ArrayHelper;

$this->title = 'Товарный чек';

// для функции number_format
$ko = Yii::getAlias('@ko');
$fl = Yii::getAlias('@fl');
$th = Yii::getAlias('@th');

// значение для пустых числовых значений
$zero = Yii::getAlias('@zero');

//d::pre(date('Y-m-d H:i:s',1534786775));

?>
<script>var KkmServerAddIn = {};</script>
<div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

<form action="ajax/get-info-by-barcode" method="POST" class="sales-reciept">

    <input type="button" name="but1" value="empty1">
    <input type="button" name="but2" value="empty2">
    <input type="button" name="but3" value="empty3">
    <input type="button" name="but4" value="empty4">
    <br>
    <br>

    <div class="ress">результат</div>

    <br>

    <div class="document-head">
        <!-- row1 -->
        <div class="row row1">
            <div class="col-md-2">
                <select name="payment_method_bank_card" data-required="1" class="form-control c-input-sm" title="Введите способ оплаты" onchange="calculationDocument()">
                    <option value="0">Введите способ оплаты</option>
                    <option value="1">Наличные</option>
                    <option value="2">Банковская карта</option>
                </select>
            </div>

            <!--
              По умолчанию сюда подставляется тип документа из БД
              из таблицы "типы документов"
            -->
            <input type="hidden" name="document_type" value="<?=$dock_type['code']?>" />

            <div class="col-md-2">
                <input type="text" name="document_id" class="form-control c-input-sm" placeholder="Введите код документа для возрата" title="Введите код документа для возрата" value="" onkeyup="isNumeric(this)" />
            </div>

            <div class="col-md-2">
                <input type="text" name="counterparty_document_comment" class="form-control c-input-sm" placeholder="Введите комментарий" title="Введите комментарий" />
            </div>

            <div class="col-md-2">
                <input type="text" name="name_buyers_document_comment" class="form-control c-input-sm" placeholder="Введите ФИО, паспорт при возрате" title="Введите ФИО, паспорт при возрате" />
            </div>

            <div class="col-md-2">
                <input type="text" name="buyer_phone_number" class="form-control c-input-sm" placeholder="тел. покупателя" title="тел. покупателя" />
            </div>

            <div class="col-md-2">
                <input type="text" name="buyer_email" class="form-control c-input-sm" placeholder="email покупателя" title="email покупателя" />
            </div>

        </div>
        <!-- /row1 -->

        <!-- row2 -->
        <div class="row row2 top-info">
            <div class="col-md-8 w-discount-card-info">
                <div class="card-content dn">
                    <?php // Текстовая информация, выводимая если в поле ввода штрихкода введен штрихкод дисконтной карты ?>
                    Карта:
                        <span data-dtcd="dtcd" id="barcode" class="span-barcode"><b></b></span>
                        <input type="hidden" name="discount_card" value="" />
                    <span class="span-fio"><b></b></span>
                    <span class="span-phone"><b></b></span>
                    Накопление за текущий год: <span
                            data-dtcd="dtcd"
                            id="accumulation_previous_year"
                            class="span-accumulation-previous-year"><b></b></span>&nbsp;
                    Накопление за предыдущий год: <span
                            data-dtcd="dtcd"
                            id="accumulation_current_year"
                            class="span-accumulation-current-year"><b></b></span>&nbsp;

                    <?php // Сумма покупок в текущем году, необходимая для сохранения скидки (выводится в случае если накопление по карте за текущий год не превысило порога текущей скидки и определяется как разность между текущим порогом скидки и накоплением по карте за текущий год) ?>

                    <? // Сумма покупок в текущем году: <span class="span-amount-purchases-current-year"><b></b></span>,&nbsp; ?>
                    <?php //необходимая для увеличения скидки (выводится в случае если текущая скидка по дисконтной карте меньше максимальной и определяется как разность между следующим порогом скидки см. лист алгоритмы и накоплением по карте за текущий год)?>
                    Текущая скидка по карте: <span class="span-current-discount-card"><b></b></span><span><b></b></span>
                    <?php // Возврат, обмен по карте - суммы возврата и обмена. Значение нужно для вычисления нового значения скидки  ?>
                    <span class="dn" data-dtcd="dtcd" id="return_exchange_by_card"><b></b></span>
                </div>
            </div>

            <div class="col-md-4 rd">
                Расчет документа
                <!-- npo - "Недобор при отоваривании" -->
                <div class="npo dn">
                    Недобор при отоваривании: <b></b>
                </div>
                <!-- sko - "Сумма к оплате" -->
                <div class="sko dn">
                    <input type="hidden" name="payment_amount" value="<?=$zero?>" />
                	<span></span>
                	<b></b>
                </div>
                <!-- kvn - "К возврату наличными" -->
                <div class="kvn dn">
                    <input type="hidden" name="cash_repayment_amount" value="<?=$zero?>" />
                    К возврату наличными: <b></b>
                </div>
                <!-- kvnbk - "К возврату на банковскую карту" -->
                <div class="kvnbk dn">
                    <input type="hidden" name="amount_of_refund_to_bank_card" value="<?=$zero?>" />
                    К возврату на банковскую карту: <b></b>
                </div>
            </div>
    <!--        <div class="col-md-3">-->
    <!---->
    <!--        </div>-->
        </div>
        <!-- /row2 -->

        <!-- row3 -->
        <div class="row row3">

            <div class="col-md-2 w-barcode">
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                <input type="text" name="barcode" class="form-control c-input-sm barcode" placeholder="Ввод штрихкода" title="Ввод штрихкода" value="" onkeyup="isNumeric(this)" />
            </div>


            <div class="col-md-2">
                <select name="action" class="form-control c-input-sm" title="Выберите и примените условия акции">
                    <option value="0">Выберите и примените условия акции</option>
                    <option disabled>Пока пусто</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="text" name="order_code_on_the_site" class="form-control c-input-sm" placeholder="Код заказа на сайте" title="Код заказа на сайте">
            </div>

            <div class="col-md-2">
                <input type="text" name="promotional_code" class="form-control c-input-sm" placeholder="Промокод" title="Промокод" />
            </div>

            <div class="col-md-4">
                &nbsp;&nbsp;
<!--                <button type="button" data-type="sales-receipt" class="btn btn-danger btn-xs delete">Удалить выбранные строки</button>&nbsp;-->
    <!--            <button type="button" class="btn btn-secondary btn-xs">Отмена</button>-->
                <button
                    type="button"
                    class="btn btn-info btn-xs kkm"
                    data-type="RegisterCheck"
                    data-type-check=""
                    <?='disabled'?>
                >Чек ККМ</button>&nbsp
                <?php /*
                <br>
                <label for="obsh">ЦенаБезСкидки</label><br>
                <input type="text" id="obsh" name="obsh" value="100">
                <br>
                <label for="obsh2">СуммаСтроки</label><br>
                <input type="text" id="obsh2" name="obsh2" value="0.01">

                <br>
                <label for="cash">Наличная оплата</label><br>
                <input type="text" id="cash" name="cash" value="0">

                <br>
                <label for="card">Сумма электронной оплаты</label><br>
                <input type="text" id="card" name="card" value="0.01">
                */ ?>


                <button type="button" class="btn btn-success btn-xs save" action="ajax/save-sales-receipt" method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Сохранить
                </button>&nbsp;
            </div>

        </div>
        <!-- /row3 -->
    </div><!-- /document-head -->



<!--    <br>-->
<!--    <div class="res">response</div>-->

    <?=$alerts?>

    <!-- row4 -->
    <div class="row row4">
        <?
        /*
         * Блок, в котором динамически будут отображаться
         * Строки раздела 1 документа Товарный чек
         * Отображает выбытие товара или выдачу на обмен
        */
        ?>
        <div class="wrap-document1">

            <div class="h6"></div>

            <table class="table document1 small">
                <tr class="t-header">
                    <th colspan="15"><span style="color:red;">Раздел 1</span> Выбытие товара или выдача на обмен</th>
                </tr>
                <tr class="thead">
                    <td></td>
                    <td>Работник</td>
                    <td>Содержание</td>
                    <td>Штрихкод</td>
                    <td>Розн.<br>цена</td><? // Количество ?>
                    <td>Шт.</td><? // Розничная цена ?>
                    <td>Сумма</td><? // Сумма без скидок ?>
                    <td>Скидка<br>карта</td><? // Скидка по дисконтной карте ?>
                    <td>Авт.<br>скидка</td><? // Автоматическая скидка ?>
                    <td>Ручная<br>скидка</td><? // Ручная скидка ?>
                    <td>Сумма<br>скидок</td>
                    <td>Сумма за<br>вычетом<br>скидок</td>
                    <td>Скидка<br>сертиф.</td><? // Скидка по подарочным сертификатам ?>
                    <td>Итого<br>скидки</td>
                    <td class="text-right">Сумма<br>продажи</td>
                </tr>
                <?=$tr_empty?>
                <tr>
                    <td class="w-checkbox">
                        <!--1        номер раздела-->
                        <input type="hidden" name="section" value="1">
                        <!--        <input class="checkbox" type="checkbox" name="checkbox"><br>-->
                        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
                    </td>
                    <!--1    список работников-->
                    <td class="table-input">
                        <select class="form-control c-input-sm" name="employee_code" title="Выберите работника">
                            <option value="">Выберите работника</option>
                            <option value="8">Галерин Николай Сергеевич</option>
                            <option value="6" selected="">Бражников Сергей Юрьевич</option>
                            <option value="7">Николаенко Дмитрий Валерьевич</option>
                            <option value="9">Никитина Любовь Александровна</option>
                        </select>    </td>
                    <!--    описание-->
                    <td class="description" data-info-kkm="Номенклатура первая, размер производителя 1, 0011001000012">Номенклатура первая, узор 1, размер производителя 1</td>
                    <!--1    штрихкод-->
                    <td class="td-barcode1" id="barcode">0011001000012</td>
                    <!--1    текущая цена-->
                    <td class="retail-price1" id="retail_price_on_day_of_sale">12345.00</td>
                    <!--1    количество-->
                    <td class="quantity1" id="quantity">123</td>
                    <!--    сумма без скидок-->
                    <td class="amount-without-discounts1">12345.00</td>
                    <!--1    скидка по диконтной карте-->
                    <td class="discount-on-a-discount-card1" id="discount_on_discount_card">0</td>
                    <!--1    автоматическая скидка-->
                    <td class="automatic-discount1" id="automatic_discount">12</td>
                    <!--1    ручная скидка-->
                    <td class="table-input1">
                        <input type="text" name="manual_discount" class="form-control c-input-sm" value="12345" title="Ручная скидка, руб." onkeyup="
            isNumeric(this,'n,')

    " oninput="changeManualDiscount(this);" data-default="0.00">
                    </td>
                    <!--    сумма скидок-->
                    <td class="sum-of-discounts1">0.00</td>
                    <!--    сумма за вычетом скидок-->
                    <td class="amount-after-deduction-of-discounts1">1000.00</td>
                    <!--1    скидка по подарочным сертификатам-->
                    <td class="discount-on-gift-certificates1" id="discount_on_gift_certificates">0.00</td>
                    <!--    итого скидки-->
                    <td class="total-discounts1">0.00</td>
                    <!--1    сумма продажи-->
                    <td class="text-right sales-amount1" id="sale_amount">1000.00</td>
                </tr>
                <tr class="tfoot1">
                    <!--                        <td></td>-->
                    <td class="text-left" colspan="6"><b>Подитог</b></td>
                    <td class="p_sbs1"><b><?=$zero?></b></td>
                    <td colspan="3"></td>
                    <td class="p_ss1"><b><?=$zero?></b></td>
                    <td data-dtcd="dtcd" id="p-szvs1" class="p-szvs1"><b><?=$zero?></b></td>
                    <td class="p_sps1"><b><?=$zero?></b></td>
                    <td class="p_is1"><b><?=$zero?></b></td>
                    <td class="p_sp1 text-right"><b><?=$zero?></b></td>
                </tr>
                <tr class="indent"><th colspan="15"></th></tr>
                <tr class="t-header">
                    <th colspan="15">
                        <span style="color:red;">Раздел 2</span> Продажа сертификата
                    </th>
                </tr>
                <?=$tr_empty?>
                <tr>
                    <td class="w-checkbox" colspan="2">
                        <input type="hidden" name="section" value="2">
                        <!--        <input class="checkbox" type="checkbox" name="checkbox">-->
                        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
                    </td>
                    <td class="description">Сертификат,Подарочный</td>
                    <td class="td-barcode24" id="barcode">0004567891018</td>
                    <td class="nominal">5000</td>
                    <td>1</td>
                    <td class="sbs2">5000.00</td>
                    <td class="text-right sp2" colspan="8">5000.00</td>
                </tr>
                <tr class="tfoot2">
                    <!--                        <td class="w-checkbox"></td>-->
                    <td colspan="6" class="text-left"><b>Подитог</b></td>
                    <td class="p_sbs2"><b><?=$zero?></b></td>
                    <td class="text-right p_sp2" colspan="8"><b><?=$zero?></b></td>
                </tr>
                <tr class="indent"><th colspan="15"></th></tr>
                <tr class="t-header">
                    <th colspan="15">
                        <span style="color:red;">Раздел 3</span> Обмен, возврат товара от покупателя
                    </th>
                </tr>
                <?=$tr_empty?>
                <tr>
                    <td class="w-checkbox">
                        <!--1        номер раздела-->
                        <input type="hidden" name="section" value="3">
                        <!--        <input class="checkbox" type="checkbox" name="checkbox"><br>-->
                        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
                    </td>
                    <!--    Надписи "дисконтная карта","банковская карта","документа контрагента, комментарий", "ФИО, документ покупателя, коммнетрий" -->
                    <td class="ptmd" data-discount-card="" data-payment-method="1" data-return-exchange-by-card="">
                        <input type="hidden" name="employee_code" value="6">
                        Бражников Сергей Юрьевич
                    </td>
                    <!--    Наименование номенклатуры товара, цвет, рисунок/узор, размер производителя -->
                    <td class="description" data-info-kkm="Номенклатура первая, размер производителя 1, 0011001000012">
                        <!--    Код документа по которому произошло выбытие товара -->
                        <span class="document-id">175</span>,
                        Наличные, Номенклатура первая, узор 1, размер производителя 1
                    </td>
                    <!--1    Штрихкод -->
                    <td class="td-barcode3" id="barcode">0011001000012</td>
                    <!--1    Розничная -->
                    <td class="retail-price3" id="retail_price_on_day_of_sale">1000.00</td>
                    <!--1    Количество -->
                    <td class="quantity3" id="quantity">
                        <span>1</span>
                        <input type="hidden" value="10">
                    </td>
                    <!--    Сумма без скидок -->
                    <td class="amount-without-discounts3">1000.00</td>
                    <!--1    Скидка по дисконтной карте -->
                    <td class="discount-on-a-discount-card3" id="discount_on_discount_card">0</td>
                    <!--1    Автоматическая скидка -->
                    <td class="automatic-discount3" id="automatic_discount">0</td>
                    <!--1    Ручная скидка -->
                    <td class="manual-discount3" id="manual_discount" data-common="120.00">12.00</td>
                    <!--    Сумма скидок -->
                    <td class="sum-of-discounts3">0.00</td>
                    <!--    Сумма за вычетом скидок -->
                    <td class="amount-after-deduction-of-discounts3">1000.00</td>
                    <!--1    Скидка по подарочнымм сертификатам -->
                    <td class="discount-on-gift-certificates3" data-common="0.00" id="discount_on_gift_certificates">0.00</td>
                    <!--    Итого скидки -->
                    <td class="total-discounts3">0.00</td>
                    <!--1    Сумма продажи -->
                    <td class="text-right sales-amount3" id="sales_amount" data-common="10000.00">1000.00</td>
                </tr>
                <tr class="tfoot3">
                    <!--                        <td class="w-checkbox"></td>-->
                    <td class="text-left" colspan="6"><b>Подитог</b></td>
                    <td class="p_sbs3"><b><?=$zero?></b></td>
                    <td colspan="3"></td>
                    <td class="p_ss3"><b><?=$zero?></b></td>
                    <td data-dtcd="dtcd" id="p-szvs3" class="p-szvs3"><b><?=$zero?></b></td>
                    <td class="p-sps31"><b><?=$zero?></b></td>
                    <td class="p_is3"><b><?=$zero?></b></td>
                    <td class="text-right p-sp32"><b><?=$zero?></b></td>
                </tr>
                <tr class="indent"><th colspan="15"></th></tr>
                <tr class="t-header">
                    <th colspan="15">
                        <span style="color:red;">Раздел 4</span> Отоваривание сертификата
                    </th>
                </tr>
                <?=$tr_empty?>
                <tr>
                    <td class="w-checkbox" colspan="2">
                        <input type="hidden" name="section" value="4">
                        <!--        <input class="checkbox" type="checkbox" name="checkbox">-->
                        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
                    </td>
                    <td class="description">Отоваривание подарочного сертификата<br>0000-00-00</td>
                    <td class="td-barcode24" id="barcode">0004567891014</td>
                    <td class="nominal">3000.00</td>
                    <td>1</td>
                    <td class="text-right sps4" colspan="9">3000.00</td>
                </tr>
                <tr class="tfoot4">
                    <!--                        <td class="w-checkbox"></td>-->
                    <td colspan="6" class="text-left"><b>Подитог</b></td>
                    <!--                        <td class="text-right p-sps4"><b><?=$zero?></b></td>-->
                    <td class="text-right p-sps4" colspan="9"><b><?=$zero?></b></td>
                </tr>
            </table>

            <?
//            d::pre($tr_empty);
            ?>

        </div>
    </div>
    <!-- /row4 -->

</form>