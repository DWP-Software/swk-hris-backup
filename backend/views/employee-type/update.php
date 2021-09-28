<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\entity\EmployeeType */

$this->title = 'Update Employee Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Employee Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="employee-type-update box-- box-warning--">

    <!-- <div class="box-header"></div> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
