<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Открыть документ';
?>
<div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

<div class="content open-document">

    <div class="row">
    <div class="col-md-2">
        <input type="text" id="from" class="form-control c-input-sm" placeholder="Дата начало" title="Дата начало">
    </div>

    <div class="col-md-2">
        <input type="text" id="to" class="form-control c-input-sm" placeholder="Дата окончание" title="Дата окончание">
    </div>

    <div class="col-md-2">
        <button type="button" class="btn btn-primary btn-xs">Вывести перечень документов</button>
    </div>

        <div class="col-md-6"></div>

    </div>

    <br>

    <div class="row">
        <div class="col-md-2">
            <select class="form-control c-input-sm" title="Выберите номер документа">
                <option value="0">Выберите номер документа</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>5</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="text" class="form-control c-input-sm" placeholder="Введите коммнетарий" title="Введите коммнетарий" style="width: 100%;">
        </div>

        <div class="col-md-4">
            <button type="button" class="btn btn-success btn-xs">Открыть документ</button>&nbsp;&nbsp;&nbsp;
            <button type="button" class="btn btn-danger btn-xs">Удалить документ</button>
        </div>
        <div class="col-md-5"></div>

    </div>

    <br>

    <div class="row">
        <h3>Продажа сертификата</h3>

        <table class="table document2 small">
            <tbody>

            <!-- Item str -->
            <tr>
                <td>Код документа</td>
                <td>Тип документа</td>
                <td>Номер документа</td>
                <td>Дата документа</td>
                <td>Время составления документа</td>
                <td>Документ контрагента</td>
                <td>Комментарий</td>
                <td>Работник</td>
                <td>ФИО, документ покупателя</td>
                <td>Дисконтная карта</td>
                <td>Банковская карта</td>
            </tr>
            <!-- /item str -->

            <!-- Item str -->
            <tr>
                <td>Код документа</td>
                <td>Тип документа</td>
                <td>Номер документа</td>
                <td>Дата документа</td>
                <td>Время составления документа</td>
                <td>Документ контрагента</td>
                <td>Комментарий</td>
                <td>Работник</td>
                <td>ФИО, документ покупателя</td>
                <td>Дисконтная карта</td>
                <td>Банковская карта</td>
            </tr>
            <!-- /item str -->

            <!-- Item str -->
            <tr>
                <td>Код документа</td>
                <td>Тип документа</td>
                <td>Номер документа</td>
                <td>Дата документа</td>
                <td>Время составления документа</td>
                <td>Документ контрагента</td>
                <td>Комментарий</td>
                <td>Работник</td>
                <td>ФИО, документ покупателя</td>
                <td>Дисконтная карта</td>
                <td>Банковская карта</td>
            </tr>
            <!-- /item str -->

            <!-- Item str -->
            <tr>
                <td>Код документа</td>
                <td>Тип документа</td>
                <td>Номер документа</td>
                <td>Дата документа</td>
                <td>Время составления документа</td>
                <td>Документ контрагента</td>
                <td>Комментарий</td>
                <td>Работник</td>
                <td>ФИО, документ покупателя</td>
                <td>Дисконтная карта</td>
                <td>Банковская карта</td>
            </tr>
            <!-- /item str -->

            <!-- Item str -->
            <tr>
                <td>Код документа</td>
                <td>Тип документа</td>
                <td>Номер документа</td>
                <td>Дата документа</td>
                <td>Время составления документа</td>
                <td>Документ контрагента</td>
                <td>Комментарий</td>
                <td>Работник</td>
                <td>ФИО, документ покупателя</td>
                <td>Дисконтная карта</td>
                <td>Банковская карта</td>
            </tr>
            <!-- /item str -->

            </tbody>
        </table>
    </div>

</div>