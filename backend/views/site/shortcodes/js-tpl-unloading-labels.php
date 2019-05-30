<?php
// todo Страница Выгрузка этикеток, шаблон tr строки, для сборки отсортированных строк по наименованию номеклатуры и по размеру
?>
<script type="html/tpl" id="unloading-labels">
<tr>
    <td class="content">{content}</td>
    <td class="manufacturer-size">{manufacturer_size}</td>
    <td class="inscription-label">{labeling}</td>
    <td class="barcode">{barcode}</td>
    <td class="retail-price">{retail_price}</td>
    <td class="action-price">{action_price}</td>
    <td class="automatic-discount">{automatic_discount}</td>
</tr>
</script>