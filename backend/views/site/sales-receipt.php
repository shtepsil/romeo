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
$zeroz = Yii::getAlias('@zero,');

//d::pre(date('Y-m-d H:i:s',1534786775));

?>
<br>
<script>var KkmServerAddIn = {};</script>
<div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

<form action="ajax/get-info-by-barcode" method="POST" class="sales-reciept">
<?//=$but?>

    <div class="document-head">
        <!-- row1 -->
        <div class="row row1">
            <div class="col-md-2">
                <select name="payment_method_bank_card" data-required="1" class="form-control c-input-sm" title="Выберите способ оплаты" onchange="calculationDocument()">
                    <option value="0">Выберите способ оплаты</option>
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
                <input type="text" name="document_id" class="form-control c-input-sm" placeholder="Введите код документа для возрата" title="Введите код документа для возрата" value="" onkeyup="isNumeric(this);resetPageSRFromDocementID()" />
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
                    Накопление <?=date('Y')?>: <span
                            data-dtcd="dtcd"
                            id="accumulation_current_year"
                            class="span-accumulation-current-year"><b></b></span>&nbsp;
                    Накопление <?=(date('Y')-1)?>: <span
                            data-dtcd="dtcd"
                            id="accumulation_previous_year"
                            class="span-accumulation-previous-year"><b></b></span>&nbsp;

                    <?php // Сумма покупок в текущем году, необходимая для сохранения скидки (выводится в случае если накопление по карте за текущий год не превысило порога текущей скидки и определяется как разность между текущим порогом скидки и накоплением по карте за текущий год) ?>

                    <? // Сумма покупок в текущем году: <span class="span-amount-purchases-current-year"><b></b></span>,&nbsp; ?>
                    <?php //необходимая для увеличения скидки (выводится в случае если текущая скидка по дисконтной карте меньше максимальной и определяется как разность между следующим порогом скидки см. лист алгоритмы и накоплением по карте за текущий год)?>
                    Текущая скидка по карте:
                    <span
                        class="span-current-discount-card"
                        data-dtcd="dtcd"
                        id="current_discount_card"
                    ><b></b></span><span><b></b></span>
                    <?php // Возврат, обмен по карте - суммы возврата и обмена. Значение нужно для вычисления нового значения скидки  ?>
                    Возврат по карте:
                    <span class="" data-dtcd="dtcd" id="return_exchange_by_card"><b></b></span>
                    Сумма до порога:
                    <span class="" data-dtcd="dtcd" id="amount_to_threshold"><b></b></span>
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
                	<span class="span1"></span>
                	<b class="b1"></b>
                    <span class="span2">Сдача:</span>
                    <b class="b2"><?=$zeroz?></b>
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

            <div class="col-md-2">
                <input type="text" name="cash_of_buyer" class="form-control c-input-sm" placeholder="Наличные покупателя" title="Наличные покупателя" onkeyup="isNumeric(this,'n,')" />
            </div>

            <div class="col-md-2">
                &nbsp;&nbsp;
<!--                <button type="button" data-type="sales-receipt" class="btn btn-danger btn-xs delete">Удалить выбранные строки</button>&nbsp;-->
    <!--            <button type="button" class="btn btn-secondary btn-xs">Отмена</button>-->
                <button
                    type="button"
                    class="btn btn-info btn-xs kkm"
                    data-type="RegisterCheck"
                    data-type-check=""
                    data-shift="0"
                    data-current-user="<?=\app\components\User::getFio()?>"
                    <?='onclick="checkStatusKkm(this)"'?>
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


<!---->

    <?'<br><div class="res">response</div>'?>

    <br>
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
                <tr class="t-header s1">
                    <th colspan="15"><span style="color:red;">Раздел 1</span> Выбытие товара или выдача на обмен</th>
                </tr>
                <?
                    $tr_empty_class = str_replace("%section%", "1", $tr_empty);
                    echo $tr_empty_class;
                ?>
                <tr class="tfoot1 s1">
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
                <tr class="t-header s2">
                    <th colspan="15">
                        <span style="color:red;">Раздел 2</span> Продажа сертификата
                    </th>
                </tr>
                <?
                    $tr_empty_class = str_replace("%section%", "2", $tr_empty);
                    echo $tr_empty_class;
                ?>
                <tr class="tfoot2 s2">
                    <!--                        <td class="w-checkbox"></td>-->
                    <td colspan="6" class="text-left"><b>Подитог</b></td>
                    <td class="p_sbs2"><b><?=$zero?></b></td>
                    <td class="text-right p_sp2" colspan="8"><b><?=$zero?></b></td>
                </tr>
                <tr class="indent"><th colspan="15"></th></tr>
                <tr class="t-header s3">
                    <th colspan="15">
                        <span style="color:red;">Раздел 3</span> Обмен, возврат товара от покупателя
                    </th>
                </tr>
                <?
                    $tr_empty_class = str_replace("%section%", "3", $tr_empty);
                    echo $tr_empty_class;
                ?>
                <tr class="tfoot3 s3">
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
                <tr class="t-header s4">
                    <th colspan="15">
                        <span style="color:red;">Раздел 4</span> Отоваривание сертификата
                    </th>
                </tr>
                <?
                    $tr_empty_class = str_replace("%section%", "4", $tr_empty);
                    echo $tr_empty_class;
                ?>
                <tr class="tfoot4 s4">
                    <!--                        <td class="w-checkbox"></td>-->
                    <td colspan="12" class="text-left"><b>Подитог</b></td>
                    <!--                        <td class="text-right p-sps4"><b><?=$zero?></b></td>-->
                    <td class="p-sps4"><b><?=$zero?></b></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <?
//            d::pre($tr_empty);
            ?>

        </div>
    </div>
    <!-- /row4 -->

</form>