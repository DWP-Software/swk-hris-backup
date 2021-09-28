<?php

use common\models\entity\ActiveContractPlacement;
use common\models\entity\Allocation;
use common\models\entity\Bundle;
use common\models\entity\Coupon;
use common\models\entity\Distribution;
use common\models\entity\Employee;
use common\models\entity\Item;
use common\models\entity\client;
use common\models\entity\LatestContract;
use common\models\entity\LatestContractPlacement;
use common\models\entity\Log;
use common\models\entity\Payment;
use common\models\entity\Payroll;
use common\models\entity\PayrollDetail;
use common\models\entity\User;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */

$this->title = 'Dashboard';
// $this->title = Yii::$app->name;
// Yii::$app->params['showTitle'] = false;
$formatter = Yii::$app->formatter;
?>

<div class="row">
    
    <div class="col-md-12">
        <div class="detail-view-container">
            <div class="box-body">
                <h5 style="margin:0; margin-bottom: 15px">Karyawan</h5>
                <div class="text-right" style="font-size:22pt"><?= $formatter->asInteger(ActiveContractPlacement::find()->where(['client_id' => Yii::$app->user->identity->clientUser->client_id])->count('contract_id')) ?></div>
            </div>
            <a href="<?= Url::to(['/employee/index']) ?>" style="background:#f4f4f4; border-radius:0" class="btn-block btn btn-xs"> &nbsp; <div class="small pull-right">detail &nbsp;<i class="fa fa-arrow-right"></i></div></a>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                <i class="fa fa-bar-chart"></i> Penggajian
            </h4>
            <div id="chart">

                <br>

                <?php 
                    $date_start = date('01/01/Y');
                    $date_end   = date('31/12/Y');
                    $year = date('Y');
                    // $year = '2019';

                    $label = [];
                    $score = [];
                    $count = [];
                    $ratio = [];
                    $months = months();
                    foreach ($months as $key => $value) {
                        $pre_count = Payroll::find()->joinWith(['contract.contractPlacements'])->where(['payroll.month' => $key, 'payroll.year' => $year, 'contract_placement.client_id' => Yii::$app->user->identity->clientUser->client_id])->count();
                        $sum       = PayrollDetail::find()->joinWith(['payroll.contract.contractPlacements'])->where(['month' => $key, 'year' => $year, 'contract_placement.client_id' => Yii::$app->user->identity->clientUser->client_id])->sum('amount');
                        
                        $label[] = $value;
                        $score[] = (int) $sum;
                        $count[] = (int) $pre_count;
                        $ratio[] = (int) $pre_count == 0 ? 0 : (int) $sum / (int) $pre_count;
                    }
                ?>
                <?= Highcharts::widget([
                    'setupOptions' => [
                        'lang' => [
                            'numericSymbols' => [' rb', ' jt', ' M', ' T', ' P', ' E']
                        ],
                    ],
                    'options' => [
                        'credits' => ['enabled' => false],
                        // 'tooltip' => ['enabled' => false],
                        'chart' => [
                            'backgroundColor' => null,
                            'type' => 'areaspline',
                            'style' => [
                                'fontFamily' => 'Barlow, Segoe UI, Helvetica, Arial, sans-serif',
                                'width' => '100%'
                            ],
                        ],
                        // 'title' => ['text' => 'Hasil Penilaian'],
                        'title' => ['text' => ''],
                        'xAxis' => [
                            'categories' => $label
                        ],
                        'yAxis' => [
                            [
                                'title' => [
                                    'text' => 'Jumlah Gaji',
                                ],
                            ],
                            [
                                'title' => [
                                    'text' => 'Jumlah Karyawan',
                                ],
                                'opposite' => true
                            ],
                        ],
                        'series' => [
                            ['name' => 'Jumlah Gaji', 'data' => $score, 'type' => 'column'],
                            ['name' => 'Jumlah Karyawan', 'data' => $count, 'type' => 'line', 'yAxis' => 1, 'dashStyle' => 'shortdash', 'color' => '#aaa'],
                            // ['name' => 'Rasio Transaksi', 'data' => $ratio, 'type' => 'area', 'dashStyle' => 'shortdot', 'color' => 'yellow'],
                        ],
                   ]
                ]); ?>

            </div>

        </div>
    </div>
    
</div>


<?php
$this->registerJs(' 
    initializeClock();

    function initializeClock() {

        function updateClock() {
            $(Highcharts.charts).each(function(i,chart){
                chart.reflow(); 
            });
            $(window).resize();
            // clearInterval(timeinterval);
        }

        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
    }
    ', \yii\web\VIEW::POS_READY);
?>