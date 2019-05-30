<?php

use backend\controllers\MainController as d;
use yii\helpers\Html;

$this->title = 'Отправка Eamil';


/*
 * Класс eml, это сокращение: email
*/

//d::pre(date('Y-m-d',1548149584));

?>

<div class="wrap eml">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="row">

            <div class="col-md-6">
				<div class="emails">
					<input type="radio" name="email" value="akvarius_84@mail.ru" id="e1" checked />
					<label for="e1">akvarius_84@mail.ru</label><br>
					<input type="radio" name="email" value="akbapuyc@yandex.ru" id="e2" />
					<label for="e2">akbapuyc@yandex.ru</label><br>
					<input type="radio" name="email" value="brag.serg@gmail.com" id="e3" />
					<label for="e3">brag.serg@gmail.com</label><br><br>
				</div>
                <button class="btn btn-primary btn-xs send-mail"
                        name="serebros"
                        action="ajax/send-mail"
                        method="post">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    Отправить Email
                </button>
            </div>
        </div><!-- row -->
        <br>

        <?=$alerts?>
        <?'<div class="res">result</div>'?>


    </div>
</div>