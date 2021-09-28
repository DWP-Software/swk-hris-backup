<?php

use common\models\entity\Client;
use common\models\entity\ContractPlacement;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\models\entity\Employee;
use common\models\entity\LatestContractPlacement;
use common\models\entity\Presence;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Presence */

$this->title = 'Input Kehadiran Harian';
$this->params['breadcrumbs'][] = ['label' => 'Kehadiran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['create-daily'])]); ?>
<div class="detail-view-container" style="padding: 10px">
<div class="row">

    <div class="col-md-3 col-sm-12">
    <?= DatePicker::widget([
        'name' => 'date',
        'value' => $date,
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'readonly' => true,
        'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
    ]); ?>
    </div>

    <?php 
        if (Yii::$app->user->can('Administrator')) {
            $data = ArrayHelper::map(Client::find()->all(), 'id', 'name');
        } else {
            $data = ArrayHelper::map(Client::find()->joinWith(['picClients'])->where(['user_id' => Yii::$app->user->id, 'role' => 'Kehadiran'])->all(), 'id', 'name');
        }
    ?>
    <div class="col-md-3 col-sm-12">
    <?= Select2::widget([
        'name' => 'client_id',
        'value' => $client_id,
        'data' => $data,
        'options' => ['placeholder' => 'pilih Mitra...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    </div>
    
    <div class="col-md-3 col-sm-12">
        <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> Lihat', ['class' => 'btn btn-default']) ?>     
    </div>

</div>
</div>
<?php ActiveForm::end(); ?>


<?php $form = ActiveForm::begin(); ?>
<?php if ($client_id) { ?>
    <div class="detail-view-container">
    <table class="table table-hover table-striped" style="margin:0">
    <?php 
        $latestContractPlacements = ContractPlacement::find()
            ->joinWith(['contract.employee'])
            ->where(['contract_placement.client_id' => $client_id])
            ->andWhere(['<=', 'contract.started_at', $date])
            ->andWhere(['>=', 'contract.ended_at', $date])
            ->orderBy('employee.name ASC')
            ->all(); 
    ?>
    <?php foreach ($latestContractPlacements as $latestContractPlacement) { ?>

        <tr>
            <td style="vertical-align:middle"><?= $latestContractPlacement->contract->employee->name ?></td>
            <td><?= Select2::widget([
                'value' => 1,
                'name' => $latestContractPlacement->contract_id,
                'data' => Presence::presenceTypes(),
                'pluginOptions' => ['allowClear' => true, 'placeholder' => ''],
            ]) ?></td>
        </tr>

        <?php // echo $form->field($model, 'is_late')->checkbox() ?>
        <?php // echo $form->field($model, 'overtime_summary')->textInput() ?>

    <?php }?>
    </table>
    </div>

    <div class="form-panel">
        <div class="row">
            <div class="col-sm-12">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> Simpan', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

<?php } ?>
<?php ActiveForm::end(); ?>