<?php
// todo модальное окно регистрации
use yii\helpers\Html;
use backend\controllers\MainController as d;

//d::pre(Yii::$app->getRequest()->getCsrfToken());

?>
<!-- QUICKVIEW PRODUCT -->
<div id="quickview-wrapper">
    <!-- Modal -->
    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="form-user-register" action="/ajax/user-register" method="POST">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                        <div class="wrap-reg">
                            <h3>Регистрация</h3>
                            <div class="input-box mb-20 w-email">
                                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                <label class="control-label">E-Mail</label>
                                <span class="reg-error error-mail"></span>
                                <input type="email" placeholder="E-Mail" value="akvarius_84@mail.ru" name="email">
                            </div>
                            <div class="input-box">
                                <label class="control-label">Придумайте пароль</label>
                                <input type="password" placeholder="Пароль" value="123456" name="password" />
                                <label class="view-password" for="view-password" title="Показать пароль">
                                    <i class="zmdi zmdi-eye dn"></i>
                                    <i class="zmdi zmdi-eye-off"></i>
                                </label>
                                <span class="reg-error error-passwd"></span>
                            </div>
                        </div>
                        <div class="frm-action">
                            <div class="input-box tci-box">
                                <a href="#" class="btn-def btn2 btn-user-register">
                                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                    Зарегистрироваться
                                </a>
                            </div>
                        </div>
                        <div class="res">result</div>
                    </form>
                </div>
                <!-- .modal-body -->
            </div>
            <!-- .modal-content -->
        </div>
        <!-- .modal-dialog -->
    </div>
    <!-- END Modal -->
</div>
<!-- END QUICKVIEW PRODUCT -->