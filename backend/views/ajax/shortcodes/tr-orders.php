<?php // todo Страница "Заказы", строка tr табилцы ?>
<tr>
    <td><?=$info?></td>
    <td><?=$size?></td>
    <td><?=$barcode?></td>
    <td class="ret-price"><?=number_format($retail_price,2,',','')?></td>
    <td class="disc-price"><?=number_format($discount_price,2,',','')?></td>
    <td><?=$discount?></td>
</tr>
