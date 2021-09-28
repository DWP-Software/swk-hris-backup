<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\models\entity\User;
use common\models\entity\Village;
use common\models\entity\Subdistrict;
use common\models\entity\District;
use common\models\entity\Province;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

<div class="row">
<div class="col-md-12 col-sm-12">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'registration_number')->textInput(['maxlength' => true]) ?>
    
    <div class="modal-footer text-right">
    <?= 
        Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
        .' '.Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Tambah' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
    ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>

</div>
