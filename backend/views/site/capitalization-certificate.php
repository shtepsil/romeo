<?php

use backend\controllers\MainController as d;
use app\models\DocumentType;
use yii\helpers\Html;

$this->title = 'Оприходование сертификата';

// для функции number_format
$zero_one = Yii::getAlias('@zero_one');

/*
 * Класс cnce, это сокращение: capitalizatoin-certificate
*/

?>

<div class="wrap cnce">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="row">
            <div class="col-md-4 w-barcode">
               <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                <input
                    type="text"
                    class="form-control c-input-sm"
                    placeholder="Введите штрихкод"
                    title="Введите штрихкод"
                    value=""
                    name="barcode"
                    data-url="ajax/get-certificate-by-barcode"
                    method="post"
                    oninput="isNumeric(this)"
                />
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-xs debit-certificate"
                        data-url="ajax/debit-certificate"
                        method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Оприходовать
                </button>
            </div>
        </div><!-- row -->

        <br>

        <div class="row">
            <div class="col-md-12">
                <table class="table table1">
                    <tbody>
                    <tr>
                        <td class="i-s">Итого сумма по документу: <b><?=$zero_one?></b> руб.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div><!-- row -->

        <?=$alerts?>
    <!--    <div class="res">response</div>-->

        <div class="row">
            <div class="col-md-6">
                <table class="table table2">
                    <thead>
                    <tr>
                        <td>Штрихкод</td>
                        <td class="nom">Номинал сертификата</td>
                    </tr>
                    </thead>
                    <tbody><?=$tr_empty?></tbody>
                </table>
            </div>
        </div><!-- row -->
    </div><!-- container -->
</div>