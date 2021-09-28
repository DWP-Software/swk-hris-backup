<?php

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */

$this->title = 'New Contract : ' . $employee->name;
$this->params['breadcrumbs'][] = ['label' => 'Employee', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $employee->name, 'url' => ['view', 'id' => $employee->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form-contract', [
	'model' => $model,
	'modelPlacement' => $modelPlacement,
	'employee' => $employee,
]) ?>
