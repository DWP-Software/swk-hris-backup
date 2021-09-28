<?php

use common\models\entity\PayrollDetail;
use common\models\entity\PlacementContractSalary;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Payroll */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payroll', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div>
    <h2 class="text-center" style="margin:0"><b>PT SALAM WADAH KARYA</b></h2>
    <p class="text-center" style="margin-top:0"><b>S L I P &nbsp; &nbsp; G A J I</b></p>

    <table cellspacing="0" width="100%" style="border-top:1px solid #ddd">
        <tr>
            <td width="50%" valign="top" style="padding:10px">
                <table cellspacing="0">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><?= $model->contract->employee->name ?></td>
                    </tr>
                    <tr>
                        <td>NRK</td>
                        <td>:</td>
                        <td><?= $model->contract->employee->registration_number ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td><?= $model->contract->employeeType->name ?></td>
                    </tr>
                </table>

                <br><b>PENDAPATAN</b>
                <table cellspacing="0" class="table" width="100%">
                    <?php foreach ($model->payrollDetails as $detail) { ?>
                        <?php if ($detail->type < '3') { ?>
                        <tr>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd"><?= $detail->name ?></td>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($detail->amount) ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
                <table cellspacing="0" class="table">
                    <tr>
                        <th style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">Penghasilan Bruto</th>
                        <th style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($model->grossPayment) ?></th>
                    </tr>
                </table>
            </td>

            <td width="50%" valign="top" style="padding:10px">
                <table cellspacing="0">
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td><?= $model->contract->contractPlacements[0]->position ?></td>
                    </tr>
                    <tr>
                        <td>Penempatan</td>
                        <td>:</td>
                        <td><?= $model->client ? $model->client->name : $model->contract->contractPlacements[0]->client->name ?></td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>:</td>
                        <td><?= monthName($model->month) ?> <?= $model->year ?></td>
                    </tr>
                </table>

                <br><b>POTONGAN</b>
                <table cellspacing="0" class="table" width="100%">
                    <?php foreach ($model->payrollDetails as $detail) { ?>
                        <?php if ($detail->type == '3') { ?>
                        <tr>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd"><?= $detail->name ?></td>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($detail->amount) ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                    <tr>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">&nbsp;</td>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right"></td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">&nbsp;</td>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right"></td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">&nbsp;</td>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right"></td>
                    </tr>
                </table>

                <table cellspacing="0" class="table">
                    <tr>
                        <th style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">Total Potongan</th>
                        <th style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($model->totalCut) ?></th>
                    </tr>
                    <tr>
                        <th style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">JUMLAH DITERIMA</th>
                        <th style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($model->netSalary) ?></th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
