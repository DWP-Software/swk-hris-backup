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
use common\models\entity\ContractSalary;
use yii\bootstrap\Modal;

?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => [
        'id' => 'form-salary'
    ]
]); ?>
<?= $form->field($model, 'type')->dropDownList(ContractSalary::types(), ['prompt' => 'pilih jenis...', 'onchange' => 'setPrefixName(this.value)'])->label(false) ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'nama komponen'])->label(false) ?>
<?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'placeholder' => 'jumlah'])->label(false) ?>
<?= $form->field($model, 'description')->textInput(['maxlength' => true, 'placeholder' => 'keterangan'])->label(false) ?>

<div class="modal-footer text-right">
<?= 
    Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
    .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Tambah' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
?>
</div>

<?php ActiveForm::end(); ?>


<?php 
$js = 
<<<JAVASCRIPT
    function setPrefixName(value) {
        $('#contractsalary-name').val('');
        if (value == '1') { 
            $('#contractsalary-name').val('Gaji Pokok');
        }
    }
JAVASCRIPT;
$this->registerJs($js, \yii\web\View::POS_READY);
?>
