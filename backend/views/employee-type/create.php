<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\entity\EmployeeType */

$this->title = 'Create Employee Type';
$this->params['breadcrumbs'][] = ['label' => 'Employee Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-type-create box-- box-success--">
	<!-- <div class="box-header"></div> -->

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
    
</div>
