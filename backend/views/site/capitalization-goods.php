<?php

use backend\controllers\MainController as d;
use app\models\DocumentType;
use yii\helpers\Html;

$this->title = 'Оприходование товара';

// для функции number_format
$zero = Yii::getAlias('@zero,');
$zero_one = Yii::getAlias('@zero_one');

/*
 * Класс cngs, это сокращение: capitalizatoin-goods
*/

?>

<div class="wrap cngs">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>
        <div class="row">
            <div class="col-md-3 w-barcode">
                <input type="hidden" name="document_type" value="<?=$dock_type?>">
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                <input
                    type="text"
                    class="form-control c-input-sm"
                    placeholder="Введите штрихкод"
                    title="Введите штрихкод"
                    value=""
                    name="barcode"
                    data-url="ajax/get-product-by-barcode"
                    method="post"
                    oninput="isNumeric(this)"
                />
            </div>
            <div class="col-md-3">
                <input
                    type="text"
                    class="form-control c-input-sm"
                    placeholder="Введите комментарий"
                    title="Введите комментарий"
                    value=""
                    name="description"
                />
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-xs debit-product"
                        data-url="ajax/debit-product"
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
                        <td class="i-qy">Итого количество: <b><?=$zero_one?></b> шт.</td>
                        <td class="i-cpe">Итого себестоимость: <b><?=$zero?></b> руб.</td>
                        <td class="i-rpe">Итого сумма розничных цен<br>по документу: <b><?=$zero?></b> руб.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div><!-- row -->

        <?=$alerts?>
    <!--    <div class="res">response</div>-->

        <div class="row">
            <div class="col-md-12">
                <table class="table table2">
                    <thead>
                    <tr>
                        <td class="content">Содержание</td>
                        <td>Штрихкод</td>
                        <td>Шт.</td>
                        <td>Себестоимость</td>
                        <td>Розн.<br>цена</td>
                    </tr>
                    </thead>
                    <tbody><?=$tr_empty?></tbody>
                </table>
            </div>
        </div><!-- row -->

    </div><!-- container -->

</div>