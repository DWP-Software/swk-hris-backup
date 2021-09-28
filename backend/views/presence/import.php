<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Package */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Import Kehadiran';
$this->params['breadcrumbs'][] = ['label' => 'Kehadiran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$years = [];
for ($i = date('Y'); $i > date('Y') - 2 ; $i--) { 
    $years[$i] = $i;
}

$months = [];
for ($i = 1; $i <= 12 ; $i++) { 
    $months[$i] = date('F', mktime(0, 0, 0, $i, 10));
}
?>

<div class="package-form row">
<div class="col-md-8 col-sm-12">

    <p>Format file yang diupload harus sesuai template. <br>
        <?= Html::a('Download Template', Url::base().'/files/TemplateRekapAbsen.xlsx') ?>
    </p>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="form-group">
        <?= Html::dropDownList('year', date('Y'), $years, ['class' => 'form-control', 'prompt' => 'pilih tahun...']) ?>
    </div>
    <div class="form-group">
        <?= Html::dropDownList('month', date('m'), $months, ['class' => 'form-control', 'prompt' => 'pilih bulan...']) ?>
    </div>

    <div class="form-group">
        <?php 
            echo FileInput::widget([
                'id' => 'package-file',
                'name' => 'package-file',
                'options' => [
                    'required' => true,
                    'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ],
                'pluginOptions' => [
                    'showPreview' => false,
                    'showUpload' => false,
                ],
            ]);
        ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-upload"></i> ' . ('Import'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
