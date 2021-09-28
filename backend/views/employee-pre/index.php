<?php

use common\models\entity\Client;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\models\entity\User;
use common\models\entity\Village;
use common\models\entity\Subdistrict;
use common\models\entity\District;
use common\models\entity\Employee;
use common\models\entity\LatestPlacementPlan;
use common\models\entity\Placement;
use common\models\entity\PlacementPlan;
use common\models\entity\Province;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calon Karyawan <small>[' . $subtitle . ']</small>';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="employee-index box-- box-primary-- box-body--">

    <?php Pjax::begin(); ?>    <?php 
        $exportColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            'id',
            'user.email:text:User',
            'identity_number',
            'registration_number',
            'phone',
            'name',
            'date_of_birth:date',
            'place_of_birth',
            'sex',
            'religion',
            'address_street',
            'address_neighborhood',
            'addressVillage.name:text:Address village',
            'addressSubdistrict.name:text:Address subdistrict',
            'addressDistrict.name:text:Address district',
            'addressProvince.name:text:Address province',
            'domicile_street',
            'domicile_neighborhood',
            'domicileVillage.name:text:Domicile village',
            'domicileSubdistrict.name:text:Domicile subdistrict',
            'domicileDistrict.name:text:Domicile district',
            'domicileProvince.name:text:Domicile province',
            'education_level',
            'family_number',
            'mother_name',
            'nationality',
            'height',
            'weight',
            'marital_status',
            'blood_type',
            'created_at:datetime',
            // 'updated_at:datetime',
            // 'createdBy.username:text:Created By',
            // 'updatedBy.username:text:Updated By',
        ];

        $exportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $exportColumns,
            'filename' => 'Employee',
            'fontAwesome' => true,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-default'
            ],
            'target' => ExportMenu::TARGET_SELF,
            'exportConfig' => [
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_TEXT => false,
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
                        return Html::a('', $url, ['title' => 'view', 'class' => 'glyphicon glyphicon-eye-open btn btn-xs btn-default btn-text-info']);
                    },
                    'placement-create' => function ($url, $model) {
                        $pending_placement = PlacementPlan::findOne(['employee_id' => $model->id, 'responded_at' => null]);
                        if ($pending_placement === null)
                        return Html::a('', $url, [
                            'title' => 'assign', 
                            'class' => 'glyphicon glyphicon-arrow-right btn btn-xs btn-default btn-text-success',
                        ]);
                        else return null;
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
                'attribute' => 'name',
                'label'     => 'Nama',
                'format'    => 'raw',
                'value'     => function($model) {
                    return '<b>' . $model->name . '</b>' 
                    . '<br>KTP : ' . $model->identity_number;
                },
            ],    
            [
                'attribute'           => 'placementClientName',
                'label'               => 'Rencana Penempatan',
                'format'              => 'html',
                'value'               => function($model) {
                    return ($model->latestPlacementPlan ? $model->latestPlacementPlan->placementPlan->client->name : '') .'<br><span class="text-muted">' . ($model->latestPlacementPlan ? $model->latestPlacementPlan->placementPlan->responseTypeText : '') . '</span>';
                },
                'filterType'          => GridView::FILTER_SELECT2,
                'filter'              => ArrayHelper::map(Client::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                'filterInputOptions'  => ['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'placeAndDateOfBirth',
                'label'     => 'TTL',
                'format'    => 'raw',
                'value'     => function($model) {
                    return $model->place_of_birth 
                    . '<br>' . Yii::$app->formatter->asDate($model->date_of_birth)
                    . '<br>' . $model->sexes($model->sex);
                },
            ],
            // [
            //     'attribute' => 'religion',
            //     'value'     => function($model) {
            //         return $model->religions($model->religion);
            //     },
            // ],
            [
                'attribute' => 'addressText',
                'contentOptions' => ['class' => 'text-wrap'],
            ],
            [
                'attribute' => 'domicileText',
                'contentOptions' => ['class' => 'text-wrap'],
            ],
            [
                'attribute' => 'user_id',
                'label'     => 'User Account',
                'format'    => 'raw',
                'value'     => function($model) {
                    return Html::a($model->user->email, ['/user/view', 'id' => $model->user->id])
                    . '<br> Telp: ' . $model->phone;
                },
                'filterType'          => GridView::FILTER_SELECT2,
                'filter'              => ArrayHelper::map(User::find()->orderBy('email')->asArray()->all(), 'id', 'email'),
                'filterInputOptions'  => ['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
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
            Html::a('<i class="fa fa-repeat"></i> ' . 'Reload', ['index-pre'], ['data-pjax'=>0, 'class'=>'btn btn-default']),
            '{toggleData}',
            Html::a('<i class="fa fa-file-excel-o"></i> &nbsp;' . 'Export', ['/employee/export', 'contract_status' => Employee::CONTRACT_NULL], ['data-pjax'=>0, 'class'=>'btn btn-default']),
            Html::a('<i class="fa fa-trash"></i> &nbsp;' . 'Delete All', ['/employee-pre/delete-all'], [
                'data-pjax'=>0, 
                'class'=>'btn btn-default btn-text-danger',
                'data-method' => 'post',
                'data-confirm' => '<b class="text-danger"><big>Hapus semua data calon karyawan?</big></b><br><br>Lakukan hanya jika anda yakin semua data calon karyawan memang tidak diperlukan. <br>Tindakan ini tidak dapat dibatalkan.'
            ]),
            // $exportMenu,
            // '&nbsp;<div style="width:200px;display:inline-block">' . Select2::widget([
            //     'model' => $searchModel,
            //     'attribute' => 'placementProcessStatus',
            //     'data' => array_merge(['-1' => 'Belum ditempatkan'], Placement::responseTypes()),
            //     'pluginOptions' => ['allowClear' => true, 'placeholder' => 'semua tahap'],
            // ]) . '</div>',
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



<?php 
    $this->registerJs('
        $("#employeesearchpre-placementprocessstatus").change(function() {
            placementProcessStatus = $(this).val();
            window.location = "'. yii\helpers\Url::base().'/employee/index-pre?EmployeeSearchPre[placementProcessStatus]="+placementProcessStatus;
        });
    ', \yii\web\View::POS_READY);
?>