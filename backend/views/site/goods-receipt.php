<?php

use yii\helpers\Html;
use app\models\Provider;
use yii\helpers\ArrayHelper;
use backend\controllers\MainController as d;
use Faker\Provider\cs_CZ\DateTime;
//use Yii;

$this->title = 'Поступление товара';

/*
 * Класс gr, это сокращение: goods-receipt
*/

?>
<div class="wrap gr">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <?= Html::beginForm('', 'post'); ?>

        <div class="row">
            <div class="col-md-3">




                <div class="form-group">
                    <?php // получаем все справочники
                    $provider = Provider::find()->orderBy('name')->all();
                    // формируем массив, с ключем равным полю 'code' и значением равным полю 'name'
                    $items = ArrayHelper::map($provider,'id','name');
                    $options = [
                        'class' => 'form-control vendor_code',
                        'prompt' => 'Выберите поставщика',
                        'title'  => 'Выберите поставщика'
                    ];
                    ?>
                    <?= Html::dropDownList('vendor_code', '', $items, $options); ?>
                </div>

            </div>
            <div class="col-md-3">
                <?php

                $items = [
                    '01' => 'Поставка',
                    '02' => 'Комиссия',
                ];
                $options = [
                    'class' => 'form-control code_type_of_document',
                    'prompt' => 'Выберите тип поставки',
                    'title'  => 'Выберите тип поставки'
                ];
                ?>
                <?= Html::dropDownList('document_type', '', $items, $options); ?>
            </div>
            <div class="col-md-6">
            <?php
                $options = [
                    'class'       => 'form-control counterparty_document_comment',
                    'placeholder' => 'Введите данные о документе поставщика',
                    'title'       => 'Введите данные о документе поставщика',
                    'rows'        => '4',
                ];
                echo Html::textarea( 'counterparty_document_comment', null, $options );
            ?>
            </div>
        </div><!-- row -->

        <?php Html::endForm(); ?>

        <div class="row">

            <div class="col-md-12 info">
                Итого количество по документу: <b><span class="quantity"><?=$zero['zero_one']?></span></b> шт.<br>
                Итого сумма себестоимости: <b><span class="t-cost-price"><?=$zero['zero,']?></span></b> руб.<br>
                Итого сумма розничной стоимости: <b><span class="t-retail-price"><?=$zero['zero,']?></span></b> руб.
            </div>

        </div><!-- row -->

        <br>

        <div class="row">

            <div class="col-md-12">
                <button
                    type="button"
                    class="btn btn btn-primary btn-sm add"
                    action="ajax/get-template"
                    method="post"
                >
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Добавить строку
                </button>
                &nbsp;&nbsp;&nbsp;
                <button type="button" data-type="goods-receipt" class="btn btn-danger btn-sm delete">Удалить выделенные строки</button>
                &nbsp;&nbsp;&nbsp;
                <button
                    type="button"
                    class="btn btn-success btn-sm send"
                    action="ajax/send-goods"
                    method="post"
                >
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Добавить товары
                </button>
            </div>

        </div><!-- row -->

        <br>

        <?=$alerts?>

    <!--    <br>-->
    <!--    <div class="res">response</div>-->

        <br>

        <div class="row list-products">

            <table class="table">
                <thead>
                    <tr>
                        <th colspan="14">Список товаров для добавления</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div><!-- row -->

        <br>

    </div><!-- container -->

</div>