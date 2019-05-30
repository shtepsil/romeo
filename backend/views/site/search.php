<?php

/* @var $this yii\web\View */
use backend\controllers\MainController as d;
use yii\helpers\Html;

$this->title = 'Форма поиска';
?>
<div class="row srch">
    <div class="col-md-10 col-md-offset-1">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <form role="form" class="form-search">
            <div class="number-dock">
                <div class="left-block">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Введите номер докумета">
                    </div>
                </div>
                <div class="right-block">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Введите дату документа начало">
                        <input type="text" class="form-control" placeholder="Введите дату документа конец">
                        <?php
                        $document_type = \app\models\DocumentType::find()->all();
                        // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                        $items = \yii\helpers\ArrayHelper::map($document_type,'code','name');
						$options = [
							'prompt'      => 'Выберите тип документа',
							'title'       => 'Выберите тип документа',
							'class'       => 'form-control ',
						];
						?>
						<?= Html::dropDownList('document_type', '', $items, $options); ?>

                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="barcode">
                <div class="left-block">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Введите штрих код">
                    </div>
                </div>
                <div class="right-block">
                    <div class="form-group">
						<?php // получаем все справочники
						$prodg = \app\models\ProductGroup::find()->all();
						// формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
						$items = \yii\helpers\ArrayHelper::map($prodg,'code','name');
						$options = [
							'prompt'      => 'Выберите товарную группу',
							'title'       => 'Выберите товарную группу',
							'class'       => 'form-control ',
						];
						?>
						<?= Html::dropDownList('product_group', '', $items, $options); ?>

                        <?php // получаем все справочники
                        $brand = \app\models\Brand::find()->all();?>
                        <select name="brand_code" id="" class="form-control brands" title="Выберите бренд" action="ajax/list-value" method="post">
                            <option value="" selected>Выберите бренд</option>
                            <?foreach($brand as $val):?>
                                <option value="<?=$val['id']?>" data-code="<?=$val['code']?>">
                                    <?=$val['name']?>
                                </option>
                            <?endforeach;?>
                        </select>

                        <div class="w-vc">
                            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                            <?php

                            $items = ['0'=>'Сначала выберите Бренд'];
                            $options = [
                                'prompt'      => 'Выберите Артикул',
                                'title'       => 'Выберите Артикул',
                                'class'       => 'form-control vendor-code',
                            ];
                            ?>
                            <?= Html::dropDownList('vendor_code', '', $items, $options); ?>
                        </div>
                        
                        <?php // получаем все справочники
						$size_manufacturer = \app\models\SizeManufacturer::find()->all();
						// формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
						$items = \yii\helpers\ArrayHelper::map($size_manufacturer,'code','name');
						$options = [
							'prompt'      => 'Выберите размер производтеля',
							'title'       => 'Выберите размер производтеля',
							'class'       => 'form-control ',
						];
						?>
						<?= Html::dropDownList('size_manufacturer', '', $items, $options); ?>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Поиск</button>
            </div>
        </form>
        <br>
        <br>

<!--        <div class="res">Результат</div>-->
<!---->
<!--        <br>-->
<!--        <br>-->



    </div>
</div>
