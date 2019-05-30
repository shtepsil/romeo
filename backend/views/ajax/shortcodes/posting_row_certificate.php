<?php // todo Страница Оприходование сертификата row html table ?>
<tr>
    <td class="barcode">
        <div class="w-barcode">

            <?if($accrued == '1'):?>
            <button
                type="button"
                class="btn btn-secondary"
                id="popover"
                data-content="<?=$label?>"
                onmouseover="popoverShow()"
                <? //onmouseout="popoverHide()" ?>
            >
        <span class="label label-success">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                </span>
            </button>

            <?endif?>
            <span class="text"><?=$barcode?></span>
        </div>
    </td>
    <td class="nom"><?=$certificate_denomination?></td>
</tr>
