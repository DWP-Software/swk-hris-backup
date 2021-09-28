<?php

use common\models\entity\ActiveContract;
use common\models\entity\ActiveContractPlacement;
use common\models\entity\Allocation;
use common\models\entity\Bundle;
use common\models\entity\Coupon;
use common\models\entity\Distribution;
use common\models\entity\Employee;
use common\models\entity\Item;
use common\models\entity\Client;
use common\models\entity\Contract;
use common\models\entity\LatestContract;
use common\models\entity\LatestContractPlacement;
use common\models\entity\Log;
use common\models\entity\Payment;
use common\models\entity\Payroll;
use common\models\entity\PayrollDetail;
use common\models\entity\Presence;
use common\models\entity\User;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;

$formatter = Yii::$app->formatter;
?>

<?= Yii::$app->user->can('Administrator') ? '<br>' : '' ?>

<div class="form-panel text-center" style="margin-bottom: 20px">
    <b style="opacity: 0.5">K E H A D I R A N</b>
</div>

<div class="row">
  
    <div class="col-md-12">
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                <i class="fa fa-bar-chart"></i> Status Kehadiran
            </h4>
            <div id="chart">

                <br>
                <?php 
                    $date_start = date('01/01/Y');
                    $date_end   = date('31/12/Y');
                    $year = date('Y');
                    // $year = '2019';

                    $label = [];
                    $value = [];
                    $total = [];
                    $series = [];
                    $months = months();
                    $statuses = Presence::statuses();
                    foreach ($statuses as $key_status => $value_status) {
                        $value[$key_status] = [];
                        $total[$key_status] = 0;
                    }
                    foreach ($months as $key_month => $value_month) {
                        foreach ($statuses as $key_status => $value_status) {
                            $count = Presence::find()->where(['status' => $key_status, 'month(date)' => $key_month, 'year(date)' => $year])->count();
                            $value[$key_status][] = (int) $count;
                            $total[$key_status]+= (int) $count;
                        }
                        $label[] = $value_month;
                    }
                    foreach ($statuses as $key_status => $value_status) {
                        $series[] = ['name' => $value_status, 'data' => $value[$key_status], 'type' => 'column', 'total' => Yii::$app->formatter->asInteger($total[$key_status])];
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
                                    'text' => '',
                                ],
                            ],
                        ],
                        'series' => $series,
                        'legend' => [
                            'enabled' => true,
                            'labelFormat' => '<b>{name}</b>: {options.total:.0f}',
                            'events'      => [
                                'click' => function () {
                                    return false; // <== returning false will cancel the default action
                                }
                            ],
                            'align' => 'right',
                            'layout' => 'vertical',
                            'verticalAlign' => 'top',
                        ]
                   ]
                ]); ?>
            </div>

        </div>
    </div>

</div>