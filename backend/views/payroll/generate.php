<?php

use common\models\entity\Client;
use common\models\entity\Employee;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use common\models\entity\PlacementContract;
use common\models\entity\ContractSalary;
use common\models\entity\Presence;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Payroll */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Generate Pembayaran Gaji';
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Gaji', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['generate'])]); ?>
<div class="detail-view-container" style="padding: 10px">
<div class="row">

    <div class="col-md-2 col-sm-12">
    <?= Html::textInput('year', $year, ['class' => 'form-control', 'placeholder' => 'pilih Tahun...']) ?>
    </div>

    <div class="col-md-3 col-sm-12">
    <?= Select2::widget([
        'name' => 'month',
        'value' => (int) $month,
        'data' => months(),
        'options' => ['placeholder' => 'pilih Bulan...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    </div>

    <div class="col-md-3 col-sm-12">
    <?php 
        if (Yii::$app->user->can('Administrator')) {
            $data = ArrayHelper::map(Client::find()->all(), 'id', 'name');
        } else {
            $data = ArrayHelper::map(Client::find()->joinWith(['picClients'])->where(['user_id' => Yii::$app->user->id, 'role' => 'Keuangan'])->all(), 'id', 'name');
        }
    ?>
    <?= Select2::widget([
        'name' => 'client_id',
        'value' => $client_id,
        'data' => $data,
        'options' => ['placeholder' => 'pilih Mitra...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    </div>

    <div class="col-md-3 col-sm-12">
        <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> Generate', ['class' => 'btn btn-default']) ?>     
    </div>

</div>
</div>
<?php ActiveForm::end(); ?>


<?php if ($contracts) { ?>
<?php foreach ($contracts as $contract) { ?>
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form-'.$contract->id
        ]
    ]); ?>
    <div class="detail-view-container box-body" style="margin-bottom:10px">
        <table width="100%">
            <tr>
                <!-- <td valign="top" style="padding-right:10px; white-space:nowrap; width:1px"><?= Html::checkbox("contract[$contract->id]", false) ?></td> -->
                <td valign="top">
                    <div class="detail-view-container" style="padding:5px 10px;margin-bottom:5px; background:#f4f4f4">
                        <div class="pull-right">
                            <?= Html::button('Simpan', [
                                'id'      => 'btn-'.$contract->id,
                                'class'   => 'btn btn default btn-success btn-sm pull-right',
                                'onclick' => 'pay("'.$contract->id.'", "'.Url::to(['pay', 'contract_id' => $contract->id, 'year' => $year, 'month' => $month, 'client_id' => $client_id]).'")',
                            ]) ?><br>
                            <div class="pull-right" id="info-<?= $contract->id?>"></div>
                        </div>
                        <b><?= $contract->employee->name ?></b>
                        <br><?= $contract->employee->registration_number ?>
                        <br><?= $contract->contractPlacements[0]->position ?> <?= $contract->started_at ?> &raquo; <?= $contract->ended_at ?>
                    </div>
                    
                    <div class="row">
                    <?php foreach (ContractSalary::types() as $key_type => $value_type) { ?>
                        <div class="col-md-4">
                            <h5><b><?= strtoupper($value_type) ?></b></h5>
                            <div class="detail-view-container" style="margin-bottom:5px">
                                <table class="table table-condensed table-hover detail-view">
                                <?php foreach (ContractSalary::permanentTypes($key_type) as $key_permanent => $value_permanent) { ?>
                                    <?php $salary = ContractSalary::findOne([
                                        'contract_id' => $contract->id,
                                        'type'        => $key_type,
                                        'name'        => $value_permanent,
                                    ]); ?>
                                    <tr>
                                        <td style="min-width:300px">
                                            <?= $value_permanent ?>
                                            <span class="pull-right">Rp <input name="<?= $contract->id ?>[<?= $key_type ?>][permanent][<?= $key_permanent ?>]" style="text-align:right; border-radius:4px; padding:0 5px; border:1px solid #ccc" type="text" value="<?= !$salary ? null : $salary->amount ?>"/></span>
                                            <br><small class="text-muted"><?= !$salary ? null : $salary->description ?></small>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php foreach (ContractSalary::conditionalTypes($key_type) as $key_conditional => $value_conditional) { ?>
                                    <?php 
                                        $salary = ContractSalary::findOne([
                                            'contract_id' => $contract->id,
                                            'type'        => $key_type,
                                            'name'        => $value_conditional,
                                        ]);
                                        $value = !$salary ? null : $salary->amount;
                                        
                                        if ($value_conditional == 'Potongan Absensi') {
                                            $count_absence = Presence::find()->where([
                                                'contract_id' => $contract->id,
                                                'employee_id' => $contract->employee_id,
                                                'client_id'   => $client_id,
                                                'month(date)' => $month,
                                                'year(date)'  => $year,
                                                'status'      => 'A',
                                            ])->count();
                                            if ($count_absence) $value = (int) (($contract->baseSalary/25) * $count_absence);
                                        }
                                    ?>
                                    <tr>
                                        <td style="min-width:300px">
                                            <?= $value_conditional ?>
                                            <span class="pull-right">Rp <input name="<?= $contract->id ?>[<?= $key_type ?>][conditional][<?= $key_conditional ?>]" style="text-align:right; border-radius:4px; padding:0 5px; border:1px solid #ccc" type="text" value="<?= $value ?>"/></span>
                                            <br><small class="text-muted"><?= !$salary ? null : $salary->description ?></small>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                    </div>

                </td>
            </tr>
        </table>
    </div>
    <?php ActiveForm::end(); ?>
<?php } ?>

<div style="margin-top:20px; display:none">
<?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> ' . ($model->isNewRecord ? 'Simpan' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>     
</div>

<?php } ?>

<?php 
$js = <<<JAVASCRIPT
function pay(contract_id, url) {
    form_data = $('#form-'+contract_id).serialize();
    $.ajax({
        url: url,
        data: form_data,
        error: function() {
            $('#info-'+contract_id).html('<p>An error has occurred</p>');
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('#info-'+contract_id).html('<span class="text-'+data.status+'">'+data.message+'</span>');
            if (data.status == 'success') $('#btn-'+contract_id).fadeOut();
        },
        type: 'POST'
    });
}
JAVASCRIPT;

$this->registerJs($js, \yii\web\View::POS_END);
?>