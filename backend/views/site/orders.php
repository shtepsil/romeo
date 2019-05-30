<?php

/* @var $this yii\web\View */
use backend\controllers\MainController as d;
use yii\helpers\Html;
use backend\models\Orders;
use backend\components\GetData;

$this->title = 'Заказы';

// для функции number_format
$ko = Yii::getAlias('@ko');
$fl = Yii::getAlias('@fl');
$th = Yii::getAlias('@th');

// значение для пустых числовых значений
$zero = Yii::getAlias('@zero');
$zeroz = Yii::getAlias('@zero,');
$zero_one = Yii::getAlias('@zero_one');

//d::pre(date('Y-m-d H:i:s',1534786775));


// orrs - orders
?>

<div class="orrs">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>
        </div>
        <div class="col-md-12 inputs">

            <div class="new-orders">
                <?php
                $new_orders = GetData::getOrders();
                $items = \yii\helpers\ArrayHelper::map($new_orders,'id','name');
                $options = [
                    'prompt'   => 'Новые заказы',
                    'title'    => 'Новые заказы',
                    'class'    => 'form-control c-input-sm',
                    'data-url' => 'ajax/get-orders',
                    'method'   => 'post'
                ];
                $arr_orders = d::objectToArray($new_orders);
                ?>
                <?= Html::dropDownList('new_orders', '', $items, $options); ?>
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','class'=>'loading','width'=>'20'])?>
            </div>

            <div class="ready-orders">
                <?php
                $ready_orders = GetData::getOrders('ready_orders');
                $items = \yii\helpers\ArrayHelper::map($ready_orders,'id','name');
                $options = [
                    'prompt'      => 'Готовые заказы',
                    'title'       => 'Готовые заказы',
                    'class'       => 'form-control c-input-sm',
                    'data-url' => 'ajax/get-orders',
                    'method'   => 'post'
                ];
                ?>
                <?= Html::dropDownList('ready_orders', '', $items, $options); ?>
            </div>

            <div class="form-check form-check-inline">
                &nbsp;&nbsp;
                <input name="order_compiled" type="checkbox" class="form-check-input" id="compiled" disabled>
                <label class="form-check-label" for="compiled">Заказ собран, увдомление отправлено</label>
                &nbsp;&nbsp;
                <input name="order_c" type="radio" class="form-check-input" id="completed" disabled>
                <label class="form-check-label" for="completed">Заказ завершен</label>
                &nbsp;&nbsp;
                <input name="order_c" type="radio" class="form-check-input" id="cancelled" disabled>
                <label class="form-check-label" for="cancelled">Заказ отменен</label>

            </div>
            <button
                type="button"
                name="save_orders"
                class="btn btn-success btn-sm"
                data-url="ajax/order-status-change"
                method="post"
            >
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','class'=>'loading','width'=>'20'])?>
                Сохранить
            </button>
        </div>
    </div><!-- row -->
    <br>
    <div class="row info">
        <div class="col-md-3">
            <div class="order-number">Номер заказа: <span>...</span></div>
            <div class="customer">Покупатель: <span>...</span></div>
        </div>
        <div class="col-md-3">
            <div class="order-status">Статус заказа: <span>...</span></div>
            <div class="phone">Телефон: <span>...</span></div>
        </div>
        <div class="col-md-6">
            <textarea name="" class="form-control" id="" cols="30" rows="2" placeholder="Комментарий"></textarea>
        </div>
    </div><!-- row -->
    <br>
    <div class="row itogo">
        <div class="col-md-3">
            <div class="discount-card">Дисконтная к.: <span>...</span></div>
        </div>
        <div class="col-md-3">
            <div class="total-discount">Итого скидка: <span><?=$zeroz?></span></div>
        </div>
        <div class="col-md-3">
            <div class="total-amount">Итого сумма: <span><?=$zeroz?></span></div>
        </div>
    </div><!-- row -->
    <br>

    <?=$alerts?>
    <?='<div class="res">resutl</div>'?>

    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>Информация</th>
                    <th>Размер</th>
                    <th>Штрихкод</th>
                    <th>Розн.<br>цена</r></th>
                    <th>Цена со<br>скидкой</th>
                    <th>Скидка</th>
                </tr>
            </thead>
            <tbody>
            <?=$tr_empty?>
            <?Yii::$app->view->renderFile(
                '@app/views/ajax/shortcodes/tr-orders.php');?>
            </tbody>
        </table>
    </div><!-- row -->
</div>


