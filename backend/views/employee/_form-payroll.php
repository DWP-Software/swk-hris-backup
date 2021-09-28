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
use common\models\entity\PlacementContract;
use common\models\entity\PlacementContractSalary;
use yii\bootstrap\Modal;

?>

<?php $form = ActiveForm::begin([
    // 'layout' => 'horizontal',
    'options' => [
        'id' => 'form-payroll'
    ]
]); ?>
<?= Html::activeHiddenInput($model, 'placement_contract_id') ?>
<div class="row">
<div class="col-sm-6">
<?= $form->field($model, 'year')->dropDownList(years()) ?>
</div>
<div class="col-sm-6">
<?= $form->field($model, 'month')->dropDownList(months()) ?>
</div>
</div>

<br>
<div class="detail-view-container" id="detail">
    <!-- detail -->
</div>

<div class="modal-footer text-right">
<?= 
    Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
    .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Tambah' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
?>
</div>

<?php ActiveForm::end(); ?>


<?php 
$url = Url::to(['/employee/pre-payroll']);
$js = 
<<<JAVASCRIPT
$(document).ready(function() {
    retrievePrePayroll(); 

	$('#payroll-year').on("change", function(e) { 
		retrievePrePayroll();
    });
    $('#payroll-month').on("change", function(e) { 
		retrievePrePayroll();
    });
    
    function retrievePrePayroll() {
        $('#detail').html('');
        $.get("{$url}", { placement_contract_id: $("#payroll-placement_contract_id").val(), year: $("#payroll-year").val(), month: $("#payroll-month").val() }, function (response) {
			$('#detail').html(response);
		});
    }
});
JAVASCRIPT;
$this->registerJs($js, \yii\web\View::POS_READY);
// $this->registerJsFile(
//     '@web/js/_form-payroll.js',
//     ['depends' => [\yii\web\JqueryAsset::className()]]
// );
?>