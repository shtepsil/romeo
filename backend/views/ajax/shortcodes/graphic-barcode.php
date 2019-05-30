<?php // todo HTML одного блока графического штрихкода для печати ?>
<div class="barcode">
    <div class="info"><?=$description?></div>
    <div class="graphic">
        <img src="data:image/png;base64,<?=base64_encode($graphic_barcode)?>" alt="barcode"/>
    </div>
</div>