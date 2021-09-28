<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\entity\PlacementContract */

$this->title = 'Create Placement Contract';
$this->params['breadcrumbs'][] = ['label' => 'Placement Contract', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-contract-create box-- box-success--">
	<!-- <div class="box-header"></div> -->

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
    
</div>
