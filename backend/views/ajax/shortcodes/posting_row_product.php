<?php // todo Страница Оприходование товара row html table

$content = (($nomenclature_name)?$nomenclature_name:'').
           (($color)?', '.$color:'').
           (($design)?', '.$design:'').
           (($manufacturer_size)?', '.$manufacturer_size:'');

?>
<tr>
    <td class="content"><?=$content?></td>
    <td class="barcode" id="barcode"><?=$barcode?></td>
    <td class="quantity" id="quantity">1</td>
    <td class="cost-price"><?=$cost_price?></td>
    <td class="retail-price"><?=$retail_price?></td>
</tr>