<?php

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditTrail $model */

use bedezign\yii2\audit\Audit;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('audit', 'Trail #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Trails'), 'url' => ['trail/index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

?>

<?= DetailView::widget([
    'options' => ['class' => 'table detail-view detail-view-container'],
    'model' => $model,
    'attributes' => [
        'id',
        [
            'label' => $model->getAttributeLabel('user_id'),
            'value' => Audit::getInstance()->getUserIdentifier($model->user_id),
            'format' => 'raw',
        ],
        [
            'attribute' => 'entry_id',
            'value' => $model->entry_id ? Html::a($model->entry_id, ['entry/view', 'id' => $model->entry_id]) : '',
            'format' => 'raw',
        ],
        'action',
        'model',
        'model_id',
        'field',
        'created',
    ],
]); ?>

<?php if ($model->getDiffHtml()) { ?>
    <div class="detail-view-container" style="padding:0 10px">
        <h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
            Difference
        </h4>
        <?= $model->getDiffHtml() ?>
        <p></p>
    </div>
<?php } ?>
