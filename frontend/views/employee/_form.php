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

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <hr style="border-color:#ddd">
    <h3>Data Diri</h3>

    <?= $form->field($model, 'identity_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'place_of_birth')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_of_birth')->widget(DatePicker::classname(), [
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'readonly' => true,
        'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
    ]); ?>

    <?= $form->field($model, 'sex')->widget(Select2::classname(), [
        'data' => Employee::sexes(),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'religion')->widget(Select2::classname(), [
        'data' => Employee::religions(),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'education_level')->widget(Select2::classname(), [
        'data' => Employee::educationLevels(),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'family_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mother_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nationality')->widget(Select2::classname(), [
        'data' => Employee::nationalities(),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'height')->textInput() ?>
    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'marital_status')->widget(Select2::classname(), [
        'data' => Employee::maritalStatuses(),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'blood_type')->widget(Select2::classname(), [
        'data' => Employee::bloodTypes(),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'bank_name')->textInput() ?>
    <?= $form->field($model, 'bank_account')->textInput() ?>

    <hr style="border-color:#ddd">
    <h3>Alamat Sesuai KTP</h3>
    
    <?= $form->field($model, 'address_province_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Province::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'address_district_id')->widget(DepDrop::classname(), [
        'type'          => DepDrop::TYPE_SELECT2,
        'data'          => [$model->address_district_id => !$model->address_district_id ? '' : District::findOne($model->address_district_id)->name],
        'pluginOptions' => [
            'depends'     => ['registerform-address_province_id'],
            'initialize'  => true,
            'placeholder' => 'Select...',
            'url'         => Url::to(['/area/district'])
        ]
    ]); ?>

    <?= $form->field($model, 'address_subdistrict_id')->widget(DepDrop::classname(), [
        'type'          => DepDrop::TYPE_SELECT2,
        'data'          => [$model->address_subdistrict_id => !$model->address_subdistrict_id ? '' : Subdistrict::findOne($model->address_subdistrict_id)->name],
        'pluginOptions' => [
            'depends'     => ['registerform-address_province_id', 'registerform-address_district_id'],
            'initialize'  => true,
            'placeholder' => 'Select...',
            'url'         => Url::to(['/area/subdistrict'])
        ]
    ]); ?>

    <?= $form->field($model, 'address_village_id')->widget(DepDrop::classname(), [
        'type'          => DepDrop::TYPE_SELECT2,
        'data'          => [$model->address_village_id => !$model->address_village_id ? '' : Village::findOne($model->address_village_id)->name],
        'pluginOptions' => [
            'depends'     => ['registerform-address_province_id', 'registerform-address_district_id', 'registerform-address_subdistrict_id'],
            'initialize'  => true,
            'placeholder' => 'Select...',
            'url'         => Url::to(['/area/village'])
        ]
    ]); ?>

    <?= $form->field($model, 'address_neighborhood')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_street')->textInput(['maxlength' => true]) ?>


    <hr style="border-color:#ddd">
    <h3>Alamat Domisili Saat Ini</h3>

    <?= $form->field($model, 'domicile_province_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Province::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'domicile_district_id')->widget(DepDrop::classname(), [
        'type'          => DepDrop::TYPE_SELECT2,
        'data'          => [$model->domicile_district_id => !$model->domicile_district_id ? '' : District::findOne($model->domicile_district_id)->name],
        'pluginOptions' => [
            'depends'     => ['registerform-domicile_province_id'],
            'initialize'  => true,
            'placeholder' => 'Select...',
            'url'         => Url::to(['/area/district'])
        ]
    ]); ?>

    <?= $form->field($model, 'domicile_subdistrict_id')->widget(DepDrop::classname(), [
        'type'          => DepDrop::TYPE_SELECT2,
        'data'          => [$model->domicile_subdistrict_id => !$model->domicile_subdistrict_id ? '' : Subdistrict::findOne($model->domicile_subdistrict_id)->name],
        'pluginOptions' => [
            'depends'     => ['registerform-domicile_province_id', 'registerform-domicile_district_id'],
            'initialize'  => true,
            'placeholder' => 'Select...',
            'url'         => Url::to(['/area/subdistrict'])
        ]
    ]); ?>

    <?= $form->field($model, 'domicile_village_id')->widget(DepDrop::classname(), [
        'type'          => DepDrop::TYPE_SELECT2,
        'data'          => [$model->domicile_village_id => !$model->domicile_village_id ? '' : Village::findOne($model->domicile_village_id)->name],
        'pluginOptions' => [
            'depends'     => ['registerform-domicile_province_id', 'registerform-domicile_district_id', 'registerform-domicile_subdistrict_id'],
            'initialize'  => true,
            'placeholder' => 'Select...',
            'url'         => Url::to(['/area/village'])
        ]
    ]); ?>

    <?= $form->field($model, 'domicile_neighborhood')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'domicile_street')->textInput(['maxlength' => true]) ?>            
        
    <hr style="border-color:#ddd">
    <h3>Dokumen</h3>

    <?= $form->field($model, 'file_photo')->fileInput() ?>
    <?= $form->field($model, 'file_identity_card')->fileInput() ?>
    <?= $form->field($model, 'file_family_certificate')->fileInput() ?>
    <?= $form->field($model, 'file_transcript')->fileInput() ?>
    <?= $form->field($model, 'file_certificate')->fileInput() ?>

    <div class="form-panel">
        <div class="row">
    	    <div class="col-sm-12 text-right">
                <?= Html::a('Kembali', ['/'], ['class' => 'btn']) ?>
    	        <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Create' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
	    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
