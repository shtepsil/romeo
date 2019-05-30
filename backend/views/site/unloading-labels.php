<?php

use backend\controllers\MainController as d;
use app\models\DocumentType;
use yii\helpers\Html;

$this->title = 'Выгрузка этикетки';

// для функции number_format
$zero = Yii::getAlias('@zero,');
$zero_one = Yii::getAlias('@zero_one');

/*
 * Класс ugls, это сокращение: uploading-labels
*/

?>
<div class="wrap ugls">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="row">
            <div class="col-md-7 w-barcode">
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
                <input
                    type="text"
                    class="form-control c-input-sm"
                    placeholder="Введите код документа Поступление"
                    title="Введите код документа Поступление"
                    value=""
                    name="document_id"
                />
                <button class="btn btn-primary btn-xs add"
                        data-url="ajax/get-products-by-document-id"
                        method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Добавить
                </button>
            </div>
            <div class="col-md-5">
                <span class="number_lines">Количество: <span>0</span> шт.</span>&nbsp;&nbsp;
                <button class="btn btn-primary btn-xs strings-list-barcodes"
                        data-url="ajaks/create-string-barcodes"
                        method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Реестр
                </button>
                <button class="btn btn-primary btn-xs graphic-list-barcodes"
                        data-url="ajaks/create-graphic-barcodes"
                        method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Этикетки
                </button><br>

                <!-- Кнопки скачивания файлов Excel -->
                <form method="post" action="<?=Yii::getAlias('@site').Yii::getAlias('@export_registry')?>" class="registry-download-export-excel dn">
                    <button class="btn btn-success btn-xs" type="submit">Скачать <span>Реестр</span>-Excel</button>
                </form>
                <!-- ------- -->
                <form method="post" action="<?=Yii::getAlias('@site').Yii::getAlias('@export_labels')?>" class="labels-download-export-excel dn">
                    <button class="btn btn-success btn-xs" type="submit">Скачать <span>Этикетки</span>-Excel</button>
                </form>
                <!-- /кнопки скачивания файлов Excel -->
            </div>
        </div><!-- row -->

        <br>
        <?=$alerts?>

        <?='<div class="res">response</div>'?>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table2">
                    <thead>
                    <tr>
                        <td class="content">Содержание</td>
                        <td>Размер</td>
                        <td class="inscription-label">Надпись на этикетке</td>
                        <td>Штрихкод</td>
                        <td>Розн.<br>цена</td>
                        <td>Цена по<br>акции</td>
                        <td>Авт.<br>скидка</td>
                    </tr>
                    </thead>
                    <tbody><?=$tr_empty?><?$tpl?></tbody>
                </table>

            </div>
        </div><!-- row -->
    </div><!-- container -->
</div>

<!-- js шаблон для строк tr.section2 -->
<?=$js_tpl?>