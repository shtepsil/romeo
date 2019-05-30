<?php // todo Страница Выгрузка этикеток, шаблон строки html table

/*
 * Строки таблицы table
 */

// Составление содержания из строк
$content =
    (($nomenclature_name)?$nomenclature_name:'').
    (($color)?', '.$color:'').
    (($design)?', '.$design:'');

?>
<tr>
    <td class="content"><?=$content?></td>
    <td class="manufacturer-size"><?=$manufacturer_size?></td>
    <td class="inscription-label"><?=($labeling)?$labeling:''?></td>
    <td class="barcode"><?=$barcode?></td>
    <td class="retail-price"><?=$retail_price?></td>
    <td class="action-price"><?=$action_price?></td>
    <td class="automatic-discount"><?=$automatic_discount?></td>
</tr>