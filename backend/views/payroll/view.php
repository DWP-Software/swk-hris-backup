<?php

use common\models\entity\PayrollDetail;
use common\models\entity\ContractSalary;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Payroll */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payroll', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payroll-view box-- box-info--">

    <div class="box-body--">
        <p>
        <?php /* echo Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Update', ['update', 'id' => $model->id], [
            'class' => 'btn btn-warning',
        ]) */ ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i> ' . 'Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-print"></i> '. 'Print', ['print', 'id' => $model->id], [
            'class' => 'btn btn-default',
        ]) ?>
        </p>

        <div class="detail-view-container" style="display:none">
        <?= DetailView::widget([
            'options' => ['class' => 'table detail-view'],
            'model' => $model,
            'attributes' => [
                // 'id',
                'contract.id:text:Contract',
                'year',
                [
                    'attribute' => 'month',
                    'value' => date('F', mktime(0, 0, 0, $model->month, 10)),
                ],
                // 'created_at:datetime',
                // 'updated_at:datetime',
                // 'createdBy.username:text:Created By',
                // 'updatedBy.username:text:Updated By',
            ],
        ]) ?>
        </div>

    </div>
</div>


<div class="detail-view-container" style="padding:50px">
    <h4 class="text-center" style="margin:0"><b>PT SALAM WADAH KARYA</b></h4>
    <p  class="text-center">S L I P &nbsp; &nbsp; G A J I</p>

    <table width="100%" style="border-top:1px solid #ddd">
        <tr>
            <td width="50%" valign="top" style="padding:10px">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td><?= $model->contract->employee->name ?></td>
                    </tr>
                    <tr>
                        <td>NRK</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td><?= $model->contract->employee->registration_number ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td><?= $model->contract->employeeType->name ?></td>
                    </tr>
                </table>

                <br><b>PENDAPATAN</b>
                <table class="table table-condensed" width="100%">
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
                        <td>&nbsp;:&nbsp;</td>
                        <td><?= $model->contract->contractPlacements[0]->position ?></td>
                    </tr>
                    <tr>
                        <td>Penempatan</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td><?= $model->client ? $model->client->name : $model->contract->contractPlacements[0]->client->name ?></td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>&nbsp;:&nbsp;</td>
                        <td><?= monthName($model->month) ?> <?= $model->year ?></td>
                    </tr>
                </table>

                <br><b>POTONGAN</b>
                <table class="table table-condensed" width="100%">
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
