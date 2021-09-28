<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
// $this->params['breadcrumbs'][] = $this->title;
Yii::$app->params['showTitle'] = false;
?>
<div class="login-box">
    <div class="text-center">
        <h2>
            <b style="font-size:50px">S.W.K</b>
        </h2>
        <p class="text-muted" style="font-size:18px;">User Panel Mitra</p>
    </div>
    <br>
    <div class="box box-primary form-panel" style="padding:20px; overflow:hidden">
        
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <div class="col-xs-4">
                    <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="text-right" style="margin-right:20px"><?= Html::a('Forgot Password', ['site/request-password-reset']) ?></div>
</div>
