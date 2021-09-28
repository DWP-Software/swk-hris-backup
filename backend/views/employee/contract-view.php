<?php

use common\models\entity\ContractSalary;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\widgets\Select2;
use common\models\entity\Contract;

/* @var $this yii\web\View */
/* @var $model common\models\entity\PlacementContract */

$this->title = 'Contract : ' . $model->employee->name . ' - ' . $model->contract_number;
$this->params['breadcrumbs'][] = ['label' => 'Employee', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->employee->name, 'url' => ['view', 'id' => $model->employee->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
<?= Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Update', ['contract-update', 'contract_id' => $model->id], [
	'class' => 'btn btn-warning',
]) ?> 
<?= Html::a('<i class="glyphicon glyphicon-trash"></i> ' . 'Delete', ['contract-delete', 'contract_id' => $model->id], [
	'class' => 'btn btn-danger',
	'data' => [
		'confirm' => 'Are you sure you want to delete this item?',
		'method' => 'post',
	],
]) ?> 
<?= Html::a('<i class="fa fa-file"></i> '. 'Print Resume', ['print-resume', 'id' => $model->id], [
	'class' => 'btn btn-default',
]) ?>

<?= Html::a('<i class="glyphicon glyphicon-file"></i> '. 'Draft Kontrak', ['print-contract', 'contract_id' => $model->id], [
	'class' => 'btn btn-default',
]) ?> 
<?= Html::a('<i class="glyphicon glyphicon-file"></i> '. 'Draft Surat Pernyataan', ['print-statement', 'id' => $model->employee_id], [
	'class' => 'btn btn-default',
]) ?>
</p>

<div class="row">
	<div class="col-md-8">
		<div class="detail-view-container">
		<?= DetailView::widget([
			'options' => ['class' => 'table detail-view'],
			'model' => $model,
			'attributes' => [
				// 'id',
				'contract_number',
				[
					'label'  => 'Karyawan',
					'format' => 'html',
					'value'  => '<b>' . $model->employee->name . '</b>'
						.'<br>NRK: ' . $model->employee->registration_number
						.'<br>KTP: ' . $model->employee->identity_number
						.'<br>TTL: ' . $model->employee->place_of_birth . ', ' . Yii::$app->formatter->asDate($model->employee->date_of_birth)
					,
				],
				'employeeType.name:text:Jenis Kontrak',
				'started_at:date',
				'ended_at:date',
				'duration:text:Durasi',
				[
					'attribute' => 'pasal_3_2',
					'value' => $model->pasal_3_2 ? 'Ya' : 'Tidak',
				],
				[
					'attribute' => 'pasal_3_3',
					'value' => $model->pasal_3_3,
				],
				'payment_date',
				'signer_name',
				'signer_position',
				'signer_address:ntext',
				[
					'attribute' => 'file',
					'format' => 'html',
					'value' => !$model->file ? null : Html::a('<i class="glyphicon glyphicon-file"></i> '. 'Download', ['contract-download', 'contract_id' => $model->id], [
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

		<div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                KOMPONEN GAJI
            </h4>
            <?php 
				$array = [];
				foreach (ContractSalary::types() as $type_key => $type_value) {
					$array[$type_value] = [];
					echo '<div class="detail-view-container">';
					echo '<h5 style="margin-left:5px"><b>'.strtoupper($type_value).'</b></h5>';
					foreach (ContractSalary::permanentTypes() as $permanentType_key => $permanentType_value) {
						if ($type_key == $permanentType_key) {
							foreach ($permanentType_value as $subType) {
								$contractSalary = ContractSalary::findOne(['contract_id' => $model->id, 'name' => $subType]);
								$array[$type_value][$subType ] = $contractSalary ? $contractSalary->amount : null;
								echo '<div style="padding:5px; border-bottom:1px solid #f4f4f4">'. $subType . '<span class="pull-right">'.Yii::$app->formatter->asInteger($contractSalary ? $contractSalary->amount : 0) . '</span></div>';
							}
						}
					}
					echo '</div>';
				}
				//d($array);
			?>
        </div>
	</div>


	<div class="col-md-4">
		<div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                PENEMPATAN
                <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', [
					'value' => Url::to(['contract-placement-form', 'id' => $model->id]), 
					'title' => 'Tempatkan', 
					'class' => 'showModalButton btn btn-xs btn-success pull-right'
				]) ?>
            </h4>
            <?= !$model->contractPlacements ? '<div class="detail-view-container text-muted small bg-gray-light box-body" style="margin-bottom:10px"><i>Tidak ada data.</i></div>' : '' ?>
            <?php foreach ($model->contractPlacements as $contractPlacement) { ?>
                <div class="detail-view-container box-body" style="margin-bottom:10px">
				<div class="pull-right">
                            <?= Html::a('<i class="fa fa-trash"></i>', ['contract-placement-delete', 'contract_placement_id' => $contractPlacement->id], [
                                'class' => 'btn btn-xs btn-default btn-text-danger', 
                                'data-method' => 'post', 
                                'data-confirm' => 'Hapus?',
                            ]) ?> 
                            <?= Html::button('<i class="fa fa-pencil"></i>', [
                                'value' => Url::to(['contract-placement-form', 'id' => $model->id, 'contract_placement_id' => $contractPlacement->id]), 
                                'title' => 'Ubah Penempatan', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning'
                            ], 
                            ['class' => 'btn btn-xs btn-default']) ?>
                        </div>
                    <div class="panel-title">
                        <b><?= $contractPlacement->client->name ?></b>
                    </div>
                    <span class="text-muted"><?= Yii::$app->formatter->asDate($contractPlacement->started_at) ?> - <?= Yii::$app->formatter->asDate($contractPlacement->ended_at) ?> : </span> <?= $contractPlacement->position ?>
                </div>
            <?php } ?>
		</div>

	</div>
</div>