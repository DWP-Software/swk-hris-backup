<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString((Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <p>
        <?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-pencil"></i> '. <?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], [
            'class' => 'btn btn-warning',
        ]) ?>
        <?= "<?= " ?>Html::a('<i class="glyphicon glyphicon-trash"></i> ' . <?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="detail-view-container">
        <?= "<?= " ?>DetailView::widget([
            'options' => ['class' => 'table detail-view'],
            'model' => $model,
            'attributes' => [
    <?php
    if (($tableSchema = $generator->getTableSchema()) === false) {
        foreach ($generator->getColumnNames() as $name) {
            echo "                '" . $name . "',\n";
        }
    } else {
        foreach ($generator->getTableSchema()->columns as $column) {
            $format = $generator->generateColumnFormat($column);        
            if ($column->name == 'id') {
                echo "            // '" . $column->name . "',\n";
            } elseif ($column->name == 'created_at' || $column->name == 'updated_at' || $column->name == 'is_deleted') {
                echo "                // '" . $column->name . ":datetime',\n";
            } elseif ($column->name == 'created_by' && StringHelper::basename($generator->modelClass) != 'User') {
                echo "                // 'createdBy.username:text:Created By',\n";
            } elseif ($column->name == 'updated_by' && StringHelper::basename($generator->modelClass) != 'User') {
                echo "                // 'updatedBy.username:text:Updated By',\n";
            } elseif (substr($column->name, -3) == '_id') {
                echo "                '" . lcfirst(Inflector::camelize(substr($column->name, 0, -3))) . ".name:text:" . ucwords(Inflector::humanize(substr($column->name, 0, -3))) . "',\n";
            } elseif ($column->type == 'double') {
                echo "                [
                    'attribute' => '" . $column->name . "',
                    'format' => ['decimal', 2],
                ],\n";
            } else {
                echo "                '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            }
        }
    }
    ?>
            ],
        ]) ?>
    </div>
    
</div>
