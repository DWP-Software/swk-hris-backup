<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Payroll */

$this->title = 'Update Pembayaran Gaji: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Gaji', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payroll-update box-- box-warning--">

    <!-- <div class="box-header"></div> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
