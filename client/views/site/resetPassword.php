<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>Please choose your new password:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password', [
                'template' => '<div class="input-group">
                    {input}
                    <span class="input-group-btn">
                        '.Html::submitButton('<i class="fa fa-check"></i>&nbsp; Save', ['class' => 'btn btn-success']).'
                    </span>
                </div>
                <small>
                    {error}
                    {hint}
                </small>'
            ])->passwordInput(['autofocus' => true]) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
