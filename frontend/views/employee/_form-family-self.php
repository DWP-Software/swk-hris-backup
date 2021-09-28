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
use common\models\entity\EmployeeFamily;
use common\models\entity\Subdistrict;
use common\models\entity\Village;
use yii\bootstrap\Modal;

?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => [
        'id' => 'form-family-self'
    ]
]); ?>
<?php // echo $form->field($modelFamily, 'type')->dropDownList(['1' => 'Keluarga Inti', '2' => 'Keluarga Orang Tua'], ['class' => 'form-control', 'prompt' => '']) ?>
<?= $form->field($modelFamily, 'position')->dropDownList(EmployeeFamily::positions(), ['class' => 'form-control', 'prompt' => '', 'onchange' => 'toggleSequence(this.value)']) ?>
<div id="sequence">
<?= $form->field($modelFamily, 'sequence')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
</div>
<?= $form->field($modelFamily, 'name')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelFamily, 'sex')->widget(Select2::classname(), [
    'data' => Employee::sexes(),
    'options' => ['placeholder' => ''],
    'pluginOptions' => ['allowClear' => true],
]); ?>
<?= $form->field($modelFamily, 'date_of_birth')->widget(DatePicker::classname(), [
    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    'readonly' => true,
    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
]); ?>
<?= $form->field($modelFamily, 'place_of_birth')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
<?= $form->field($modelFamily, 'education_level')->widget(Select2::classname(), [
    'data' => Employee::educationLevels(),
    'options' => ['placeholder' => ''],
    'pluginOptions' => ['allowClear' => true],
]); ?>
<?= $form->field($modelFamily, 'occupation')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>

<div class="modal-footer text-right">
<?= 
    Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
    .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($modelFamily->isNewRecord ? 'Tambah' : 'Update'), ['class' => $modelFamily->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
?>
</div>

<?php ActiveForm::end(); ?>

<?php 
$js = 
<<<JAVASCRIPT
    toggleSequence();
    
    function toggleSequence(value) {
        if (value == '3') { 
            $('#sequence').show();
        } else {
            $('#sequence').hide();
            $('#employeefamily-sequence').val('');
        }
    }
JAVASCRIPT;
$this->registerJs($js, \yii\web\View::POS_READY);
?>
