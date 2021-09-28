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

<div class="text-center">
    <h4 style="margin:0"><b>PT SALAM WADAH KARYA</b></h4>
    <p>S L I P &nbsp; &nbsp; G A J I</p>

    <table width="100%" style="border-top:1px solid #ddd">
        <tr>
            <td width="50%" valign="top" style="padding:10px">
                <table>
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

                <br>PENDAPATAN
                <table class="table" width="100%">
                    <?php foreach ($model->payrollDetails as $detail) { ?>
                        <?php if ($detail->type < '3') { ?>
                        <tr>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd"><?= $detail->name ?></td>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($detail->amount) ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
                <table class="table">
                    <tr>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">Penghasilan Bruto</td>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($model->grossPayment) ?></td>
                    </tr>
                </table>
            </td>

            <td width="50%" valign="top" style="padding:10px">
                <table>
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

                <br>POTONGAN
                <table class="table" width="100%">
                    <?php foreach ($model->payrollDetails as $detail) { ?>
                        <?php if ($detail->type == '3') { ?>
                        <tr>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd"><?= $detail->name ?></td>
                            <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($detail->amount) ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </table>

                <table class="table">
                    <tr>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd">Total Potongan</td>
                        <td style="border-top:1px solid #ddd; border-bottom:1px solid #ddd" class="text-right">Rp <?= Yii::$app->formatter->asInteger($model->totalCut) ?></td>
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
