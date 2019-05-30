<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login auth-admin">

    <div class="row">
        <div class="col-lg-12">
            <?php /* $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); */ ?>

            <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => [
                            'class' => 'login'
                    ],
            ]); ?>
                <p>
<!--                    <label for="login">Логин:</label>-->
<!--                    <input type="text" name="login" id="login" value="name@example.com">-->
                    <?= $form->field($model, 'username')->textInput(
//                            ['autofocus' => true]
                    ) ?>
                </p>

                <p>
<!--                    <label for="password">Пароль:</label>-->
<!--                    <input type="password" name="password" id="password" value="4815162342">-->
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </p>

                <p class="login-submit">
                    <?= Html::submitButton('Войти', [
                        'class' => 'login-button',
                        'name' => 'login-button'
                    ]) ?>
                </p>
                
<!--                <p class="forgot-password"><a href="index.html">Забыл пароль?</a></p>-->
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
