<?php

use common\models\entity\Employee;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */

$this->title = $title;
?>

<div class="text-center">
    <h4><b>DATA KARYAWAN BARU</b></h4>
</div>

<br>

<table width="100%" class="table-report">
    <tr>
        <td width="60%">
            <table width="100%" class="table-report">
                <tr><td>Nama	            </td><td>:</td>	<td><?= $model->employee->name ?></td>
                <tr><td>NRK	                </td><td>:</td>	<td><?= $model->employee->registration_number ?></td>
                <tr><td>Posisi/Jabatan	    </td><td>:</td>	<td><?= $model->contractPlacements[0]->position ?></td>
                <tr><td>Tempat/Tgl Lahir    </td><td>:</td>	<td><?= $model->employee->place_of_birth ?>, <?= $model->employee->date_of_birth ?></td>
                <tr><td>Alamat (KTP)        </td><td>:</td>	<td><?= $model->employee->addressText ?></td>
                <tr><td>Telp                </td><td>:</td>	<td><?= $model->employee->phone ?></td>
                <tr><td>Status Perkawinan   </td><td>:</td>	<td><?= Employee::maritalStatuses($model->employee->marital_status) ?></td>
                <tr><td>No. KTP             </td><td>:</td>	<td><?= $model->employee->identity_number ?></td>
                <tr><td>No. KK              </td><td>:</td>	<td><?= $model->employee->family_number ?></td>
                <tr><td>No. NPWP            </td><td>:</td>	<td><?= $model->employee->id ?></td>
                <tr><td>No. Rekening        </td><td>:</td>	<td><?= $model->employee->bank_name ?> <?= $model->employee->bank_account ?></td>
                <tr><td>Agama               </td><td>:</td>	<td><?= Employee::religions($model->employee->religion) ?></td>
                <tr><td>Ibu Kandung         </td><td>:</td>	<td><?= $model->employee->mother_name ?></td>
            </table>
        </td>
        <td>
            <table width="100%" class="table-report">
                <tr><td>Tgl. Aktif Kerja    </td><td>:</td><td><?= $model->started_at ?></td>
                <!-- <tr><td>Project	            </td><td>:</td><td><?php // echo $model->client->name ?></td> -->
                <!-- <tr><td>Site	            </td><td>:</td><td><?php // echo $model->client->name ?></td> -->
                <!-- <tr><td>Departement	        </td><td>:</td><td><?php // echo $model->client->name ?></td> -->
                <tr><td>Durasi Kontrak	        </td><td>:</td><td><?= $model->duration ?> : <?= $model->started_at ?> - <?= $model->ended_at ?></td>
                <?php foreach ($model->contractSalaries as $salary) : ?>
                    <tr><td><?= $salary->name ?>	        </td><td>:</td><td align="right">Rp <?= Yii::$app->formatter->asDecimal($salary->amount) ?></td>
                <?php endforeach; ?>
            </table>
        </td>
    </tr>
</table>	
									
<br>
<br>
<table width="100%" class="table-report">
    <tr><td align="center" colspan="4">Mengetahui</td></tr>
    <tr>
        <td width="25%" align="center">
            Tgl:
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>_______________________
            <br>
        </td>
        <td width="25%" align="center">
            Tgl:
            <br>
            <br>
            <br>
            <br>
            <br>
            <br><b><u><?= $model->signer_name ?></u></b>
            <br><?= $model->signer_position ?>
        </td>
        <td width="25%" align="center">
            Tgl:
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>_______________________
            <br>BPJS
        </td>
        <td width="25%" align="center">
            Tgl:
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>_______________________
            <br>PIC/Finance
        </td>
    </tr>		
</table>

<br>
<br>
<i>Lembar distribusi	: 	1. HR.Admin Office, 2. HR Admin BPJS/Asuransi, 3. Dept Finance</i>				
