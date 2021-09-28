<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\entity\Payroll */

$this->title = 'Create Pembayaran Gaji';
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Gaji', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-create box-- box-success--">
	<!-- <div class="box-header"></div> -->

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
    
</div>
