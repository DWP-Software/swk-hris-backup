<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Placement */

$this->title = 'Update Placement: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Placement', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="placement-update box-- box-warning--">

    <!-- <div class="box-header"></div> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
