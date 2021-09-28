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
use common\models\entity\User;
use miloschuman\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;

$formatter = Yii::$app->formatter;
?>

<div class="form-panel text-center" style="margin-bottom: 20px">
    <b style="opacity: 0.5">R E K R U T M E N</b>
</div>

<div class="row">
    
<div class="col-md-4">
        <div class="detail-view-container">
            <div class="box-body">
                <h5 style="margin:0; margin-bottom: 15px">Karyawan Aktif</h5>
                <div class="text-right" style="font-size:22pt"><?= $formatter->asInteger(ActiveContract::find()->count('contract_id')) ?></div>
            </div>
            <!-- <a href="<?= Url::to(['/item/index']) ?>" style="background:#f4f4f4; border-radius:0" class="btn-block btn btn-xs"> &nbsp; <div class="small pull-right">detail &nbsp;<i class="fa fa-arrow-right"></i></div></a> -->
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="detail-view-container">
            <div class="box-body">
                <h5 style="margin:0; margin-bottom: 15px">Karyawan Menunggu Ttd Kontrak</h5>
                <?php 
                    $contracted_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
                    $unexpired_contracts  = ArrayHelper::getColumn(LatestContract::find()->select('employee_id')->where(['>=', 'contract_ended_at', date('Y-m-d')])->asArray()->all(), 'employee_id');
                    $query = Employee::find();
                    $query->where(['in', 'employee.id', $contracted_employees]);
                    $query->andWhere(['not in', 'employee.id', $unexpired_contracts]);
                    $count = $query->count();
                ?>
                <div class="text-right" style="font-size:22pt"><?= $formatter->asInteger($count) ?></div>
            </div>
            <!-- <a href="<?= Url::to(['/item/index']) ?>" style="background:#f4f4f4; border-radius:0" class="btn-block btn btn-xs"> &nbsp; <div class="small pull-right">detail &nbsp;<i class="fa fa-arrow-right"></i></div></a> -->
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="detail-view-container">
            <div class="box-body">
                <h5 style="margin:0; margin-bottom: 15px">Karyawan Akan Habis Kontrak</h5>
                <?php 
                    $contracted_employees = ArrayHelper::getColumn(Employee::find()->select('employee.id')->joinWith(['contracts'])->where(['is not', 'contract.id', null])->asArray()->all(), 'id');
                    $ending_contracts     = ArrayHelper::getColumn(LatestContract::find()->select('employee_id')->where([
                        'and', 
                        ['is not', 'file', null], 
                        ['>=', 'contract_ended_at', date('Y-m-d')], 
                        ['<=', 'DATEDIFF(contract_ended_at, \''.date('Y-m-d').'\')', 30]
                    ])->asArray()->all(), 'employee_id');
                    $query = Employee::find();
                    $query->where(['in', 'employee.id', $contracted_employees]);
                    $query->andWhere(['in', 'employee.id', $ending_contracts]);
                    $count = $query->count();
                ?>
                <div class="text-right" style="font-size:22pt"><?= $formatter->asInteger($count) ?></div>
            </div>
            <!-- <a href="<?= Url::to(['/item/index']) ?>" style="background:#f4f4f4; border-radius:0" class="btn-block btn btn-xs"> &nbsp; <div class="small pull-right">detail &nbsp;<i class="fa fa-arrow-right"></i></div></a> -->
        </div>
    </div>
    
    <!-- <div class="col-md-3">
        <div class="detail-view-container">
            <div class="box-body">
                <h5 style="margin:0; margin-bottom: 15px">Mitra</h5>
                <div class="text-right" style="font-size:22pt"><?= $formatter->asInteger(Client::find()->count('id')) ?></div>
            </div>
            <a href="<?= Url::to(['/user/index']) ?>" style="background:#f4f4f4; border-radius:0" class="btn-block btn btn-xs"> &nbsp; <div class="small pull-right">detail &nbsp;<i class="fa fa-arrow-right"></i></div></a>
        </div>
    </div> -->
    
    <div class="col-md-8">
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                <i class="fa fa-bar-chart"></i> Kontrak Baru dan Expired
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
                        $new = Contract::find()->where(['month(started_at)' => $key, 'year(started_at)' => $year])->count();
                        $expired = Contract::find()->where(['month(ended_at)' => $key, 'year(ended_at)' => $year])->count();
                        
                        $label[] = $value;
                        $score[] = (int) $new;
                        $count[] = (int) $expired;
                        $ratio[] = (int) $expired == 0 ? 0 : (int) $new / (int) $expired;
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
                        'series' => [
                            ['name' => 'Kontrak Baru', 'data' => $score, 'type' => 'column'],
                            ['name' => 'Kontrak Expire', 'data' => $count, 'type' => 'column'],
                            // ['name' => 'Jumlah Karyawan', 'data' => $count, 'type' => 'line', 'yAxis' => 1, 'dashStyle' => 'shortdash', 'color' => '#aaa'],
                            // ['name' => 'Rasio Transaksi', 'data' => $ratio, 'type' => 'area', 'dashStyle' => 'shortdot', 'color' => 'yellow'],
                        ],
                   ]
                ]); ?>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                <i class="fa fa-pie-chart"></i> Jumlah Karyawan Per Mitra
            </h4>
            <br>
            <?php 
                $data = [];
                $clients = Client::find()->all();
                foreach ($clients as $client) {
                    $count = ActiveContractPlacement::find()->where(['client_id' => $client->id])->count();
                    // if ($count) {
                        $data[] = [
                            'name' => $client->name, 
                            'y' => intval($count),
                        ];
                    // }
                }
            ?>
            <?= Highcharts::widget([
                'options' => [
                    'credits' => ['enabled' => false],
                    'chart'   => [
                        'type'  => 'pie',
                        'style' => [
                            'fontFamily' => 'Barlow, Segoe UI, Helvetica, Arial, sans-serif',
                            'width'      => '100%'
                        ],
                    ],
                    'title'  => ['text' => ''],
                    'series' => [
                        [
                            'type' => 'pie',
                            'name' => 'Jumlah',
                            'size' => '100%',
                            'data' => $data,
                            'showInLegend' => true,
                        ],
                        [
                            'type'      => 'pie',
                            'name'      => 'Jumlah',
                            'size'      => '100%',
                            'innerSize' => '85%',
                            'data'      => $data,
                            'dataLabels' => [
                                'enabled' => false
                            ],
                            'showInLegend' => false,
                        ]
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor'           => 'pointer',
                            'dataLabels'       => [
                                'enabled'  => false,
                                'format'   => '<b>{point.name}</b>: {point.y:.0f}',
                                // 'distance' => -50,
                            ],
                            'events' => [
                                'click' => function () {
                                    return false; // <== returning false will cancel the default action
                                }
                            ],
                        ],
                    ],
                    'legend' => [
                        'enabled' => true,
                        'labelFormat' => '<b>{name}</b>: {y:.0f}',
                        'events'      => [
                            'click' => function () {
                                return false; // <== returning false will cancel the default action
                            }
                        ],
                        // 'align' => 'right',
                        // 'layout' => 'vertical',
                        // 'verticalAlign' => 'top',
                    ]
                ]
            ]); ?>
        </div>
    </div>

</div>