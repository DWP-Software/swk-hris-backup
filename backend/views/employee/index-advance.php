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
use common\models\entity\Placement;
use common\models\entity\Province;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employee';
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
            'updated_at:datetime',
            'createdBy.username:text:Created By',
            'updatedBy.username:text:Updated By',
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
                'template' => '{view} {placement-create}',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('', $url, ['title' => 'view', 'class' => 'glyphicon glyphicon-eye-open btn btn-xs btn-default btn-text-info']);
                    },
                    'placement-create' => function ($url, $model) {
                        $pending_placement = Placement::findOne(['employee_id' => $model->id, 'responded_at' => null]);
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
            'name',
            [
                'attribute' => 'placementClientName',
                'label'     => 'Mitra Penempatan',
                'format'    => 'html',
                'value'     => function($model) {
                    $return = null;
                    $latestPlacement = Placement::find()->where(['employee_id' => $model->id])->orderBy('id DESC')->one();
                    if ($latestPlacement !== null) {
                        $return = $latestPlacement->client->name;
                    }
                    return $return;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Client::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'placementProcessStatus',
                'label'     => 'Status Penempatan',
                'format'    => 'html',
                'value'     => function($model) {
                    $return = 'Belum ditempatkan';
                    $latestPlacement = Placement::find()->where(['employee_id' => $model->id])->orderBy('id DESC')->one();
                    if ($latestPlacement !== null) {
                        $return = $latestPlacement->processStatus;
                    }
                    return $return;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => [
                    '1' => 'Diterima',
                    '2' => 'Ditolak',
                    '3' => 'Menunggu',
                    '4' => 'Belum ditempatkan',
                ], 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'user_id',
                'value' => 'user.email',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(User::find()->orderBy('email')->asArray()->all(), 'id', 'email'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            'identity_number',
            'registration_number',
            'phone',
            [
                'attribute' => 'date_of_birth',
                'format' => 'date',
                'filterType' => GridView::FILTER_DATE,
                'filterInputOptions' => ['placeholder' => ''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
                ],
            ],
            'place_of_birth',            
            [
                'attribute' => 'sex',
                'value' => function($model) {
                    return $model->sexes($model->sex);
                },
            ],
            [
                'attribute' => 'religion',
                'value' => function($model) {
                    return $model->religions($model->religion);
                },
            ],
            'address_street',
            'address_neighborhood',
            'addressText:text:Alamat (KTP)',
            'domicileText:text:Alamat (domisili)',
            [
                'attribute' => 'address_village_id',
                'value' => 'addressVillage.name',
                'filterType' => GridView::FILTER_SELECT2,
                // 'filter' => ArrayHelper::map(AddressVillage::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'address_subdistrict_id',
                'value' => 'addressSubdistrict.name',
                'filterType' => GridView::FILTER_SELECT2,
                // 'filter' => ArrayHelper::map(AddressSubdistrict::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'address_district_id',
                'value' => 'addressDistrict.name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(District::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'address_province_id',
                'value' => 'addressProvince.name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Province::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            'domicile_street',
            'domicile_neighborhood',
            [
                'attribute' => 'domicile_village_id',
                'value' => 'domicileVillage.name',
                'filterType' => GridView::FILTER_SELECT2,
                // 'filter' => ArrayHelper::map(DomicileVillage::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'domicile_subdistrict_id',
                'value' => 'domicileSubdistrict.name',
                'filterType' => GridView::FILTER_SELECT2,
                // 'filter' => ArrayHelper::map(DomicileSubdistrict::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'domicile_district_id',
                'value' => 'domicileDistrict.name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(District::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],
            [
                'attribute' => 'domicile_province_id',
                'value' => 'domicileProvince.name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Province::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],            
            [
                'attribute' => 'education_level',
                'value' => function($model) {
                    return $model->educationLevels($model->education_level);
                },
            ],
            'family_number',
            'mother_name',
            [
                'attribute' => 'nationality',
                'value' => function($model) {
                    return $model->nationalities($model->nationality);
                },
            ],
            [
                'attribute' => 'height',
                'format' => ['decimal', 2],
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'weight',
                'format' => ['decimal', 2],
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'marital_status',
                'value' => function($model) {
                    return $model->maritalStatuses($model->marital_status);
                },
            ],
            [
                'attribute' => 'blood_type',
                'value' => function($model) {
                    return $model->bloodTypes($model->blood_type);
                },
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
            $exportMenu,
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
