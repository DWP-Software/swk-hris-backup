<?php

/** @var View $this */
/** @var AuditMail $model */

use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\models\AuditMail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

$this->title = Yii::t('audit', 'Mail #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;
?>

<?= DetailView::widget([
    'options' => ['class' => 'table detail-view detail-view-container'],
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'entry_id',
            'value' => $model->entry_id ? Html::a($model->entry_id, ['entry/view', 'id' => $model->entry_id]) : '',
            'format' => 'raw',
        ],
        'successful',
        'to',
        'from',
        'reply',
        'cc',
        'bcc',
        'subject',
        [
            'label' => Yii::t('audit', 'Download'),
            'value' => ($model->data !== null) ? Html::a(Yii::t('audit', 'Download eml file'), ['mail/download', 'id' => $model->id]) : null,
            'format' => 'raw',
        ],
        'created',
    ],
]); ?>


<div class="detail-view-container" style="padding:0 10px">
<h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
    Text
</h4>
<?= Yii::$app->formatter->asNtext($model->text) ?>
<p></p>
</div>

<div class="detail-view-container" style="padding:0 10px">
<h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
    HTML
</h4>
<iframe src="<?= Url::to(['mail/view-html', 'id' => $model->id]) ?>" style="width:100%;" onload="this.style.height = this.contentWindow.document.body.scrollHeight +'px';" frameborder="0"></iframe>
<p></p>
</div>

<div class="detail-view-container" style="padding:0 10px">
<h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
    Data
</h4>
<?= Yii::$app->formatter->asNtext(Helper::uncompress($model->data)) ?>
<p></p>
</div>
