<?php

use common\models\entity\AuthItem;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\entity\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

<div class="row">
<div class="col-md-8 col-sm-12">

    <?php $form = ActiveForm::begin(); ?>

    <?= $model->isNewRecord ? $form->field($model, 'email')->textInput(['maxlength' => true]) : '' ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true])->hint($model->isNewRecord ? '' : 'kosongkan jika ingin tetap menggunakan password yg ada saat ini.') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->statuses()) ?>

    <?= $form->field($model, 'role')->checkboxList([
        'Administrator' => 'Administrator',
        'Rekrutmen'     => 'Rekrutmen',
        'Kehadiran'     => 'Kehadiran',
        'Keuangan'      => 'Keuangan',
    ], ['style' => 'margin-left:10px']) ?>

    <div class="form-panel">
        <div class="row">
    	    <div class="col-sm-12">
    	        <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Create' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
	    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>

</div>
