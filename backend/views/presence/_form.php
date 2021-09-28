<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\models\entity\Employee;
use common\models\entity\Presence;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Presence */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="presence-form">

<div class="row">
<div class="col-md-8 col-sm-12">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        if (Yii::$app->user->can('Administrator')) {
            $data = ArrayHelper::map(Employee::find()->all(), 'id', 'shortTextLatestContract');
        } else {
            $data = ArrayHelper::map(Employee::find()->joinWith(['latestContractPlacement.contractPlacement.client.picClients'])->where(['pic_client.user_id' => Yii::$app->user->id, 'role' => 'Kehadiran'])->all(), 'id', 'shortTextLatestContract');
        }
    ?>
    <?= $form->field($model, 'employee_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'readonly' => true,
        'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
    ]); ?>

    <?= $form->field($model, 'status')->widget(Select2::className(), [
        'data' => Presence::presenceTypes(),
        'pluginOptions' => ['allowClear' => true, 'placeholder' => ''],
    ]) ?>

    <?php // echo $form->field($model, 'is_late')->checkbox() ?>

    <?php // echo $form->field($model, 'overtime_summary')->textInput() ?>

    
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
