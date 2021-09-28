<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\models\entity\Employee;
use common\models\entity\Client;

/* @var $this yii\web\View */
/* @var $searchModel client\models\search\PlacementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Placement';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="placement-index box-- box-primary-- box-body--">

    <?php Pjax::begin(); ?>    <?php 
        $exportColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            'id',
            'employee.name:text:Employee',
            'client.name:text:Client',
            'plan_employee_type',
            'plan_started_at:datetime',
            'plan_ended_at:datetime',
            'remark',
            'submitted_at:datetime',
            'responded_at:datetime',
            'response_type',
            'created_at:datetime',
            'updated_at:datetime',
            'createdBy.username:text:Created By',
            'updatedBy.username:text:Updated By',
        ];

        $exportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $exportColumns,
            'filename' => 'Placement',
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

        $gridColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'text-right serial-column'],
                'contentOptions' => ['class' => 'text-right serial-column'],
            ],
            [
                'contentOptions' => ['class' => 'action-column nowrap text-left'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
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
                'format'    => 'raw',
                'value'     => function($model) {
                    return '<b>' . $model->employee->name . '</b>' 
                    . '<br>KTP : ' . $model->employee->identity_number
                    . '<br>NRP : ' . $model->employee->registration_number;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Employee::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            /* [
                'attribute' => 'client_id',
                'value' => 'client.name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Client::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ], */
            /* [
                'attribute' => 'plan_employee_type',
                'value' => function($model) {
                    return $model->planEmployeeType->name;
                },
            ],
            [
                'attribute' => 'plan_started_at',
                'format' => 'date',
                'filterType' => GridView::FILTER_DATE,
                'filterInputOptions' => ['placeholder' => ''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
                ],
            ],
            [
                'attribute' => 'plan_ended_at',
                'format' => 'date',
                'filterType' => GridView::FILTER_DATE,
                'filterInputOptions' => ['placeholder' => ''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
                ],
            ], */
            [
                'attribute' => 'submitted_at',
                'format' => 'datetime',
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'responded_at',
                'format' => 'datetime',
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'response_type',
                'value' => function($model) {
                    return $model->responseTypeText;
                },
            ],[
                'attribute' => 'employee.placeAndDateOfBirth',
                'label'     => 'TTL',
                'format'    => 'raw',
                'value'     => function($model) {
                    return $model->employee->place_of_birth 
                    . '<br>' . Yii::$app->formatter->asDate($model->employee->date_of_birth)
                    . '<br>' . $model->employee->sexes($model->employee->sex);
                },
            ],
            // [
            //     'attribute' => 'religion',
            //     'value'     => function($model) {
            //         return $model->religions($model->religion);
            //     },
            // ],
            [
                'attribute' => 'employee.addressText',
                'contentOptions' => ['class' => 'text-wrap'],
            ],
            [
                'attribute' => 'employee.domicileText',
                'contentOptions' => ['class' => 'text-wrap'],
            ],
            [
                'attribute' => 'remark',
                'format' => 'ntext',
                'contentOptions' => ['class' => 'text-wrap'],
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
<?php Pjax::end(); ?>
</div>
