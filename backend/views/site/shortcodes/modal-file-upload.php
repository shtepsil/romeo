<div class="modal-background"></div>
<div class="modal-upload bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="h4">Загрузка файлов</div>

            <?
            /*
             * Кароч какая то фигня с формой
             * без первой формы, вторая форма не отображается
             * в принципе не мешает пока
             */

            echo \kato\DropZone::widget([
                'options' => [
                    'url' => Yii::$app->urlManager->createUrl('ajax/upload-files'),
                    'maxFilesize' => '200',
                ],
                'clientEvents' => [
                    'complete' => "function(file){}",
                    'removedfile' => "function(file){alert(file.name + ' is removed')}",
                ],
            ]);

            ?>

            <div class="modal-out">X</div>
<!--            <div class="res">res</div>-->

        </div>
    </div>
</div>