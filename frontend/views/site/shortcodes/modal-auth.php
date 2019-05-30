<?php
// todo модальное окно авторизации
use yii\helpers\Html;
use backend\controllers\MainController as d;

//d::pre(Yii::$app->getRequest()->getCsrfToken());

?>
<!-- QUICKVIEW PRODUCT -->
<div id="quickview-wrapper">
    <!-- Modal -->
    <div class="modal fade" id="modal-auth" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="form-reg-auth form-user-auth" action="/ajax/user-auth" method="POST">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                        <div class="wrap-reg-auth wrap-auth">
                            <h3>Авторизация</h3>
                            <div class="input-box mb-20 w-email">
                                <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                <label class="control-label">E-Mail</label>
                                <span class="reg-error error-mail"></span>
                                <input type="email" placeholder="E-Mail" value="" name="email">
                            </div>
                            <div class="input-box">
                                <label class="control-label">Введите пароль</label>
                                <input type="password" placeholder="Пароль" value="" name="password" />
                                <label class="view-password" for="view-password" title="Показать пароль">
                                    <i class="zmdi zmdi-eye dn"></i>
                                    <i class="zmdi zmdi-eye-off"></i>
                                </label>
                                <span class="reg-error error-passwd"></span>
                            </div>
                        </div>
                        <div class="frm-action">
                            <div class="input-box tci-box">
                                <a href="#" class="btn-def btn2 btn-user-auth">
                                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                                    Войти
                                </a>
                            </div>
                            <span class="wrap-remember-me">
                                <label for="remember-me" class="remr remember-me">
                                <input type="checkbox" name="remember_me" class="remr remember-me" id="remember-me">
                                Запомнить меня</label>
                            </span>
                        </div>
                        <a href="#" class="forgotten forg">Забыли пароль?</a>
                        <?'<div class="res">result</div>'?>
                    </form>
                    <div class="modal-alerts">
                        <?=$alerts?>
                    </div>
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