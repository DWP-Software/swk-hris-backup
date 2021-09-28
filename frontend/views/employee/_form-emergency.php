<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use common\models\entity\Employee;
use common\models\entity\Province;
use common\models\entity\District;
use common\models\entity\Subdistrict;
use common\models\entity\Village;
use yii\bootstrap\Modal;

?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => [
        'id' => 'form-emergency'
    ]
]); ?>
<?= $form->field($modelEmergency, 'name')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelEmergency, 'relationship')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelEmergency, 'phone')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelEmergency, 'address')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>

<div class="modal-footer text-right">
<?= 
    Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
    .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($modelEmergency->isNewRecord ? 'Tambah' : 'Update'), ['class' => $modelEmergency->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
?>
</div>

<?php ActiveForm::end(); ?>