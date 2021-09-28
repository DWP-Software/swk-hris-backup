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
        <td>
            <table width="100%" class="table-report">
                <tr><td>Nama	            </td><td>:</td>	<td><?= $model->placement->employee->name ?></td>
                <tr><td>NRK	                </td><td>:</td>	<td><?= $model->placement->employee->registration_number ?></td>
                <tr><td>Posisi/Jabatan	    </td><td>:</td>	<td><?= $model->position ?></td>
                <tr><td>Tempat/Tgl Lahir    </td><td>:</td>	<td><?= $model->placement->employee->place_of_birth ?>, <?= $model->placement->employee->date_of_birth ?></td>
                <tr><td>Alamat (KTP)        </td><td>:</td>	<td><?= $model->placement->employee->addressText ?></td>
                <tr><td>Telp                </td><td>:</td>	<td><?= $model->placement->employee->phone ?></td>
                <tr><td>Status Perkawinan   </td><td>:</td>	<td><?= Employee::maritalStatuses($model->placement->employee->marital_status) ?></td>
                <tr><td>No. KTP             </td><td>:</td>	<td><?= $model->placement->employee->identity_number ?></td>
                <tr><td>No. KK             </td><td>:</td>	<td><?= $model->placement->employee->family_number ?></td>
                <tr><td>No. NPWP            </td><td>:</td>	<td><?= $model->placement->employee->id ?></td>
                <tr><td>No. Rekening        </td><td>:</td>	<td><?= $model->placement->employee->bank_name ?> <?= $model->placement->employee->bank_account ?></td>
                <tr><td>Agama               </td><td>:</td>	<td><?= Employee::religions($model->placement->employee->religion) ?></td>
                <tr><td>Ibu Kandung         </td><td>:</td>	<td><?= $model->placement->employee->mother_name ?></td>
            </table>
        </td>
        <td>
            <table width="100%" class="table-report">
                <tr><td>Tgl. Aktif Kerja    </td><td>:</td><td><?= $model->started_at ?></td>
                <tr><td>Project	            </td><td>:</td><td><?= $model->placement->client->name ?></td>
                <tr><td>Site	            </td><td>:</td><td><?= $model->placement->client->name ?></td>
                <tr><td>Departement	        </td><td>:</td><td><?= $model->placement->client->name ?></td>
                <tr><td>Durasi Kontrak	        </td><td>:</td><td><?= $model->duration ?> : <?= $model->started_at ?> - <?= $model->ended_at ?></td>
                <?php foreach ($model->placementContractSalaries as $salary) : ?>
                    <tr><td><?= $salary->name ?>	        </td><td>:</td><td>Rp <?= Yii::$app->formatter->asDecimal($salary->amount, 0) ?></td>
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
            <br><b>Nuke</b>
            <br>
        </td>
        <td width="25%" align="center">
            Tgl:
            <br>
            <br>
            <br>
            <br>
            <br>
            <br><b>Rohdjaya Ibrahim</b>
            <br>Senior HR & Opr. Manager
        </td>
        <td width="25%" align="center">
            Tgl:
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>BPJS
        </td>
        <td width="25%" align="center">
            Tgl:
            <br>
            <br>
            <br>
            <br>
            <br>
            <br><b>Pandu</b>
            <br>PIC/Finance
        </td>
    </tr>		
</table>

<br>
<br>
<i>Lembar distribusi	: 	1. HR.Admin Office, 2. HR Admin BPJS/Asuransi, 3. Dept Finance</i>				
