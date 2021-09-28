<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";

if (($tableSchema = $generator->getTableSchema()) !== false) {
    $flag_select2       = false;
    $flag_datepicker    = false;
    $imported_models    = [];
    foreach ($tableSchema->columns as $column) {
        if (substr($column->name, -3) == '_id') {
            $flag_select2 = true;
            $imported_models[] = str_replace(StringHelper::basename($generator->modelClass), Inflector::camelize(preg_replace("/\_id$/", "", $column->name)), $generator->modelClass);
        }
        if ($column->type == 'date') {
            $flag_datepicker = true;
        }
    }
}
?>

use yii\helpers\Url;
use yii\helpers\Html;
<?php if ($flag_select2) echo "use yii\\helpers\\ArrayHelper;\n"; ?>
<?= $generator->enablePjax ? "use yii\widgets\Pjax;\n" : "" ?>
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use kartik\export\ExportMenu;
<?php if ($flag_select2) echo "use kartik\\widgets\\Select2;\n"; ?>
<?php if ($flag_datepicker) echo "use kartik\\widgets\\DatePicker;\n"; ?>
<?php 
    foreach ($imported_models as $imported_model) {
        echo "use " . $imported_model . ";\n";
    }
?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

<?php if(!empty($generator->searchModelClass)): ?>
<?php if($generator->indexWidgetType === 'list'): ?>
    <?="<?= "?> $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>
<?php endif; ?><?= $generator->enablePjax ? '    <?php Pjax::begin(); ?>' : '' ?>
    <?= "<?php \n"; ?>
        $exportColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
    <?php
    if (($tableSchema = $generator->getTableSchema()) === false) {
        foreach ($generator->getColumnNames() as $name) {
            echo "                '" . $name . "',\n";
        }
    } else {
        foreach ($tableSchema->columns as $column) {
            $format = $generator->generateColumnFormat($column);
            if (substr($column->name, -3) == '_at') {
                echo "            '" . $column->name . ":datetime',\n";
            } elseif (substr($column->name, -3) == '_by') {
                echo "            '" . lcfirst(Inflector::camelize($column->name)) . ".username:text:".ucwords(Inflector::humanize($column->name))."',\n";
            } else {
                if ($column->name == 'id') {
                    echo "        '" . $column->name . "',\n";
                } elseif (substr($column->name, -3) == '_id') {
                    echo "            '".lcfirst(Inflector::camelize(substr($column->name, 0, -3))) . ".name:text:".Inflector::humanize(substr($column->name, 0, -3))."',\n";
                } elseif ($column->type == 'date') {
                    echo "            '" . $column->name . ":date',\n";
                } elseif ($column->type == 'integer') {
                    echo "            '" . $column->name . "',\n";
                } elseif ($column->type == 'double') {
                    echo "            '" . $column->name . "',\n";
                } elseif ($format == 'ntext') {
                    echo "            '" . $column->name . "',\n";
                } else {
                    echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                }
            }
        }
    }
    ?>
        ];

        $exportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $exportColumns,
            'filename' => '<?= (Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>',
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
                            'data-confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>]);
                    },
                ],
            ],
<?php
    $count = 0;
    if (($tableSchema = $generator->getTableSchema()) === false) {
        foreach ($generator->getColumnNames() as $name) {
            if (++$count < 6) {
                echo "                '" . $name . "',\n";
            } else {
                echo "                // '" . $name . "',\n";
            }
        }
    } else {
        foreach ($tableSchema->columns as $column) {
            $format = $generator->generateColumnFormat($column);
            if ($column->name != 'created_at' && $column->name != 'updated_at' && $column->name != 'created_by' && $column->name != 'updated_by') {
                if ($column->name == 'id') {
                    echo "            // '" . $column->name . "',\n";
                } elseif (substr($column->name, -3) == '_id') {
                    echo "            [
                'attribute' => '" . $column->name . "',
                'value' => '" . lcfirst(Inflector::camelize(substr($column->name, 0, -3))) . ".name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(" . Inflector::camelize(substr($column->name, 0, -3)) . "::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
                'filterInputOptions'=>['placeholder'=>''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear'=>true],
                ],
            ],\n";
                } elseif ($column->type == 'date') {
                    echo "            [
                'attribute' => '" . $column->name . "',
                'format' => 'date',
                'filterType' => GridView::FILTER_DATE,
                'filterInputOptions' => ['placeholder' => ''],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
                ],
            ],\n";
                } elseif ($column->type == 'integer') {
                    echo "            [
                'attribute' => '" . $column->name . "',
                'format' => 'integer',
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => ['class' => 'text-right'],
            ],\n";
                } elseif ($column->type == 'double') {
                    echo "            [
                'attribute' => '" . $column->name . "',
                'format' => ['decimal', 2],
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => ['class' => 'text-right'],
            ],\n";
                } elseif ($format == 'ntext') {
                    echo "            [
                'attribute' => '" . $column->name . "',
                'format' => 'ntext',
                'contentOptions' => ['class' => 'text-wrap'],
            ],\n";
                } else {
                    echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                }
            } else {
                echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            }
        }
    }
    ?>
        ];
<?= "    ?>"; ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>

    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'responsiveWrap' => false,
        'pjax' => true,
        'hover' => true,
        'striped' => false,
        'bordered' => false,
        'toolbar'=> [
            Html::a('<i class="fa fa-plus"></i> ' . <?= $generator->generateString('Create') ?>, ['create'], ['class' => 'btn btn-success']),
            Html::a('<i class="fa fa-repeat"></i> ' . <?= $generator->generateString('Reload') ?>, ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default']),
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
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n" : ""; ?>
        'columns' => $gridColumns,
    ]); ?>
<?php else: ?>
        <?= "<?= " ?>ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => function ($model, $key, $index, $widget) {
                return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
            },
        ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>

</div>
