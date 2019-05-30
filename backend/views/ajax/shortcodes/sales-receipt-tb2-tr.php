<?php // todo Раздел 2 Продажа сертификата

$ko = Yii::getAlias('@ko');
$fl = Yii::getAlias('@fl');
$th = Yii::getAlias('@th');

?>
<tr class="section2">
    <td class="w-checkbox" colspan="2">
        <input type="hidden" name="section" value="2" />
<!--        <input class="checkbox" type="checkbox" name="checkbox">-->
        <span class="glyphicon glyphicon-remove" onclick="deleteTrSR(this)" title="Удалить строку"></span>
    </td>
    <td class="description">Сертификат,Подарочный</td>
    <td class="td-barcode24" id="barcode"><?=$arr_c['barcode']?></td>
    <td class="nominal"><?=$arr_c['certificate_denomination']?></td>
    <td>1</td>
    <td class="sbs2"><?=number_format(($arr_c['certificate_denomination'] * 1), $ko, $fl, $th)?></td>
    <td class="text-right sp2" colspan="8"><?=number_format(($arr_c['certificate_denomination'] * 1), $ko, $fl, $th)?></td>
</tr>