<?php

use api\models\Employee;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\widgets\Select2;
use common\models\entity\Contract;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PayrollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pembayaran Gaji';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payroll-index box-- box-primary-- box-body--">

    <?php 
        $exportColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            'id',
            'contract.id:text:Placement contract',
            'year',
            'month',
            'created_at:datetime',
            'updated_at:datetime',
            'createdBy.username:text:Created By',
            'updatedBy.username:text:Updated By',
        ];

        $exportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $exportColumns,
            'filename' => 'Payroll',
            'fontAwesome' => true,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-default'
            ],
            'target' => ExportMenu::TARGET_SELF,
            'exportConfig' => [
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_HTML => false,
            ],
            'styleOptions' => [
                ExportMenu::FORMAT_EXCEL_X => [
                    'font' => [
                        'color' => ['argb' => '00000000'],
                    ],
                    'fill' => [
                        // 'type' => PHPExcel_Style_Fill::FILL_NONE,
                        'color' => ['argb' => 'DDDDDDDD'],
                    ],
                ],
            ],
            'pjaxContainerId' => 'grid',
        ]);
        
        if (Yii::$app->user->can('Administrator')) {
            $data = ArrayHelper::map(Employee::find()->joinWith(['latestContractPlacement.contractPlacement.client'])->all(), 'id', 'shortTextLatestContract');
        } else {
            $data = ArrayHelper::map(Employee::find()->joinWith(['latestContractPlacement.contractPlacement.client.picClients'])->where(['pic_client.user_id' => Yii::$app->user->id, 'role' => 'Keuangan'])->all(), 'id', 'shortTextLatestContract');
        }

        $gridColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'text-right serial-column'],
                'contentOptions' => ['class' => 'text-right serial-column'],
            ],
            [
                'contentOptions' => ['class' => 'action-column nowrap text-left'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {print}',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-print btn btn-xs btn-default']);
                    },
                    'view' => function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-xs btn-default btn-text-info']);
                    },
                    'update' => function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-xs btn-default btn-text-warning']);
                    },
                    'delete' => function ($url) {
                        return Html::a('', $url, [
                            'class' => 'glyphicon glyphicon-trash btn btn-xs btn-default btn-text-danger', 
                            'data-method' => 'post', 
                            'data-confirm' => 'Are you sure you want to delete this item?']);
                    },
                ],
            ],
            // 'id',
            [
                'attribute' => 'employee_id',
                'value' => function($model) {
                    return $model->contract->employee->name .' <small class="text-muted"><br>NRK : '. $model->contract->employee->registration_number .'<br>Kontrak : '. $model->contract->id .' ('.$model->contract->currentPlacement->client->name.')</small>';
                },
                'label' => 'Karyawan',
                'format' => 'raw',
                'filterType' => GridView::FILTER_SELECT2,
                // 'filter' => ArrayHelper::map(Contract::find()->orderBy('id')->all(), 'id', 'shortText'), 
                'filter' => $data, 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'year',
                // 'format' => 'integer',
                'headerOptions' => ['class' => 'text-right action-column'],
                'contentOptions' => ['class' => 'text-right action-column'],
            ],
            [
                'attribute' => 'month',
                // 'format' => 'integer',
                'headerOptions' => ['class' => 'text-right action-column'],
                'contentOptions' => ['class' => 'text-right action-column'],
                'filter' => months(),
                'value' => function($model) {
                    return monthName($model->month);
                }
            ],
            // 'created_at:integer',
            // 'updated_at:integer',
            // 'created_by:integer',
            // 'updated_by:integer',
        ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'pjax' => true,
        'hover' => true,
        'striped' => false,
        'bordered' => false,
        'toolbar'=> [
            // Html::a('<i class="fa fa-plus"></i> ' . 'Create', ['create'], ['class' => 'btn btn-success']),
            Html::a('<i class="fa fa-plus"></i> ' . 'Generate', ['generate'], ['class' => 'btn btn-success']),
            Html::a('<i class="fa fa-repeat"></i> ' . 'Reload', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default']),
            '{toggleData}',
            // $exportMenu,
        ],
        'panel' => [
            'type' => 'no-border transparent',
            'heading' => false,
            'before' => '{summary}',
            'after' => false,
        ],
        'panelBeforeTemplate' => '
            <div class="row">
                <div class="col-sm-8">
                    <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                        {toolbar}
                    </div> 
                </div>
                <div class="col-sm-4">
                    <div class="pull-right">
                        {before}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        ',
        'pjaxSettings' => ['options' => ['id' => 'grid']],
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
    ]); ?>

</div>
