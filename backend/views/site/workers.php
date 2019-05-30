<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 04.06.2018
 * Time: 16:24
 */

use backend\controllers\MainController as d;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\ReferenceBooks;
use \common\models\User;

$this->title = 'Работники';
/*
 * Класс rb, это сокращение: reference-books
*/

//echo Html::beginForm('#', '',[
//    'class' => '',
//    'enctype' => 'multipart/form-data',
//    'enableAjaxValidation' => true,
//]);

//$str = '1234ке';
//preg_match("/[^a-z\d-_]/iu",$str,$matches);
//if($matches) d::pre('Ошибка валидации');
//else d::pre('Строка правильная');
?>

<div class="wrap workers">
    <div class="container">
        <div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

        <div class="row">

            <div class="col-md-4 col-md-offset-4">
                <div class="w-list">
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>

                    <?php // получаем всех пользователей
                    $users = User::find()->orderBy('fio')->all();
                    $us = [];
                    $us[0] = ['value'=>'new','name'=>'Добавить работника'];
                    for($i=1;$i<count($users);$i++){
                        $us[$i]['value'] = $users[$i]['id'];
                        $us[$i]['name'] = $users[$i]['fio'];
                    }

                    // формируем массив, с ключем равным полю 'value' и значением равным полю 'name'
                    $items = ArrayHelper::map($us,'value','name');
                    $options = [
                        'prompt'      => 'Выберите работника',
                        'title'       => 'Выберите работника',
                        'class'       => 'form-control',
                        'data-type'        => 'edit',
                        'data-url'    => 'ajax/get-user',
                        'method' => 'post',
                    ];
                    ?>
                    <?= Html::dropDownList('workers', '', $items, $options); ?>
                </div>

                <input type="text" class="form-control" placeholder="Введите ФИО" title="Введите скорр. или новое значение" name="fio" disabled />
                <input type="text" class="form-control" placeholder="Введите логин" title="Введите скорр. или новое значение" name="username" disabled />
                <input type="text" class="form-control" placeholder="Введите пароль" title="Введите скорр. или новое значение" name="password" disabled />

                <select class="form-control reference-values" title="Работник активен да/нет" name="active" disabled>
                    <option value="">Работник активен да/нет</option>
                    <option value="1">Активен</option>
                    <option value="0">Не активен</option>
                </select>

                <select class="form-control reference-values" title="Права администратора да/нет" name="role" disabled>
                    <option value="">Права администратора</option>
                    <option value="user">Кассир</option>
                    <option value="admin">Администратор</option>
                </select>

            </div>

        </div>

        <br>

        <div class="row go-change">
            <div class="col-md-12 text-center">
                <button
                        type="button"
                        class="btn btn-success center"
                        data-url="ajax/signup"
                        method="post"
                        disabled
                >
                    <?=Html::img('@web/images/animate/loading.gif',['alt'=>'Загрузка','width'=>'20'])?>
                    <span>Внести изменения</span>
                </button>
            </div>
        </div>

        <!--        <br>-->
        <!--        <div class="res">result</div>-->

        <br>
        <?=$alerts?>
    </div><!-- container -->
</div>
<?php // Html::endForm(); ?>




