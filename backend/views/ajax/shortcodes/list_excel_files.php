<?php
use yii\helpers\Html;
// todo Страница Загрузка файлов Excel row html table
?>
<tr>
    <th scope="row"><?=$id?></th>
    <td><?=$name?></td>
    <td><?=$date?> <?=$time?></td>
    <td class="td-del">
        <div class="w-del">
            <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
        </div>
        <span
            data-id="<?=$id?>"
            data-full-name="<?=$full_name?>"
            onclick="deleteFileExcel(this)"
        >Х</span>
    </td>
</tr>