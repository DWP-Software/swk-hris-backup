<?php

use common\models\entity\Employee;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */

$this->title = $title;
?>

<div class="text-center">
    <h4><b>SURAT PERNYATAAN</b></h4>
</div>

<br>
<p>Saya yang bertanda tangan dibawah ini :</p>				

<table class="table-report-noborder">
    <tr><td>Nama				</td> <td> : </td>	<td><?= $model->name ?></td></tr>
    <tr><td>NIK				    </td> <td> : </td>	<td><?= $model->identity_number ?></td></tr>
    <tr><td>Tempat/Tgl Lahir	</td> <td> : </td>	<td><?= $model->place_of_birth ?>, <?= $model->date_of_birth ?></td></tr>
    <tr><td>Alamat				</td> <td> : </td>	<td><?= $model->addressText ?></td></tr>
</table>

<br>
<p>Dengan ini menyatakan bahwa :</p>
<ol type="1">
    <li>Saya bersedia untuk  bekerja di PT Salam Wadah Karya (SWK) untuk ditempatkan di mitra perusahaan dan menerima  status  kekaryawanan Saya sebagai karyawan kontrak serta tidak akan menuntut sebagai karyawan permanent (tetap).</li>
    <li>Saya bersedia dan menerima segala ketentuan dan peraturan yang berlaku di PT Salam Wadah Karya (SWK) untuk dikontrak dengan tidak terbatas pada masa kerja yang berlanjut serta membebaskan dan melepaskan PT Salam Wadah Karya (SWK) dan tuntutan hukum yang berhubungan dengan hubungan industrial.</li>
    <li>Dengan ditandatanganinya Surat  Pernyataan  ini, maka Saya memahami PT Salam Wadah Karya (SWK)  adalah sebuah perusahaan Outsourcing yang bisnisnya masih tergantung oleh pihak mitra sehingga tidak dapat mengangkat atau merubah status kekaryawanan sebagai karyawan tetap.</li>
</ol>

<p>Demikianlah Surat  Pernyataan ini dibuat dengan sadar dan tanpa paksaan dari Pihak manapun.</p>					

<br>
<table width="100%">
    <tr>
        <td width="50%"></td>
        <td width="50%" align="center">
            Jakarta, <?= date('Y-m-d') ?>
            <br>Hormat Saya
            <br>
            <br>
            <br>
            <br>
            <br><b><?= $model->name ?></b>
        </td>
    </tr>
</table>				
