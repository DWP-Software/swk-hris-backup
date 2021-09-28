<?php

use common\models\entity\Client;
use common\models\entity\Employee;
use common\models\entity\EmployeeType;
use common\models\entity\Placement;
use common\models\entity\ContractSalary;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="row">
    <div class="col-md-4">
        <div class="detail-view-container" style="margin-bottom:10px">
            <h5 style="background:#f4f4f4;padding:10px;margin:0">KONTRAK</h5>
            <div style="padding:10px;">

                <div class="detail-view-container box-body" style="margin-bottom:5px; background: #f4f4f4">
                    <b><?= $model->getAttributeLabel('contract_number') ?></b>
                    <span class="pull-right"><?= $model->contract_number ?? '<span class="text-muted">auto</span' ?></span>
                </div>
                <p></p>
                
                <?= $form->field($model, 'employee_type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(EmployeeType::find()->all(), 'id', 'name'),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>

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

                <?= $form->field($model, 'pasal_3_2')->checkbox(); ?>
                <?= $form->field($model, 'pasal_3_3')->textInput(); ?>
                <?= $form->field($model, 'payment_date')->textInput(); ?>

                <?= $form->field($model, 'signer_name')->textInput(); ?>
                <?= $form->field($model, 'signer_position')->textInput(); ?>
                <?= $form->field($model, 'signer_address')->textarea(['rows' => 3]); ?>

                <?= $model->isNewRecord ? '' : $form->field($model, 'uploaded_file')->widget(FileInput::className(), [        
                    'options' => [
                        'accept' => 'image/*, application/pdf'
                    ],
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showUpload' => false,
                    ],
                ])
                ->label('File Kontrak')
                ->hint('Scan Kontrak yang sudah selesai ditandatangani') ?>

            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="detail-view-container" style="margin-bottom:10px">
            <!-- <h5 style="background:#f4f4f4;padding:10px;margin:0">GAJI</h5> -->
            <div style="padding:10px;">
                <?php 
                    echo '<div class="row">';
                    foreach (ContractSalary::types() as $type_key => $type_value) {
                        echo strtoupper($type_value) == "GAJI POKOK" ? '<div class="col-md-12">' : '<div class="col-md-6">';
                        echo '<div class="detail-view-container" style="margin-bottom:10px">';
                        echo '<h5 style="background:#f4f4f4;padding:10px;margin:0">'.strtoupper($type_value).'</h5>';
                        echo '<div style="padding:10px;">';
                        foreach (ContractSalary::permanentTypes($type_key) as $subtype_key => $subtype_value) {
                            $initial_value = null;
                            if (($salary = ContractSalary::findOne([
                                'contract_id' => $model->id,
                                'type'        => $type_key,
                                'name'        => $subtype_value,
                            ])) !== null) {
                                $initial_value = $salary->amount;
                            }
                            ?>
                                <div class="form-group">
                                    <label><?= $subtype_value ?></label>
                                    <?= Html::textInput($subtype_value, $initial_value, ['class' => 'form-control', 'id' => Inflector::slug($subtype_value)]) ?>
                                </div>
                            <?php
                        }
                        /* foreach (ContractSalary::conditionalTypes($type_key) as $subtype_key => $subtype_value) {
                            ?>
                                <div class="form-group">
                                    <label><?= $subtype_value ?></label>
                                    <?= Html::textInput($subtype_value, null, ['class' => 'form-control']) ?>
                                </div>
                            <?php
                        } */
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                ?>
            </div>
        </div>
    </div>
</div>
    
<div class="form-panel">
    <div class="row">
        <div class="col-sm-12">
            <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Create' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

<?php 

$js = <<<JAVASCRIPT
$('#gaji-pokok').keyup(function() {
    gaji_pokok  = parseInt(this.value);
    bpjs_tk_jht = gaji_pokok * 0.02;
    bpjs_tk_jp  = gaji_pokok * 0.01;
    bpjs_ks     = gaji_pokok * 0.01;
    $('#bpjs-tk-jht').val(bpjs_tk_jht);
    $('#bpjs-tk-jp').val(bpjs_tk_jp);
    $('#bpjs-ks').val(bpjs_ks);
});
JAVASCRIPT;

$this->registerJs($js, \yii\web\View::POS_READY);

?>