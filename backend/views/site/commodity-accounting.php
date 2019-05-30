<?php

/* @var $this yii\web\View */
use backend\controllers\MainController as d;
use yii\helpers\Html;
use common\models\User;
use yii\helpers\ArrayHelper;

$this->title = 'Товарный учёт';

// для функции number_format
$ko = Yii::getAlias('@ko');
$fl = Yii::getAlias('@fl');
$th = Yii::getAlias('@th');

// значение для пустых числовых значений
$zero = Yii::getAlias('@zero');
$zeroz = Yii::getAlias('@zero,');
$zero_one = Yii::getAlias('@zero_one');

//$gg = date('Y',time());
//$gg += 1;
//d::pre($gg);

//d::pri($ps);

?>
<div class="wrap cyag">

    <input type="hidden" name="user_id" value="<?=Yii::$app->user->id?>" />
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="document-head">
            <!-- row1 -->
            <div class="row row1">
                <div class="col-md-8">
                    <div class="w-dt">
                        <?php // получаем все справочники
                        $document_type = \app\models\DocumentType::find()
                            ->where(['code'=>['04','05','06','07']])
                            ->orderBy('code')->asArray()->all();

                        // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                        $items = \yii\helpers\ArrayHelper::map($document_type,'code','name');
                        $options = [
                            'prompt'   => 'Тип документа',
                            'title'    => 'Тип документа',
                            'class'    => 'form-control c-input-sm',
                            'data-url' => 'ajax/get-documents',
                            'method'   => 'post'
                        ];?>
                        <?=Html::dropDownList(
                            'document_type', '', $items, $options);?>
                        <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    </div>

                    <?
                    $months = Yii::$app->params['months'];
                    $options = [
                        'prompt'  => 'Месяц',
                        'title'   => 'Месяц',
                        'class'   => 'form-control c-input-sm',
                        'options' => [
                            date('m',time()) => [
                                    'selected'=>'selected'
                            ]
                        ]
                    ];
                    echo Html::dropDownList('months', '', $months, $options); ?>
                    <?php
                    // Начальный год
                    $years = Yii::getAlias('@begin_year');
                    // Текущий год
                    $year = date('Y',time());
                    $select_options = '<option value="">Год</option>';
                    for($i=0;$i<1000;$i++){
// Если текущий год не равняется начальному
if($year != $years){
    // Выводим год
    $select_options .= '<option value="'.$years.'">'.$years.'</option>';
    // К начальному прибавляем 1
    $years += 1;
}else{
    // Выводим текущий
    $select_options .= '<option value="'.$years.'" selected>'.$years.'</option>';
    break;
}
                    }
                    ?>
                    <select name="years" class="form-control c-input-sm">
                        <?=$select_options?>
                    </select>
                    <select
                        name="document"
                        class="form-control c-input-sm"
                        title="Выберите документ"
                        placeholder="Выберите документ"
                        data-url="ajax/get-from-goods-movement"
                        method="post"
                        style="<?=$document_style?>"
                    >
                        <?=$document_options?>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="totals">
                        <table class="table">
                            <tr>
                                <td>Количество, шт.</td>
                                <td class="quantity"><?=$zero_one?></td>
                            </tr>
                            <tr>
                                <td>Себестоимость, руб.</td>
                                <td class="cost-price"><?=$zero_one?></td>
                            </tr>
                            <tr>
                                <td>Розница, руб.</td>
                                <td class="retail-price"><?=$zeroz?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /row1 -->

            <!-- row2 -->
            <div class="row row2">
                <div class="col-md-2">
                    <?php // получаем все справочники
                    $provider = \app\models\Provider::find()
                        ->orderBy('id')->all();
                    $items = \yii\helpers\ArrayHelper::map($provider,'id','name');
                    $options = [
                        'prompt'      => 'Выберите поставщика',
                        'title'       => 'Выберите поставщика',
                        'class'       => 'form-control c-input-sm',
                    ];
                    echo Html::dropDownList('provider', '', $items, $options); ?>
                    <div class="w-barcode">
                        <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка22','width'=>'20'])?>
                        <input
                            type="text"
                            name="barcode"
                            class="form-control c-input-sm barcode"
                            placeholder="Ввод штрихкода"
                            title="Ввод штрихкода"
                            data-url="ajax/get-product-by-barcode"
                            method="post"
                            onkeyup="isNumeric(this)" />
                    </div>
                </div>
                <div class="col-md-6">
                    <?php
                    $options = [
                        'placeholder' => 'Введите комментарий',
                        'title'       => 'Введите комментарий',
                        'class'       => 'form-control',
                        'rows'        => '3',
                    ];
                    echo Html::textarea('description','Некий комментарий документа',$options)?>
                </div>

            </div>
            <!-- /row2 -->

            <!-- row3 -->
            <div class="row row3">

                <div class="col-md-3">
                    <button
                        type="button"
                        class="btn btn-danger btn-xs"
                        name="delete_rows"
                        onclick="deleteRowCA()"
                    >Удалить выделенные строки</button>
                </div>
                <div class="col-md-4 text-center w-btns-blocks">
                    <button
                        type="button"
                        class="btn btn-primary btn-xs leftovers-shk"
                        data-url="ajax/account-balance"
                        method="post"
                    >
                        <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка22','width'=>'20'])?>
                        Остатки ШК
                    </button>&nbsp;

                    <button type="button" class="btn btn-primary btn-xs leftovers-dimensions">
                        Остатки Размеры
                    </button>&nbsp;

                    <button type="button" class="btn btn-primary btn-xs block-show">
                        Развернуто
                    </button>
                </div>

                <div class="col-md-5 text-right">
                    <button
                        type="button"
                        class="btn btn-success btn-xs save"
                        data-url="ajax/save-commodity-accounting"
                        method="post"
                    >
                        <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                        Сохранить документ
                    </button>
                </div>


                <br><div class="res">response</div>
            </div><!-- /row3 -->
        </div><!-- /document-head -->


        <!---->

        <br>
        <?=$alerts?>

        <!-- row4 -->
        <div class="row row4">

                <div class="h6"></div>

                <table class="table small blocks">
                    <tbody>
                        <tr class="thead">
                            <td></td>
                            <td>№</td>
                            <td>Док</td>
                            <td>Содержание</td>
                            <td>Размер</td>
                            <td>Дата<br>поступления</td>
                            <td>Штрихкод</td>
                            <td>Себе-<br>стоимость</td>
                            <td>Розничная<br>цена</td>
                            <td>Остаток<br>на учете</td>
                            <td>Кол-во</td>
                            <td>Остаток<br>факт</td>
                        </tr>

                        <tr class="t-header s1">
                            <th colspan="15">Строки(развернуто)</th>
                        </tr>

                        <?//=$block1?>

                        <tr class="t-header s2">
                            <th colspan="15">Строки Остаток ШК</th>
                        </tr>

                        <tr class="t-header s3">
                            <th colspan="15">Строки остаток Размеры</th>
                        </tr>

                        <tr class="section3">
                            <td></td>
                            <!--    номер строки-->
                            <td>1</td>
                            <td></td>
                            <!--    описание-->
                            <td class="description3">Наименование номерклатуры товара, цвет, рисунок</td>
                            <!--    размер производителя-->
                            <td class="size-manufacturer3">70-152</td>
                            <!--    дата поступления-->
                            <td class="receipt-date3">2019-02-26</td>
                            <!--    штрихкод-->
                            <td class="td-barcode3">0011001000012</td>
                            <!--    себестоимость-->
                            <td class="cost-price3">250.00</td>
                            <!--    розничная цена-->
                            <td class="retail-price3">350.00</td>
                            <!--    остаток на учете-->
                            <td class="account-balance3">1</td>
                            <!--    количество-->
                            <td class="quantity3">8</td>
                            <!--    остаток факт-->
                            <td class="remainder-fact3">4</td>
                        </tr>
                        <tr class="section3">
                            <td></td>
                            <!--    номер строки-->
                            <td>1</td>
                            <td></td>
                            <!--    описание-->
                            <td class="description3">Наименование номерклатуры товара, цвет, рисунок</td>
                            <!--    размер производителя-->
                            <td class="size-manufacturer3">70-152</td>
                            <!--    дата поступления-->
                            <td class="receipt-date3">2019-02-26</td>
                            <!--    штрихкод-->
                            <td class="td-barcode3">0031002000019</td>
                            <!--    себестоимость-->
                            <td class="cost-price3">250.00</td>
                            <!--    розничная цена-->
                            <td class="retail-price3">350.00</td>
                            <!--    остаток на учете-->
                            <td class="account-balance3">1</td>
                            <!--    количество-->
                            <td class="quantity3">8</td>
                            <!--    остаток факт-->
                            <td class="remainder-fact3">4</td>
                        </tr>
                        <tr class="section3">
                            <td></td>
                            <!--    номер строки-->
                            <td>1</td>
                            <td></td>
                            <!--    описание-->
                            <td class="description3">Наименование номерклатуры товара, цвет, рисунок</td>
                            <!--    размер производителя-->
                            <td class="size-manufacturer3">70-152</td>
                            <!--    дата поступления-->
                            <td class="receipt-date3">2019-02-26</td>
                            <!--    штрихкод-->
                            <td class="td-barcode3">0011001000029</td>
                            <!--    себестоимость-->
                            <td class="cost-price3">250.00</td>
                            <!--    розничная цена-->
                            <td class="retail-price3">350.00</td>
                            <!--    остаток на учете-->
                            <td class="account-balance3">1</td>
                            <!--    количество-->
                            <td class="quantity3">8</td>
                            <!--    остаток факт-->
                            <td class="remainder-fact3">4</td>
                        </tr>
                    </tbody>
                </table>

                <?
                //            d::pre($tr_empty);
                ?>

        </div><!-- /row4 -->

    </div><!-- /container -->
</div>

<!-- js шаблон для строк tr -->
<?=$js_tpl?>
