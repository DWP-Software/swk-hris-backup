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
        'id' => 'form-education-formal'
    ]
]); ?>
<?php // echo $form->field($modelEducation, 'type')->dropDownList(['1' => 'Formal', '2' => 'Informal'], ['class' => 'form-control', 'prompt' => '']) ?>
<?php // echo $form->field($modelEducation, 'level')->dropDownList(Employee::educationLevels(), ['class' => 'form-control', 'prompt' => '']) ?>
<?= $form->field($modelEducation, 'name')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelEducation, 'place')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelEducation, 'year_start')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelEducation, 'year_end')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?php // echo $form->field($modelEducation, 'major')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelEducation, 'remark')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>

<div class="modal-footer text-right">
<?= 
    Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
    .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($modelEducation->isNewRecord ? 'Tambah' : 'Update'), ['class' => $modelEducation->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
?>
</div>

<?php ActiveForm::end(); ?>