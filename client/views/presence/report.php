<?php

use common\models\entity\Client;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\daterange\DateRangePicker;
use common\models\entity\Employee;
use common\models\entity\Presence;

/* @var $this yii\web\View */
/* @var $searchModel common\models\entity\PresenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kehadiran - Report';
$this->params['breadcrumbs'][] = $this->title;

$statuses = Presence::statuses();
?>

<div class="presence-report">

</div>

<?php if (!$to_pdf) { ?>

<style>
    .kv-grid-container {border:none !important; overflow: hidden; margin-bottom: 5px; padding-bottom: 5px}
    .table-report {width:100%}
    .table-report td {padding:0px 5px !important}
    .table-report-footer td {padding:0px 5px !important; border:none !important}
</style>

    
    <?php $form = ActiveForm::begin(['action' => Url::to([$params['view']]), 'method' => 'get', 'options' => ['style' => 'display:inline']]); ?>
    
    <div class="" style="display: inline-block; vertical-align: middle">
        <input type="text" name="params[year]" class="form-control" placeholder="tahun..." value="<?= $params['year'] ?>" style="width:100px; display:inline" />
    </div>
    <div class="" style="display: inline-block; min-width:120px; vertical-align: middle">
        <?= Select2::widget([
		    'name' => 'params[month]',
		    'value' => $params['month'],
		    'data' => months(),
		    'options' => ['placeholder' => 'bulan...'],
		]); ?>
    </div>
    
    <div style="display:none; width:200px; vertical-align:bottom">
    <?= Select2::widget([
        'name' => 'params[employee_id]',
        'value' => $params['employee_id'],
        'data' => ArrayHelper::map(Employee::find()->joinWith(['activeContract.contract.contractPlacements'])->where(['client_id' => Yii::$app->user->identity->clientUser->client_id])->all(), 'id', 'shortText'),
        'options' => ['placeholder' => 'semua karyawan'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    </div>

    <?= Html::button('<i class="glyphicon glyphicon-refresh"></i> ' . Yii::t('app', 'Reload'), [
        'type' => 'submit',
        'class' => 'btn btn-default',
        // 'style' => 'border-top-left-radius:0; border-bottom-left-radius:0; margin-left:-1px',
    ]) ?>

    <?= Html::a('<i class="fa fa-file-excel-o"></i> ' . Yii::t('app', 'Export'), ['spreadsheet', 'params' => ['month' => $params['month'], 'year' => $params['year']]], [
        'class' => 'btn btn-default',
        // 'style' => 'border-top-left-radius:0; border-bottom-left-radius:0; margin-left:-1px',
    ]) ?>

    <?php ActiveForm::end(); ?>
    
    <button style="display:none" onclick="window.print()" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Print</button>
    <!-- <a id="print" target="_blank" href="<?= Url::to([$params['view'],
        'params' => $params,
        'to_pdf' => 1,
    ]) ?>" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Print </a> -->

    <p></p>

    <style>
        .table-report > tbody tr > td { padding: 0px 5px; border-bottom: 1px solid #eee; }
        .table-report tr.thead td { font-weight: bold; text-transform: uppercase; border-top:none }
        thead {display: table-header-group;}
    </style>

<?php } ?>


<div class="detail-view-container">
    <div class="printable">

        <?php if (!$models) { ?>
            <div style="margin:20px" class="text-muted">Tidak ada data.</div>
        <?php } else { ?>

            <table class="table table-bordered table-condensed" style="margin-bottom:0px">
                <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">NRK</th>
                    <th rowspan="2">Nama</th>
                    <th rowspan="2">Posisi</th>
                    <th colspan="<?= date('t', strtotime($params['year'].'-'.$params['month'].'-01')) ?>">Date</th>
                    <?php foreach ($statuses as $status_key => $status_value) { ?>
                        <?php if ($status_key == '1') {
                            echo '<th rowspan="2" class="text-right">Effective Working Days</th>';
                        } elseif ($status_key != 'T') {
                            echo '<th rowspan="2" class="text-right" style="min-width:25px">'.$status_key.'</th>';
                        } ?>
                    <?php } ?>
                    <th rowspan="2" class="text-right">Terlambat Hadir</th>
                </tr>
                <tr>
                    <?php for ($date = 1; $date <= date('t', strtotime($params['year'].'-'.$params['month'].'-01')); $date++) { ?>
                        <th style="width:1px;white-space:nowrap; font-family:monospace"><?= str_pad($date, 2, "0", STR_PAD_LEFT) ?></th>
                    <?php } ?>
                </tr>
                </thead>

                <?php 
                    $i = 0;
                    $total = 0;
                    foreach ($models as $model) {
                        $presences = ArrayHelper::index($model['presences'], 'date');
                ?>
                    <tr>
                        <td style="width:1px;white-space:nowrap;"><?= ++$i; ?></td>
                        <td style="width:1px;white-space:nowrap;"><?= $model['registration_number'] ?></td>
                        <td style="width:1px;white-space:nowrap;"><?= $model['name'] ?></td>
                        <td style="width:1px;white-space:nowrap;"><?= $model['id'] ?></td>
                        <?php 
                            foreach ($statuses as $status_key => $status_value) {
                                $subtotal[$status_key] = 0;
                            }
                        ?>
                        <?php for ($date = 1; $date <= date('t', strtotime($params['year'].'-'.str_pad($params['month'], 2, '0', STR_PAD_LEFT).'-01')); $date++) { ?>
                            <?php 
                                $key = $params['year'].'-'.str_pad($params['month'], 2, '0', STR_PAD_LEFT).'-'. str_pad($date, 2, '0', STR_PAD_LEFT); 
                                $status = ArrayHelper::getValue($presences, $key)['status'];
                                foreach ($statuses as $status_key => $status_value) {
                                    if ($status == $status_key ) $subtotal[$status_key]++;
                                }
                            ?>
                            <td class="text-center" style="width:1px;white-space:nowrap; font-family:monospace; <?= ($status == '1' || $status == 'T' || $status == 'C' || $status == 'SL') ? 'background:lightgreen' : 'background:pink' ?>">
                                <?= $status ?>
                            </td>
                        <?php } ?>
                        <?php foreach ($statuses as $status_key => $status_value) { ?>
                            <?php if ($status_key == '1') {
                                echo '<td class="text-right">'.($subtotal['1']+$subtotal['T']).'</td>';
                            } elseif ($status_key != 'T') {
                                echo '<td class="text-right">'.$subtotal[$status_key].'</td>';
                            } ?>
                        <?php } ?>
                        <td class="text-right"><?= $subtotal['T'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        
        <?php } ?>
        
    </div>
</div>


<?php 
if (!isset($to_pdf) || !$to_pdf) {

$js = 
<<<JAVASCRIPT
jQuery(document).bind("keyup keydown", function(e){
if(e.ctrlKey && e.keyCode == 80){
    $('#print').get(0).click();
    return false;
}
});
JAVASCRIPT;

$this->registerJs($js, \yii\web\View::POS_READY);
}
?>