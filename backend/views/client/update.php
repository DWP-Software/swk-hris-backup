<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Client */

$this->title = 'Edit Mitra: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mitra', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="client-update box-- box-warning--">

    <!-- <div class="box-header"></div> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
