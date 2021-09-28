<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Log */

$this->title = $model->log_time;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$arr = explode('.', $model->log_time);
$arr[1] = isset($arr[1]) ? '.'.$arr[1] : '';

?>
<div class="log-view">

    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
    <p></p>

    <div class="detail-view-container">
    <?= DetailView::widget([
        'options'=> ['class' => 'table detail-view'],
        'template' => '<tr><th width="140px">{label}</th><td class="text-mono">{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'level',
                'value' => \yii\Log\Logger::getLevelName($model->level),
            ],
            'category',
            
            [
                'attribute' => 'log_time',
                'value' => date('Y-m-d H:i:s'.$arr[1], $arr[0]),
            ],
            'prefix:ntext',
            'message:ntext',
        ],
    ]) ?>
    </div>

</div>
