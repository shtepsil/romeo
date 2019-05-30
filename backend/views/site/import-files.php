<?php

use yii\helpers\Html;
use backend\controllers\MainController as d;

$this->registerJsFile('@web/js/download_files.js',['position'=>$this::POS_END],'download_files');

$this->title = 'Загрузка файлов Excel';

//d::pri($file);

//foreach($excel_files as $val){
//    d::pri($val->name.'.'.$val->ext);
//}

/*
 * Класс ddfs, это сокращение: download files
*/

//d::pri($files);


?>
<div class="container wrap ddfs">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>
                <br>
                <br>
                <br>

                <form action="ajax/upload" method="post" id="my_form" class="form-import certificates" enctype="multipart/form-data" accept-charset="utf-8" data-type="certificates">

                    <div class="input-file">

                        <div class="over-view"></div>

                        <h6 class="header">
                            Загрузка сертификатов
                            <button type="button" class="btn btn-secondary" id="popover" data-content="<b>Формат файла<br>должен быть таким:</b><br>Первая строка: заголовок<br>Первый столбец: штрихкод<br>Второй столбец: номинал">?</button>
                        </h6>

                        <a class='btn btn-primary btn-sm' href='javascript:;'>
                            Выберите файл...
                            <input type="file" id="image-certificates" name="excel" size="40" onchange='$("#upload-certificates").html($(this).val());'>
                            <input type="hidden" id="type_file" name="type_file" value="certificates" />
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-certificates"></span>
                        <button type="submit" id="submit" class="btn btn-secondary btn-sm">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            Загрузить
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="progress-percent"></span>

                    </div>
                    <br>

                </form>

                <form action="ajax/upload" method="post" id="my_form" class="form-import discount-cards" enctype="multipart/form-data" accept-charset="utf-8" data-type="discount-cards">

                    <div class="input-file">

                        <div class="over-view"></div>

                        <h6 class="header">
                            Загрузка дисконтных карт
                            <button type="button" class="btn btn-secondary" id="popover" data-content="<b>Формат файла<br>должен быть таким:<br></b>Первая строка: заголовок<br>Первый столбец: штрихкод<br>Второй столбец: скидка">?</button>
                        </h6>

                        <a class='btn btn-primary btn-sm' href='javascript:;'>
                            Выберите файл...
                            <input type="file" id="image-discount-cards" name="excel" size="40" onchange='$("#upload-discount-cards").html($(this).val());' />
                            <input type="hidden" id="type_file" name="type_file" value="discount-cards" />
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-discount-cards"></span>
                        <button type="submit" id="submit" class="btn btn-secondary btn-sm">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            Загрузить
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="progress-percent"></span>
                    </div>
                    <br>

                </form>

                <form action="ajax/upload" method="post" id="my_form" class="form-import automatic-discount" enctype="multipart/form-data" accept-charset="utf-8" data-type="automatic-discount">

                    <div class="input-file">

                        <div class="over-view"></div>

                        <h6 class="header">
                            Загрузка автоматических скидок
                            <button type="button" class="btn btn-secondary" id="popover" data-content="<b>Формат файла<br>должен быть таким:</b><br>Первая строка: заголовок<br>Первый столбец: штрихкод<br>Второй столбец: цена по акции<br>Третий столбец: скидка<br>Четвертый столбец: дата начало скидки(гггг-дд-мм)<br>Пятый столбец: дата окончание скидки(гггг-дд-мм)">?</button>
                        </h6>

                        <a class='btn btn-primary btn-sm' href='javascript:;'>
                            Выберите файл...
                            <input type="file" id="image-automatic-discount" name="excel" size="40" onchange='$("#upload-automatic-discount").html($(this).val());' />
                            <input type="hidden" id="type_file" name="type_file" value="automatic-discount" />
                        </a>
                        &nbsp;
                        <span class='label label-info' id="upload-automatic-discount"></span>
                        <button type="submit" id="submit" class="btn btn-secondary btn-sm">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            Загрузить
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="progress-percent"></span>
                    </div>
                    <br>

    <!--                <div class="res">result</div>-->

                </form>

                <br>
                <?=$alerts?>

            </div>
        </div>

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