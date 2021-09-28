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
use common\models\entity\ContractSalary;
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
    <b style="opacity: 0.5">K E U A N G A N</b>
</div>

<div class="row">
    
    <div class="col-md-12">
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                <i class="fa fa-bar-chart"></i> Pembayaran Gaji
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
                        $pre_count = Payroll::find()->where(['payroll.month' => $key, 'payroll.year' => $year])->count();
                        $sum       = PayrollDetail::find()->joinWith(['payroll'])->where(['month' => $key, 'year' => $year])->sum('amount');
                        
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
                            ['name' => 'Jumlah Karyawan', 'data' => $count, 'type' => 'column', 'yAxis' => 1, /* 'dashStyle' => 'shortdash', 'color' => '#aaa' */],
                            // ['name' => 'Rasio Transaksi', 'data' => $ratio, 'type' => 'area', 'dashStyle' => 'shortdot', 'color' => 'yellow'],
                        ],
                   ]
                ]); ?>
            </div>

        </div>
    </div>

    <div class="col-md-12">
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                <i class="fa fa-bar-chart"></i> Pembayaran Gaji Berdasarkan Komponen
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
                    $salary_types = ContractSalary::types();
                    $salary_types_permanent = ContractSalary::permanentTypes();
                    $salary_types_conditional = ContractSalary::conditionalTypes();
                    foreach ($salary_types as $pre_key_status => $pre_value_status) {
                        foreach ($salary_types_permanent[$pre_key_status] as $key_status => $value_status) {
                            $value[$value_status] = [];
                            $total[$value_status] = 0;
                        }
                        foreach ($salary_types_conditional[$pre_key_status] as $key_status => $value_status) {
                            $value[$value_status] = [];
                            $total[$value_status] = 0;
                        }
                    }
                    foreach ($months as $key_month => $value_month) {
                        foreach ($salary_types as $pre_key_status => $pre_value_status) {
                            foreach ($salary_types_permanent[$pre_key_status] as $key_status => $value_status) {
                                $count = PayrollDetail::find()->joinWith(['payroll'])->where(['type' => $pre_key_status, 'name' => $value_status, 'month' => $key_month, 'year' => $year])->sum('amount');
                                $count = (int) $count;
                                if ($pre_key_status === 3) $count = -$count;
                                $value[$value_status][] = $count;
                                $total[$value_status]+=$count;
                            }
                            foreach ($salary_types_conditional[$pre_key_status] as $key_status => $value_status) {
                                $count = PayrollDetail::find()->joinWith(['payroll'])->where(['type' => $pre_key_status, 'name' => $value_status, 'month' => $key_month, 'year' => $year])->sum('amount');
                                $count = (int) $count;
                                if ($pre_key_status === 3) $count = -$count;
                                $value[$value_status][] = $count;
                                $total[$value_status]+=$count;
                            }
                        }
                        $label[] = $value_month;
                    }
                    foreach ($salary_types as $pre_key_status => $pre_value_status) {
                        foreach ($salary_types_permanent[$pre_key_status] as $key_status => $value_status) {
                            $series[] = ['name' => $value_status, 'data' => $value[$value_status], 'type' => 'column', 'total' => Yii::$app->formatter->asInteger($total[$value_status])];
                        }
                        foreach ($salary_types_conditional[$pre_key_status] as $key_status => $value_status) {
                            $series[] = ['name' => $value_status, 'data' => $value[$value_status], 'type' => 'column', 'total' => Yii::$app->formatter->asInteger($total[$value_status])];
                        }
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
                            'labelFormat' => '<b>{name}</b>: {options.total}',
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