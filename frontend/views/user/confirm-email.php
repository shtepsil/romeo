<?php

use backend\controllers\MainController as d;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->title = 'Подтверждение Email';

$this->params['breadcrumbs'][] = 'Подтверждение Email';

// cmel - confirm-email
?>
<div class="cmel">
    <!--breadcumb area start -->
    <div class="breadcumb-area breadcumb-2 overlay pos-rltv">
        <div class="bread-main">
            <div class="bred-hading text-center dn">
                <h5><?= Html::encode($this->title) ?></h5> </div>

            <?=Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/'],
                'links' =>
                    isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [], ]);?>


        </div>
    </div>
    <!--breadcumb area end -->

    <!--service idea area are start -->
    <div class="idea-area  ptb-10">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="h3 text-center">Подтверждение Email</div>
                    <?if(!$params['error']):?>
                    <div class="description email-verified text-center success">
                        <br>
                        Ваш Email успешно подтвержден!<br><br>
                        <a href="#" class="btn btn-success no-link" data-toggle="modal" data-target="#modal-auth">
                            Перейти в Личный кабинет
                        </a>
                    </div>
                    <?else:?>
                    <div class="text-center">
                        <span class="warning">
                            <?=$params['error']?></span>
                    </div>
                    <?endif?>
                </div>
            </div>
        </div>
    </div>
    <!--service idea area are end -->

</div><!-- /confirm-email -->