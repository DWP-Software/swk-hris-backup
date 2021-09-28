<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\entity\PicClient */

$this->title = 'Update Pic Client: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pic Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pic-client-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
