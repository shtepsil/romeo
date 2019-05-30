<?php

use backend\controllers\MainController as d;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\ReferenceBooks;

$this->title = 'Справочники';
/*
 * Класс rb, это сокращение: reference-books
*/

//echo Html::beginForm('#', '',[
//    'class' => '',
//    'enctype' => 'multipart/form-data',
//    'enableAjaxValidation' => true,
//]);
?>

<div class="wrap rb reference-books">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="row">

            <div class="col-md-6">

                <?php // получаем все справочники
                $reference_books = ReferenceBooks::find()->orderBy('name')->all();
                $rb = [];
                for($i=0;$i<count($reference_books);$i++){
                    // пропускаем элементы, у которых видимость - 0
                    if($reference_books[$i]['visibility'] == '0') continue;
                    $rb[$i]['value'] = $reference_books[$i]['value'];
                    $rb[$i]['name'] = $reference_books[$i]['name'];
                }
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = ArrayHelper::map($rb,'value','name');
                $options = [
                    'prompt'      => 'Выберите справочник',
                    'title'       => 'Выберите справочник',
                    'class'       => 'form-control directory',
                    'url'    => 'ajax/list-value',
                    'method' => 'post',
                ];
                ?>
                <?= Html::dropDownList('reference_books', '', $items, $options); ?>
            </div>
            <div class="col-md-6">
                <select class="form-control code" disabled>
                    <option value="">Код значения</option>
                </select>
            </div>



        </div>

        <br>
        <br>

        <div class="row">
            <div class="col-md-6 wrb">
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                <select class="form-control reference-values" title="Выберите значение справочника">
                    <option value="empty">Значение справочника пока пусто</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control edit" placeholder="Введите скорр. или новое значение" title="Введите скорр. или новое значение" disabled />
            </div>
        </div>

        <br>
        <br>

        <div class="row">
            <div class="col-md-12 text-center">
                <button
                    type="button"
                    class="btn btn-success center button"
                    data-url="ajax/reference-edit"
                    method="post"
                >
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Внести изменения
                </button>
            </div>
        </div>

    <!--    <br>-->
    <!--    <div class="res">result</div>-->
    </div><!-- container -->
</div>
<?php // Html::endForm(); ?>




