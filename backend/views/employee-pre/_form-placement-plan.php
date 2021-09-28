<?php

use common\models\entity\Client;
use common\models\entity\Employee;
use common\models\entity\EmployeeType;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">


    <?php $form = ActiveForm::begin(); ?>
<!-- 
    <?= $form->field($model, 'employee_id')->widget(Select2::classname(), [
		'data' => ArrayHelper::map(Employee::find()->all(), 'id', 'name'),
		'options' => ['placeholder' => ''],
		'pluginOptions' => ['allowClear' => true],
	]); ?> -->

	<?= $form->field($model, 'client_id')->widget(Select2::classname(), [
		'data' => ArrayHelper::map(Client::find()->all(), 'id', 'name'),
		'options' => ['placeholder' => ''],
		'pluginOptions' => ['allowClear' => true],
	]); ?>
<!-- 
	<?= $form->field($model, 'plan_employee_type')->widget(Select2::classname(), [
		'data' => ArrayHelper::map(EmployeeType::find()->all(), 'id', 'name'),
		'options' => ['placeholder' => ''],
		'pluginOptions' => ['allowClear' => true],
	]); ?>

	<?= $form->field($model, 'plan_started_at')->widget(DatePicker::classname(), [
		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
		'readonly' => true,
		'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
	]); ?>

	<?= $form->field($model, 'plan_ended_at')->widget(DatePicker::classname(), [
		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
		'readonly' => true,
		'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
	]); ?>
 -->
	<?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>
	
	<?= $form->field($model, 'response_type')->checkbox()->label('Langsung tempatkan tanpa perlu persetujuan Mitra') ?>

    
    <div class="modal-footer text-right">
    <?= 
        Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
        .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Tambah' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
    ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>