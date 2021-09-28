<?php

use common\models\entity\Client;
use common\models\entity\Employee;
use common\models\entity\EmployeeType;
use common\models\entity\Placement;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id'      => 'form-pic',
        'enctype' => 'multipart/form-data',
    ]
]); ?>

<?= $form->field($model, 'client_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(Client::find()->all(), 'id', 'name'),
    'options' => ['placeholder' => ''],
    'pluginOptions' => ['allowClear' => true],
]); ?>

<div class="modal-footer text-right">
<?= 
    Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
    .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Tambah' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
?>
</div>

<?php ActiveForm::end(); ?>
