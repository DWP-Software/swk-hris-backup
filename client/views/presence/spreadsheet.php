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

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Kehadiran.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>

        <?php if (!$models) { ?>
            Tidak ada data
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
                        <th style="font-family:monospace"><?= str_pad($date, 2, "0", STR_PAD_LEFT) ?></th>
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
                        <td><?= ++$i; ?></td>
                        <td><?= $model['registration_number'] ?></td>
                        <td><?= $model['name'] ?></td>
                        <td><?= $model['id'] ?></td>
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
                            <td class="text-center" style="font-family:monospace; <?= $status == '1' || $status == 'T' || $status == 'C' || $status == 'SL' ? 'background:lightgreen' : 'background:pink' ?>">
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
        