<?php

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */

$this->title = 'Update Contract : ' . $model->employee->name . ' - ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Employee', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->employee->name, 'url' => ['view', 'id' => $model->employee->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form-contract-update', [
	'model' => $model,
]) ?>
