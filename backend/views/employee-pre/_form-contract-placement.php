<?php

use common\models\entity\User;
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

	<?= $form->field($model, 'client_id')->widget(Select2::classname(), [
		'data' => ArrayHelper::map(Client::find()->all(), 'id', 'name'),
		'options' => ['placeholder' => ''],
		'pluginOptions' => ['allowClear' => true],
	]); ?>

	<?= $form->field($model, 'position')->textInput(['maxlength' => true]); ?>
	<?= $form->field($model, 'location')->textInput(['maxlength' => true]); ?>
	<?= $form->field($model, 'department')->textInput(['maxlength' => true]); ?>

	<?= $form->field($model, 'started_at')->widget(DatePicker::classname(), [
		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
		// 'readonly' => true,
		'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
	]); ?>

	<?= $form->field($model, 'ended_at')->widget(DatePicker::classname(), [
		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
		// 'readonly' => true,
		'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
	]); ?>

    <div class="modal-footer text-right">
    <?= 
        Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
        .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Tambah' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
    ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>