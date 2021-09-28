<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\entity\Client */

$this->title = 'Tambah Mitra';
$this->params['breadcrumbs'][] = ['label' => 'Mitra', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create box-- box-success--">
	<!-- <div class="box-header"></div> -->

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
    
</div>
