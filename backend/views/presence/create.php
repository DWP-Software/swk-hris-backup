<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\entity\Presence */

$this->title = 'Tambah Kehadiran';
$this->params['breadcrumbs'][] = ['label' => 'Kehadiran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presence-create box-- box-success--">
	<!-- <div class="box-header"></div> -->

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
    
</div>
