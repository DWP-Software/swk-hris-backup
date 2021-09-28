<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>Please fill out your email. A link to reset password will be sent there.</p>

    <div class="row">
        <div class="col-lg-4">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

            <?= $form->field($model, 'email', [
                'template' => '<div class="input-group">
                    {input}
                    <span class="input-group-btn">
                        '.Html::submitButton('<i class="fa fa-paper-plane"></i>&nbsp; Send', ['class' => 'btn btn-primary']).'
                    </span>
                </div>
                <small>
                    {error}
                    {hint}
                </small>'
            ])->textInput(['autofocus' => true]) ?>

            <?php ActiveForm::end(); ?>
            
            <br>
            <div style="color:#999;margin:1em 0">
                <?= Html::a('<i class="fa fa-arrow-left"></i>&nbsp; Kembali ke Login', ['site/login']) ?>
            </div>
        </div>
    </div>
</div>
