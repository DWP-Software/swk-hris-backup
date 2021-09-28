<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\PresenceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="presence-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'employee_id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'started_at') ?>

    <?php // echo $form->field($model, 'ended_at') ?>

    <?php // echo $form->field($model, 'is_late') ?>

    <?php // echo $form->field($model, 'overtime_started_at') ?>

    <?php // echo $form->field($model, 'overtime_ended_at') ?>

    <?php // echo $form->field($model, 'overtime_summary') ?>

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
