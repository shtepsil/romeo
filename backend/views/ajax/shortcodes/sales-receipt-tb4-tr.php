<?php // todo Раздел 4 Отоваривание сертификата

$ko = Yii::getAlias('@ko');
$fl = Yii::getAlias('@fl');
$th = Yii::getAlias('@th');

?>
<tr class="section4">
    <td class="w-checkbox" colspan="2">
        <input type="hidden" name="section" value="4" />
<!--        <input class="checkbox" type="checkbox" name="checkbox">-->
        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
    </td>
    <td class="description">Отоваривание подарочного сертификата, <?=$arr_c['date_of_sale']?></td>
    <td class="td-barcode24" id="barcode"><?=$arr_c['barcode']?></td>
    <td class="nominal"><?=number_format($arr_c['certificate_denomination'], $ko, $fl, $th)?></td>
    <td colspan="7">1</td>
    <td class="sps4"><?=number_format(($arr_c['certificate_denomination'] * 1), $ko, $fl, $th)?></td>
</tr>