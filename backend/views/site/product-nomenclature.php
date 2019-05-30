<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 04.06.2018
 * Time: 16:30
 */

use yii\helpers\Html;
use backend\controllers\MainController as d;

$this->title = 'Номенклатура товара';

/*
 * Сокращения классов html:
 * pn - product-nomenclature
 * ptne - product-nomenclature (класс главноый формы)
 *
*/
echo Html::beginForm('ajax/ptne', '',[
    'method' => 'post',
    'class' => 'ptne',
    'enctype' => 'multipart/form-data',
    'enableAjaxValidation' => true,
]);

?>
<div class="wrap pn">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="row">

            <div class="col-md-6">
                <?php // получаем все справочники
                $brand = \app\models\Brand::find()->orderBy('name')->all();?>
                <select name="brand_code" id="" class="form-control brands" title="Выберите бренд" action="ajax/list-value" method="post">
                    <option value="" selected>Выберите бренд</option>
                    <?foreach($brand as $val):?>
                    <option value="<?=$val['id']?>" data-code="<?=$val['code']?>">
                        <?=$val['name']?>
                    </option>
                    <?endforeach;?>
                </select>
            </div>

            <div class="col-md-6">
                <span class="is-images"></span>
                <!-- Модальное окно "Загрузка файлов" -->
                <button type="button" class="btn btn-sm btn-primary modal-in" disabled>Загрузить/показать изображения</button>
            </div>

        </div><!-- row -->

        <div class="row">
            <div class="col-md-6 wvc">
                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                <select
                    name="reference_value"
                    class="form-control vendor-code"
                    title="Выберите артикул"
                    action="ajax/get-nomenclature"
                    method="post"
                >
                    <option value="">Список артикулов пока пуст</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="article_of_manufacture" id="edit" class="form-control edit" data-type="edit" placeholder="Введите скорр. или новое занчение" disabled />
            </div>
        </div><!-- row -->

<!--        <div class="res">res</div>-->

        <div class="row">
            <div class="col-md-12">

                <textarea name="nomenclature_name" id="nomenclature_name" cols="30" rows="1" class="form-control" placeholder="Наименование номенклатуры"></textarea>
                <textarea name="features_of_the_model" id="features_of_the_model" cols="30" rows="2" class="form-control" placeholder="Введите особенности модели"></textarea>
                <textarea name="product_description_on_the_site" id="product_description_on_the_site" cols="30" rows="2" class="form-control" placeholder="Описание товара на сайте"></textarea>
                <textarea name="labeling" id="labeling" cols="30" rows="1" class="form-control" placeholder="Надпись на этикетке"></textarea>
                <textarea name="nomenclature_codes_similar_products" id="nomenclature_codes_similar_products" cols="30" rows="1" class="form-control" placeholder="Номенклатурные коды похожие на товары"></textarea>
                <textarea name="nomenclatural_codes" id="nomenclatural_codes" cols="30" rows="1" class="form-control" placeholder="Номенклатурные коды подборка"></textarea>

            </div>

        </div><!-- row -->

        <div class="row">
            <div class="col-md-3">
                <?php // получаем все справочники
                $prodg = \app\models\ProductGroup::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($prodg,'code','name');
                $options = [
                    'prompt'      => 'Выберите тованую группу',
                    'title'       => 'Выберите тованую группу',
                    'class'       => 'form-control product-group',
                ];
                ?>
                <?= Html::dropDownList('commodity_group_code', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $color = \app\models\Color::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($color,'id','name');
                $options = [
                    'prompt'      => 'Выберите цвет',
                    'title'       => 'Выберите цвет',
                    'defaultValue' => 0,
                    'class'       => 'form-control color',
                ];
                ?>
                <?= Html::dropDownList('code_color', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $neckband = \app\models\Neckband::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($neckband,'id','name');
                $options = [
                    'prompt'      => 'Выберите ворот',
                    'title'       => 'Выберите ворот',
                    'defaultValue' => 0,
                    'class'       => 'form-control neckband',
                ];
                ?>
                <?= Html::dropDownList('code_collar', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $clasp = \app\models\Clasp::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($clasp,'id','name');
                $options = [
                    'prompt'      => 'Выберите застежка',
                    'title'       => 'Выберите застежка',
                    'defaultValue' => 0,
                    'class'       => 'form-control clasp',
                ];
                ?>
                <?= Html::dropDownList('code_clasp', '', $items, $options); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?php // получаем все справочники
                $gender = \app\models\Gender::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($gender,'code','name');
                $options = [
                    'prompt'      => 'Выберите пол',
                    'title'       => 'Выберите пол',
                    'class'       => 'form-control gender',
                ];
                ?>
                <?= Html::dropDownList('code_sex', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $design = \app\models\Design::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($design,'id','name');
                $options = [
                    'prompt'      => 'Выберите рисунок/узор',
                    'title'       => 'Выберите рисунок/узор',
                    'defaultValue' => 0,
                    'class'       => 'form-control design',
                ];
                ?>
                <?= Html::dropDownList('code_pattern', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $pockets = \app\models\Pockets::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($pockets,'id','name');
                $options = [
                    'prompt'      => 'Выберите карманы',
                    'title'       => 'Выберите карманы',
                    'defaultValue' => 0,
                    'class'       => 'form-control pockets',
                ];
                ?>
                <?= Html::dropDownList('code_pockets', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $buckle = \app\models\Buckle::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($buckle,'id','name');
                $options = [
                    'prompt'      => 'Выберите пряжка',
                    'title'       => 'Выберите пряжка',
                    'defaultValue' => 0,
                    'class'       => 'form-control buckle',
                ];
                ?>
                <?= Html::dropDownList('code_buckle', '', $items, $options); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php // получаем все справочники
                $season = \app\models\Season::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($season,'id','name');
                $options = [
                    'prompt'      => 'Выберите сезон',
                    'title'       => 'Выберите сезон',
                    'defaultValue' => 0,
                    'class'       => 'form-control season',
                ];
                ?>
                <?= Html::dropDownList('code_season', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $width = \app\models\Width::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($width,'id','name');
                $options = [
                    'prompt'      => 'Выберите ширина',
                    'title'       => 'Выберите ширина',
                    'defaultValue' => 0,
                    'class'       => 'form-control width',
                ];
                ?>
                <?= Html::dropDownList('code_width', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $number_buttons = \app\models\NumberButtons::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($number_buttons,'id','name');
                $options = [
                    'prompt'      => 'Выберите число пуговиц',
                    'title'       => 'Выберите число пуговиц',
                    'defaultValue' => 0,
                    'class'       => 'form-control number-buttons',
                ];
                ?>
                <?= Html::dropDownList('code_number_of_buttons', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $landing_line = \app\models\LandingLine::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($landing_line,'id','name');
                $options = [
                    'prompt'      => 'Выберите линия посадки',
                    'title'       => 'Выберите линия посадки',
                    'defaultValue' => 0,
                    'class'       => 'form-control landing-line',
                ];
                ?>
                <?= Html::dropDownList('code_landing_line', '', $items, $options); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php // получаем все справочники
                $composition_top = \app\models\CompositionTop::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($composition_top,'id','name');
                $options = [
                    'prompt'      => 'Выберите состав верх',
                    'title'       => 'Выберите состав верх',
                    'defaultValue' => 0,
                    'class'       => 'form-control composition-top',
                ];
                ?>
                <?= Html::dropDownList('code_composition_top', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $length = \app\models\Length::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($length,'id','name');
                $options = [
                    'prompt'      => 'Выберите длина',
                    'title'       => 'Выберите длина',
                    'defaultValue' => 0,
                    'class'       => 'form-control length',
                ];
                ?>
                <?= Html::dropDownList('code_length', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $insulation = \app\models\Insulation::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($insulation,'id','name');
                $options = [
                    'prompt'      => 'Выберите утеплитель',
                    'title'       => 'Выберите утеплитель',
                    'defaultValue' => 0,
                    'class'       => 'form-control insulation',
                ];
                ?>
                <?= Html::dropDownList('code_insulation', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $defenses = \app\models\Defenses::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($defenses,'id','name');
                $options = [
                    'prompt'      => 'Выберите защипы',
                    'title'       => 'Выберите защипы',
                    'defaultValue' => 0,
                    'class'       => 'form-control defenses',
                ];
                ?>
                <?= Html::dropDownList('security_code', '', $items, $options); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php // получаем все справочники
                $composition_filler = \app\models\CompositionFiller::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($composition_filler,'id','name');
                $options = [
                    'prompt'      => 'Выберите состав наполнитель',
                    'title'       => 'Выберите состав наполнитель',
                    'defaultValue' => 0,
                    'class'       => 'form-control composition-filler',
                ];
                ?>
                <?= Html::dropDownList('code_filler_composition', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $sleeve = \app\models\Sleeve::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($sleeve,'id','name');
                $options = [
                    'prompt'      => 'Выберите рукав',
                    'title'       => 'Выберите рукав',
                    'defaultValue' => 0,
                    'class'       => 'form-control sleeve',
                ];
                ?>
                <?= Html::dropDownList('code_sleeve', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $hood = \app\models\Hood::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($hood,'id','name');
                $options = [
                    'prompt'      => 'Выберите капюшон',
                    'title'       => 'Выберите капюшон',
                    'defaultValue' => 0,
                    'class'       => 'form-control hood',
                ];
                ?>
                <?= Html::dropDownList('code_hood', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $splines = \app\models\Splines::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($splines,'id','name');
                $options = [
                    'prompt'      => 'Выберите шлицы',
                    'title'       => 'Выберите шлицы',
                    'defaultValue' => 0,
                    'class'       => 'form-control splines',
                ];
                ?>
                <?= Html::dropDownList('code_slots', '', $items, $options); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php // получаем все справочники
                $composition_lining = \app\models\CompositionLining::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($composition_lining,'id','name');
                $options = [
                    'prompt'      => 'Выберите состав подклад',
                    'title'       => 'Выберите состав подклад',
                    'defaultValue' => 0,
                    'class'       => 'form-control composition-lining',
                ];
                ?>
                <?= Html::dropDownList('code_composition_lining', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $silhouette = \app\models\Silhouette::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($silhouette,'id','name');
                $options = [
                    'prompt'      => 'Выберите силуэт',
                    'title'       => 'Выберите силуэт',
                    'defaultValue' => 0,
                    'class'       => 'form-control silhouette',
                ];
                ?>
                <?= Html::dropDownList('code_silhouette', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <?php // получаем все справочники
                $belt = \app\models\Belt::find()->orderBy('name')->all();
                // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                $items = \yii\helpers\ArrayHelper::map($belt,'id','name');
                $options = [
                    'prompt'      => 'Выберите пояс',
                    'title'       => 'Выберите пояс',
                    'defaultValue' => 0,
                    'class'       => 'form-control belt',
                ];
                ?>
                <?= Html::dropDownList('code_belt', '', $items, $options); ?>
            </div>
            <div class="col-md-3">
                <input type="text" name="novelty_of_the_season" placeholder="Признак новинка сезона (дата)" id="datepicker" class="form-control" />
            </div>

        </div><!-- row -->

        <div class="row bottom-elements">
            <div class="col-md-4">
                <select name="display" class="form-control display-on-the-site" title="Отображать на сайте (да/нет)">
                    <option value="0" selected>Отображать на сайте (нет)</option>
                    <option value="1">Отображать на сайте (Да)</option>
                </select>
            </div>
            <div class="col-md-5 dpu">
                <input type="hidden" name="detail_page_url" value="" />
                Адрес страницы товара на сайте: <a href="#"></a>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Внести изменения</button>
            </div>
        </div>

        <br>

        <?'<div class="res">res</div><br>'?>

        <?=$alerts?>

    </div><!-- container -->

</div>

<?=$modal_upload_files?>

<?php Html::endForm(); ?>
