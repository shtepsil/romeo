<?php // todo модальное окно подтверждение удаления аккаунта

use yii\helpers\Html;

?>
<!-- Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Подтверждение удаления аккаунта</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-delete-confirm dn">Вы действительно хотите удалить ваш аккаунт?</div>
                <div class="reload-after-delete">
                    Ваш аккаунт удален!<br>
                    Сейчас вы будете перенаправлены на главную странцу сайта...&nbsp;&nbsp;
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'reload-to-main'])?>
                </div>
                <div class="errors"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button
                    type="button"
                    class="btn btn-danger btn-delete-profile"
                    data-url="/ajax/delete-user-profile"
                    method="post"
                >
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20','class'=>'animate-load'])?>
                    Удалить
                </button>
                <?'<div class="res">result</div>'?>
            </div>
        </div>
    </div>
</div>