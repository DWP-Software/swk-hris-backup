<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mitra <small>'.$subtitle.'</small>';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="client-index box-- box-primary-- box-body--">

    <?php Pjax::begin(); ?>    <?php 
        $exportColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            'id',
            'name',
            'address',
            'phone',
            [
                'header' => 'Nomor MoU',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->latestAgreement ? $model->latestAgreement->document_number : null;
                }
            ],
            [
                'header' => 'Mulai Mou',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->latestAgreement ? Yii::$app->formatter->asDate($model->latestAgreement->started_at) : null;
                }
            ],
            [
                'header' => 'Selesai Mou',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->latestAgreement ? Yii::$app->formatter->asDate($model->latestAgreement->ended_at) : null;
                }
            ],
        ];

        $exportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $exportColumns,
            'filename' => 'Client',
            'fontAwesome' => true,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-default'
            ],
            'target' => ExportMenu::TARGET_SELF,
            'exportConfig' => [
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_TEXT => false,
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
            'name',
            [
                'attribute' => 'address',
                'format' => 'ntext',
                'contentOptions' => ['class' => 'text-wrap'],
            ],
            'phone',
            [
                'header' => 'MoU',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->latestAgreement ? $model->latestAgreement->document_number.'<br><small>'.Yii::$app->formatter->asDate($model->latestAgreement->started_at) .' &nbsp; <i class="fa fa-arrow-right text-muted"></i> &nbsp; '. Yii::$app->formatter->asDate($model->latestAgreement->ended_at).'</small>' : null;
                }
            ],
            [
                'attribute' => 'file_logo',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->file_logo ? Html::img(['download', 'id' => $model->id], ['height' => '20px']) : '';
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
            Html::a('<i class="fa fa-plus"></i> ' . 'Create', ['create'], ['class' => 'btn btn-success']),
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
