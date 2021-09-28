<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\EmployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'identity_number') ?>

    <?= $form->field($model, 'registration_number') ?>

    <?= $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'date_of_birth') ?>

    <?php // echo $form->field($model, 'place_of_birth') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'religion') ?>

    <?php // echo $form->field($model, 'address_street') ?>

    <?php // echo $form->field($model, 'address_neighborhood') ?>

    <?php // echo $form->field($model, 'address_village_id') ?>

    <?php // echo $form->field($model, 'address_subdistrict_id') ?>

    <?php // echo $form->field($model, 'address_district_id') ?>

    <?php // echo $form->field($model, 'address_province_id') ?>

    <?php // echo $form->field($model, 'domicile_street') ?>

    <?php // echo $form->field($model, 'domicile_neighborhood') ?>

    <?php // echo $form->field($model, 'domicile_village_id') ?>

    <?php // echo $form->field($model, 'domicile_subdistrict_id') ?>

    <?php // echo $form->field($model, 'domicile_district_id') ?>

    <?php // echo $form->field($model, 'domicile_province_id') ?>

    <?php // echo $form->field($model, 'education_level') ?>

    <?php // echo $form->field($model, 'family_number') ?>

    <?php // echo $form->field($model, 'mother_name') ?>

    <?php // echo $form->field($model, 'nationality') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'marital_status') ?>

    <?php // echo $form->field($model, 'blood_type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
