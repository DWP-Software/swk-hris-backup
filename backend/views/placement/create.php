<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\entity\Placement */

$this->title = 'Create Placement';
$this->params['breadcrumbs'][] = ['label' => 'Placement', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-create box-- box-success--">
	<!-- <div class="box-header"></div> -->

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
    
</div>
