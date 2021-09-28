<?php

use common\models\entity\PlacementContractSalary;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\PlacementContract */

$this->title = 'Contract : ' . $model->placement->employee->name . ' - ' . $model->placement->client->name;
$this->params['breadcrumbs'][] = ['label' => 'Employee', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->placement->employee->name, 'url' => ['view', 'id' => $model->placement->employee->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
<?= Html::a('<i class="fa fa-file"></i> '. 'Print Resume', ['print-resume', 'id' => $model->id], [
	'class' => 'btn btn-default',
]) ?>
</p>

<div class="detail-view-container">
<?= DetailView::widget([
	'options' => ['class' => 'table detail-view'],
	'model' => $model,
	'attributes' => [
		// 'id',
		[
			'label'  => 'Karyawan',
			'format' => 'html',
			'value'  => '<b>' . $model->placement->employee->name . '</b>'
				.'<br>NRK: ' . $model->placement->employee->registration_number
				.'<br>KTP: ' . $model->placement->employee->identity_number
				.'<br>TTL: ' . $model->placement->employee->place_of_birth . ', ' . Yii::$app->formatter->asDate($model->placement->employee->date_of_birth)
			,
		],
		[
			'label'  => 'Penempatan',
			'format' => 'html',
			'value'  => '<b>' . $model->placement->client->name . '</b>'
				.'<br>' . Yii::$app->formatter->asDate($model->placement->submitted_at) . ' - Diajukan'
				.'<br>' . Yii::$app->formatter->asDate($model->placement->responded_at) . ' - Disetujui'
			,
		],
		'location',
		'employeeType.name:text:Jenis Kontrak',
		'started_at',
		'ended_at',
		[
			'attribute' => 'file',
			'format' => 'html',
			'value' => !$model->file ? null : Html::a('<i class="glyphicon glyphicon-file"></i> '. 'Download', ['contract-download', 'contract_id' => $model->placement_contract_id], [
				'class' => 'btn btn-default btn-xs',
			]),
		],
		// 'created_at:datetime',
		// 'updated_at:datetime',
		// 'createdBy.username:text:Created By',
		// 'updatedBy.username:text:Updated By',
	],
]) ?>
</div>
