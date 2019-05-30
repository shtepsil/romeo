<?php // todo шаблон <tr> для страницы "поступление товара"

use yii\helpers\Html;

?>
<tr>
    <td>
        <input type="hidden" name="serial_number" value="<?=$sn?>" />
        <span><?=$sn?></span>
    </td>
    <td><input name="delete_on" type="checkbox" /></td>
    <td class="td1">
        <?php // получаем все справочники
        $brand = \app\models\Brand::find()->orderBy('name')->all();?>
        <select name="brand" id="" class="form-control c-input-sm brands" title="Выберите бренд" action="ajax/list-value" method="post" onchange="getVendorCodeByBrand(this)">
            <option value="" selected>Бренд</option>
            <?foreach($brand as $val):?>
                <option
                    value="<?=$val['code']?>"
                    data-code="<?=$val['code']?>"
                    <?=($val['code'] == $brand_code)?' selected':''?>>
                    <?=$val['name']?>
                </option>
            <?endforeach;?>
        </select>
    </td>
    <td class="td2">
        <select
            name="articul"
            class="form-control c-input-sm vendor-code"
            title="Выберите артикул"
            onchange="getDataByBrandAndVendorCode(this)"
            action="ajax/product-nomenclature"
            method="post"
        >
            <?if($tr_quantity > 0):?>
                <?=$list_options?>
            <?else:?>
                <option>Список артикулов пока пуст</option>
            <?endif?>
		</select>
    </td>
    <td class="product_group td3" code="<?=($product_group_code != '')?$product_group_code:''?>" title="Товарная группа">
        <?=($product_group_text != '')?$product_group_text:'Товарная группа'?>
    </td>
    <td class="name_nomenclature td4" gender="<?=($gender != '')?$gender:''?>" title="Наименование номенклатуры">
        <input type="hidden" name="item_code" value="<?=($item_code != '')?$item_code:''?>" />
        <span><?=($name_nomenclature_text != '')?$name_nomenclature_text:'Наименование номенклатуры'?></span>
    </td>
    <td class="td5">
        <?php // получаем все справочники
        $size_manufacturer = \app\models\SizeManufacturer::find()->orderBy('name')->all();
        // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
        $items = \yii\helpers\ArrayHelper::map($size_manufacturer,'id','name');
        $options = [
            'prompt'       => '',
            'title'        => 'Размер производителя',
            'defaultValue' => 0,
            'class'        => 'form-control c-input-sm',
            'onchange'     => 'createBarcode(this)',
            'action'       => 'ajax/product-nomenclature',
            'method'       => 'post',
        ];
        ?>
        <?= Html::dropDownList('code_manufacturer_size', '', $items, $options); ?>
    </td>
    <td class="td6">
        <?php // получаем все справочники
        $size_russian = \app\models\SizeRussian::find()->orderBy('name')->all();
        // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
        $items = \yii\helpers\ArrayHelper::map($size_russian,'id','name');
        $options = [
            'prompt'      => '',
            'title'       => 'Размер российский',
            'defaultValue' => 0,
            'class'       => 'form-control c-input-sm',
        ];
        ?>
        <?= Html::dropDownList('code_size_russian', '', $items, $options); ?>
    </td>
    <td class="td7">
        <?php // получаем все справочники
        $russian_growth = \app\models\RussianGrowth::find()->orderBy('name')->all();
        // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
        $items = \yii\helpers\ArrayHelper::map($russian_growth,'id','name');
        $options = [
            'prompt'      => '',
            'title'       => 'Рост российский',
            'defaultValue' => 0,
            'class'       => 'form-control c-input-sm',
        ];
        ?>
        <?= Html::dropDownList('code_growth_russian', '', $items, $options); ?>
    </td>
    <td class="td8">
        <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
        <input type="hidden" name="barcode" value="" />
        <span>Штрихкод</span>
    </td>
    <td class="td10">
        <input name="quantity" type="text" class="form-control c-input-sm amount" placeholder="" title="Количество, шт" value="<?=($quantity != '')?$quantity:''?>" onfocus="focusReturnStyle(this)" onkeyup="isNumeric(this); totalProductInfo()" />
    </td>
    <td class="td9">
        <input name="cost_of_goods" type="text" class="form-control c-input-sm cost-price" placeholder="" title="Себестоимость, руб." value="<?=($cost_of_goods != '')?$cost_of_goods:''?>" onkeyup="isNumeric(this,'n,'); totalProductInfo()" />
    </td>
    <td class="td11">
        <input name="retail_price" type="text" class="form-control c-input-sm retail-price" placeholder="" title="Розничная цена, руб." value="<?=($retail_price != '')?$retail_price:''?>" onkeyup="isNumeric(this,'n,'); totalProductInfo()" />
    </td>
    <?php // <td><span class="glyphicon glyphicon-remove" onclick="deleteTr(this)" title="Удалить строку"></span></td> ?>
</tr>