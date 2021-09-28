<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Presence */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kehadiran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="presence-view box-- box-info--">

    <div class="box-body--">
        <p>
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Update', ['update', 'id' => $model->id], [
            'class' => 'btn btn-warning',
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i> ' . 'Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        </p>

        <div class="detail-view-container">
        <?= DetailView::widget([
            'options' => ['class' => 'table detail-view'],
            'model' => $model,
            'attributes' => [
                // 'id',
                'employee.name:text:Employee',
                'date',                
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => $model->presenceTypes((string)$model->status),
                ],
                // 'is_late:integer',
                // [
                //     'attribute' => 'overtime_summary',
                //     'format' => ['decimal', 2],
                // ],
                // 'created_at:datetime',
                // 'updated_at:datetime',
                // 'createdBy.username:text:Created By',
                // 'updatedBy.username:text:Updated By',
            ],
        ]) ?>
        </div>
    </div>
</div>
