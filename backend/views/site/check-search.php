<?php

use backend\controllers\MainController as d;
use app\models\DocumentType;
use yii\helpers\Html;

$this->title = 'Поиск чека';

// для функции number_format
$zero = Yii::getAlias('@zero,');
$zero_one = Yii::getAlias('@zero_one');

/*
 * Класс cksh, это сокращение: check-search
*/

?>

<div class="wrap cksh">
    <div class="container">

        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="row">
            <div class="col-md-2">
                <input
                    type="text"
                    name="search_date"
                    class="form-control c-input-sm"
                    placeholder="Введите дату"
                    value=""
                    <? // value="<?=Yii::$app->getFormatter()->asDate(time())"?>
                />
            </div>
            <div class="col-md-2">
                <select name="time_period" id="" class="form-control c-input-sm">
                    <option value="day">За день</option>
                    <option value="week">За неделю</option>
                    <option value="two_weeks">За две недели</option>
                </select>
            </div>
            <div class="col-md-7 text-right">
                <button type="button" class="btn btn-primary btn-xs btn-search"
                        data-url="ajax/check-search-filter"
                        method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Поиск
                </button>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-2 w-barcode">
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                <input
                    type="text"
                    class="form-control c-input-sm"
                    placeholder="Введите штрихкод"
                    title="Введите штрихкод"
                    value=""
                    data-url="ajax/check-search-barcode"
                    method="post"
                    oninput="isNumeric(this)"
                />
            </div>
            <div class="col-md-2">
                <?php // получаем все справочники
                $product_group = \app\models\ProductGroup::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($product_group,'code','name');
                $options = [
                    'prompt' => 'Товарная группа',
                    'title'  => 'Товарная группа',
                    'class'  => 'form-control product_group c-input-sm',
                ];
                ?>
                <?= Html::dropDownList('product_group', '', $items, $options); ?>
            </div>
            <div class="col-md-2">
                <?php // получаем все справочники
                $product_group = \app\models\Brand::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($product_group,'code','name');
                $options = [
                    'prompt' => 'Выберите бренд',
                    'title'  => 'Выберите бренд',
                    'class'  => 'form-control c-input-sm brands',
                    'action' =>'ajax/list-value',
                    'method' =>'post',
                ];
                ?>
                <?= Html::dropDownList('brand_code', '', $items, $options); ?>
            </div>
            <div class="col-md-3 vendor-code">
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                <select
                        name="reference_value"
                        class="form-control c-input-sm"
                        title="Выберите артикул"
                >
                    <option value="">Список артикулов пока пуст</option>
                </select>
            </div>
            <div class="col-md-2">
                <?php // получаем все справочники
                $size_manufacturer = \app\models\SizeManufacturer::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($size_manufacturer,'id','name');
                $options = [
                    'prompt'      => 'Выберите размер',
                    'title'       => 'Выберите размер',
                    'class'       => 'form-control size_manufacturer c-input-sm',
                ];
                ?>
                <?= Html::dropDownList('size_manufacturer', '', $items, $options); ?>
            </div>
        </div><!-- row -->

        <br>

        <?=$alerts?>
<!--        <div class="res">response</div>-->

        <div class="row">
            <div class="col-md-12">
                <table class="table table2">
                    <thead>
                    <tr>
                        <td>Документ</td>
                        <td>Дата, время</td>
                        <td>Штрихкод</td>
                        <td>Описание</td>
                        <td>Цена</td>
                    </tr>
                    </thead>
                    <tbody><?=$tr_empty?></tbody>
                </table>
            </div>
        </div><!-- row -->

    </div><!-- container -->

</div>