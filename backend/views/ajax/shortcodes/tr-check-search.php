<?php // todo Страница "Поиск чека", строка tr табилцы

// Собираем строку описания
$d = '';
$d .= ($nomenclature_name)?$nomenclature_name:'';
$d .= ($color)?(($d)?', ':'').$color:'';
$d .= ($design)?(($d)?', ':'').$design:'';
$d .= ($manufacturer_size)?(($d)?', ':'').$manufacturer_size:'';

?>
<tr>
    <td><?=$document_id?></td>
    <td><?=$date_time?></td>
    <td><?=$barcode?></td>
    <td><?=$d?></td>
    <td><?=$retail_price?></td>
</tr>