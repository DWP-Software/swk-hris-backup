<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\PlacementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="placement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'employee_id') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'plan_employee_type') ?>

    <?= $form->field($model, 'plan_started_at') ?>

    <?php // echo $form->field($model, 'plan_ended_at') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'submitted_at') ?>

    <?php // echo $form->field($model, 'responded_at') ?>

    <?php // echo $form->field($model, 'response_type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
