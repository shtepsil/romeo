<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 04.06.2018
 * Time: 16:34
 */

use backend\controllers\MainController as d;
use app\models\DocumentType;
use yii\helpers\Html;

$this->title = 'Кассовый отчет';

// для функции number_format
$zero = Yii::getAlias('@zero');


/*
 * Класс cr, это сокращение: cash-report
*/

?>
<script>var KkmServerAddIn = {};</script>
<div class="wrap cr">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>
        <input type="hidden" name="current_user" value="<?=\app\components\User::getFio()?>" />

    <!--    <div class="row test">-->
    <!--        <div class="col-md-4">-->
    <!--            <form class="form-group" role="search" action="ajax/debug" method="post">-->
    <!--                <div class="form-group">-->
    <!--                    <input type="text" class="form-control time" placeholder="Search">-->
    <!--                </div>-->
    <!--                <button type="button" class="btn btn-success t-but">Отправить</button>-->
    <!--            </form>-->
    <!--            <div class="res-test">response</div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <br>-->
    <!--    <br>-->

        <div class="row">
            <div class="col-md-4 shift">
                <button class="btn btn-primary btn-xs open-shift"
                    onclick="OpenShift('0','<?=\app\components\User::getFio()?>')"
                    <?='disabled'?>>
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Открыть смену
                </button>
                <button class="btn btn-primary btn-xs close-shift"
                    onclick="GetDataKKT()" <?='disabled'?>
                    data-user="<?=\app\components\User::getFio()?>"
                >
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Закрыть смену
                </button>
                <button class="btn btn-primary btn-xs close-shift"
                    onclick="balanceCheck()">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Отчет ККМ
                </button>
            </div>
            <div class="col-md-3">
                <input
                    type="text"
                    id="date"
                    class="form-control c-input-sm date-report"
                    placeholder="Введите дату"
                    title="Введите дату"
                    value=""
                    name="date_report"
                    data-current-date="<?=Yii::$app->getFormatter()->asDate(time())?>"
                    <?php // 2018-08-28 ?>
                />
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-xs get-report"
                        action="ajax/cash-report"
                        method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Вывести отчет
                </button>
            </div>
        </div><!-- row -->

        <br>

        <div class="row">

            <table class="table table1">
                <tbody>
                    <tr>
                        <td class="son">Сумма<br>оплата наличными: <b><?=$zero?></b> руб.</td>
                        <td class="svzn">Сумма<br>возврата наличными: <b><?=$zero?></b> руб.</td>
                        <td class="svrn">Сумма<br>выручка наличными: <b><?=$zero?></b> руб.</td>
                        <td class="sos">Сумма<br>отоварено сертификатов: <b><?=$zero?></b> руб.</td>
                    </tr>
                    <tr>
                        <td class="sobk">Сумма<br>оплата банковскими картами: <b><?=$zero?></b> руб.</td>
                        <td class="svnbk">Сумма<br>возврата на банковские карты: <b><?=$zero?></b> руб.</td>
                        <td class="svbk">Сумма<br>выручка банковскими картами: <b><?=$zero?></b> руб.</td>
                        <td class="ivzd">Итого<br>выручка за (<span>дд.мм.гггг</span>): <b><?=$zero?></b> руб.</td>
                    </tr>
                </tbody>
            </table>

        </div><!-- row -->

        <?=$alerts?>
    <!--    <div class="res">response</div>-->

        <hr>

        <div class="row">

            <table class="table table2">
                <thead>
                <tr>
                    <td>Код документа</td>
                    <td>Время составления документа</td>
                    <td>Документ контрагента, комментарий</td>
                    <!--    <td>Работник</td>-->
                    <td>ФИО, документ покупателя, комментарий</td>
                    <td>Дисконтная карта</td>
                    <!--    <td>Банковская карта</td>-->
                    <td>Сумма оплата наличными</td>
                    <td>Сумма оплата банковской картой</td>
                    <td>Сумма возврата наличными</td>
                    <td>Сумма возврата на банковскую карту</td>
                </tr>
                </thead>
                <tbody><?=$tr_empty?></tbody>
            </table>

        </div><!-- row -->
    </div><!-- container -->
</div>