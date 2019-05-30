<?php

use yii\helpers\Html;
use app\models\Provider;
use yii\helpers\ArrayHelper;
use backend\controllers\MainController as d;
use Faker\Provider\cs_CZ\DateTime;
//use Yii;

$this->title = 'Тест ККМ';

/*
 * Класс gr, это сокращение: goods-receipt
*/

// d::pre(iconv_strlen('Наименование номенклатупы товара, размер производителя, штрихкод'));

?>
<script>var KkmServerAddIn = {};</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

            <button type="button" class="btn btn-success btn-sm but" data-type="OpenShift">
                Открыть смену
            </button>

            <button type="button" class="btn btn-success btn-sm but" data-type="CloseShift">
                Закрыть смену
            </button>

            <button
                type="button"
                class="btn btn-success btn-sm but"
                data-type="RegisterCheck"
                data-type-check="0"
            >
                Печать чека
            </button>

            <button type="button" class="btn btn-success btn-sm change-type" onclick="changeType(this)">
                Продажа
            </button>

<!--            <button type="button" class="btn btn-success btn-sm but" data-type="RegisterCorrectionCheck">-->
<!--                Чек корекции-->
<!--            </button>-->

            <br>
            <br>
            <br>

            <br>
            <br>
            <br>

            <div class="res">Результат ответа ККМ сервера</div>

        </div>
    </div>
</div>