<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\entity\PlacementContract */

$this->title = 'Update Placement Contract: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Placement Contract', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="placement-contract-update box-- box-warning--">

    <!-- <div class="box-header"></div> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
