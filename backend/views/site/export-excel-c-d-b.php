<?php

use yii\helpers\Html;
use backend\controllers\MainController as d;

$this->registerJsFile('@web/js/download_files.js',['position'=>$this::POS_END],'download_files');

$this->title = 'Загрузка Excel CDB';

//d::pri($file);

//foreach($excel_files as $val){
//    d::pri($val->name.'.'.$val->ext);
//}

/*
 * Класс ddfs, это сокращение: download files
*/

//d::pri($select);


?>
<style>
    .ddfs2 form.brand-product-group{ height: 110px; }
    .ddfs2 form.brand-product-group select{
        margin: 5px 0 5px 0;
        cursor: pointer;
    }
    .ddfs2 form.brand-product-group input[type=file]{ top: 60px; }
    .ddfs2 form.brand-product-group .label{ top: 90px; }
</style>
<div class="container wrap ddfs ddfs2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>
                <br>
                <br>
                <br>

                <form action="ajax/upload-excel-tmpl" method="post" id="my_form" class="form-import-tmpl brand-product-group" enctype="multipart/form-data" accept-charset="utf-8">

                    <div class="input-file">

                        <div class="over-view"></div>

                        <h6 class="header">
                            Загрузка файлов "Бренд, Товарная группа"
                            <button type="button" class="btn btn-secondary" id="popover" data-content="Подсказка">?</button>
                        </h6>
                        <select name="type_file" id="">
                            <option value="">Выберите тип файла</option>
                            <option value="brand">Бренд</option>
                            <option value="product_group">Товарная группа</option>
                        </select>
                        <br>
                        <a class='btn btn-primary btn-sm' href='javascript:;'>
                            Выберите файл...
                            <input type="file" id="image-brand-product-group" name="excel" size="40" onchange='$("#upload-brand-product-group").html($(this).val());'>
                            <input type="hidden" id="data_type" name="data_type" value="brand-product-group" />
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-brand-product-group"></span>
                        <button type="submit" id="submit" class="btn btn-secondary btn-sm">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            Загрузить
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="progress-percent"></span>

                    </div>
                    <br>

                </form>

                <form action="ajax/upload-excel-tmpl" method="post" id="my_form" class="form-import-tmpl size-manufacturer" enctype="multipart/form-data" accept-charset="utf-8">

                    <div class="input-file">

                        <div class="over-view"></div>

                        <h6 class="header">
                            Загрузка файла "Размер производителя"
                            <button type="button" class="btn btn-secondary" id="popover" data-content="Подсказка">?</button>
                        </h6>

                        <a class='btn btn-primary btn-sm' href='javascript:;'>
                            Выберите файл...
                            <input type="file" id="image-size-manufacturer" name="excel" size="40" onchange='$("#upload-size-manufacturer").html($(this).val());' />
                            <input type="hidden" id="type_file" name="type_file" value="size_manufacturer" />
                            <input type="hidden" id="data_type" name="data_type" value="size-manufacturer" />
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-size-manufacturer"></span>
                        <button type="submit" id="submit" class="btn btn-secondary btn-sm">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            Загрузить
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="progress-percent"></span>
                    </div>
                    <br>

                </form>

                <form action="ajax/upload-excel-tmpl" method="post" id="my_form" class="form-import-tmpl product-nomenclature" enctype="multipart/form-data" accept-charset="utf-8">

                    <div class="input-file">

                        <div class="over-view"></div>

                        <h6 class="header">
                            Загрузка файла "Номенклатура"
                            <button type="button" class="btn btn-secondary" id="popover" data-content="Подсказка">?</button>
                        </h6>

                        <a class='btn btn-primary btn-sm' href='javascript:;'>
                            Выберите файл...
                            <input type="file" id="image-product-nomenclature" name="excel" size="40" onchange='$("#upload-product-nomenclature").html($(this).val());' />
                            <input type="hidden" id="type_file" name="type_file" value="product_nomenclature" />
                            <input type="hidden" id="data_type" name="data_type" value="product-nomenclature" />
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-product-nomenclature"></span>
                        <button type="submit" id="submit" class="btn btn-secondary btn-sm">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            Загрузить
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="progress-percent"></span>
                    </div>
                    <br>

                </form>

                <form action="ajax/upload-excel-tmpl" method="post" id="my_form" class="form-import-tmpl product" enctype="multipart/form-data" accept-charset="utf-8">

                    <div class="input-file">

                        <div class="over-view"></div>

                        <h6 class="header">
                            Загрузка файла "Товар"
                            <button type="button" class="btn btn-secondary" id="popover" data-content="Подсказка">?</button>
                        </h6>

                        <a class='btn btn-primary btn-sm' href='javascript:;'>
                            Выберите файл...
                            <input type="file" id="image-product" name="excel" size="40" onchange='$("#upload-product").html($(this).val());' />
                            <input type="hidden" id="type_file" name="type_file" value="product" />
                            <input type="hidden" id="data_type" name="data_type" value="product" />
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-product"></span>
                        <button type="submit" id="submit" class="btn btn-secondary btn-sm">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            Загрузить
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="progress-percent"></span>
                    </div>
                    <br>

                </form>

                <br>
                <?=$alerts?>

            </div>
    <!--        <div class="col-md-12">-->
    <!--            <div class="res">result</div>-->
    <!--        </div>-->
        </div>
        <br>
        <br>

        <div class="row">
            <div class="col-md-12 w-lf">
                <div class="view-list-files">
                    <a href="#" class="no-link">
                        <span class="badge pull-right"><?=$count_files?></span>
                        <span class="text">Показать список загруженых файлов</span>
                    </a>
                </div>
                <div
                    class="w-list-files"
                    data-url="ajax/delete-excel-file"
                    data-method="post"
                >
                    <table class="table table-sm list-files">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя файла</th>
                            <th>Дата и время загрузки</th>
                            <th class="td-del">Удалить</th>
                        </tr>
                        </thead>
                        <tbody><?=$list_files?></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div><!-- container -->
</div>