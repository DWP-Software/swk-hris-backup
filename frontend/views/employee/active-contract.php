<?php

use common\models\entity\Employee;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\entity\PlacementContractSalary;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */

$this->title = 'Contract : ' . $model->contract->employee->name . ' - ' . $model->contract->contractPlacements[0]->client->name;
// $this->params['breadcrumbs'][] = ['label' => 'Employee', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="employee-view">

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> '. 'Kembali', ['view'], [
            'class' => 'btn btn-default',
        ]); ?>
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
                'value'  => '<b>' . $model->contract->employee->name . '</b>'
                    .'<br>NRK: ' . $model->contract->employee->registration_number
                    .'<br>KTP: ' . $model->contract->employee->identity_number
                    .'<br>TTL: ' . $model->contract->employee->place_of_birth . ', ' . Yii::$app->formatter->asDate($model->contract->employee->date_of_birth)
                ,
            ],
            [
                'label'  => 'Penempatan',
                'format' => 'html',
                'value'  => '<b>' . $model->contract->contractPlacements[0]->client->name . '</b>'
                    // .'<br>' . Yii::$app->formatter->asDate($model->submitted_at) . ' - Diajukan'
                    // .'<br>' . Yii::$app->formatter->asDate($model->responded_at) . ' - Disetujui'
                ,
            ],
            'location',
            'contract.employeeType.name:text:Jenis Kontrak',
            'contract_started_at',
            'contract_ended_at',
            [
                'attribute' => 'Gaji Pokok',
                'format' => 'html',
                'value' => 'Rp '. Yii::$app->formatter->asDecimal($model->contract->baseSalary, 0),
            ],
            [
                'attribute' => 'file',
                'format' => 'html',
                'value' => !$model->file ? null : Html::a('<i class="glyphicon glyphicon-file"></i> '. 'Download', ['contract-download', 'contract_id' => $model->contract_id], [
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
</div>
