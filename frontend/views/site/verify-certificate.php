<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'Verifikasi Sertifikat UKW';
$post = Yii::$app->request->post();
?>

<div class="row">
    <div class="col-md-6 col-sm-12">

        <?php $form = ActiveForm::begin(); ?>
        
        <div class="form-group">
            <!-- <label for="certificate_number">Masukkan Nomor Sertifikat UKW:</label> -->
            <div class="input-group">
                <input type="text" name="certificate_number" value="<?= (isset($post['certificate_number']) ? $post['certificate_number'] : '') ?>" class="form-control" placeholder="masukkan nomor sertifikat UKW ..."/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i> Validasi</button>
                </span>
            </div>
            <?php // echo Html::textInput('certificate_number', (isset($post['certificate_number']) ? $post['certificate_number'] : ''), ['class' => 'form-control']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <?php if ($post) { 
            if (!$model) {
                echo '<div class="form-panel text-danger">'.$post['certificate_number'].' bukan nomor sertifikat yang valid.</div>';
            } else { ?>
                <div class="form-panel" style="padding:10px 20px !important">
                    <h4><i class="fa fa-mortar-board"></i> KOMPETEN</h4>
                    <p>
                        <b><?= $model->name ?></b> dengan Nomor Sertifikat UKW <?= $model->certificate_number ?> 
                        sudah melaksanakan UKW dengan Jenis Media <?= $model->media->mediaType->name ?>
                        dan Jenjang Wartawan <?= $model->level_submitted ?> oleh Lembaga Uji PWI pada tanggal <?= date('d F Y', strtotime($model->exam->date)) ?>
                        dan dinyatakan <span class="text-success"><b>KOMPETEN</b></span>
                    </p>
                </div>
            <?php }
        } ?>

    </div>
</div>
