<?php

use common\models\entity\Employee;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */

$this->title = 'selamat datang, ' . $model->name . '!';
// $this->params['breadcrumbs'][] = ['label' => 'Employee', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="employee-view">

    <p>
    <?php if ($model->placementProgress['status'] == Employee::CONTRACT_OPENED) {
        echo Html::a('<i class="glyphicon glyphicon-file"></i> '. 'Draft Kontrak', ['print-contract'], [
            'class' => 'btn btn-default',
        ]);
        echo ' ';
        echo Html::a('<i class="glyphicon glyphicon-file"></i> '. 'Draft Surat Pernyataan', ['print-statement'], [
            'class' => 'btn btn-default',
        ]);
    } ?>
    </p>

    <div class="alert alert-info">
        <?= $model->placementProgress['description'] ?>
        <div class="pull-right">
        <?php if ($model->activeContract) { ?>
            <?= '&nbsp;'.Html::a('Detail Kontrak', ['active-contract'], ['class' => 'btn btn-primary', 'style' => 'margin-top:-7px; text-decoration:none']) ?>
            <?= '&nbsp;'.Html::a('Kehadiran', ['/presence/index'], ['class' => 'btn btn-primary', 'style' => 'margin-top:-7px; text-decoration:none']) ?>
            <?= !$model->activeContract->contract->payrolls ? '' : '&nbsp;'.Html::a('Download Slip Gaji', ['/employee/receipt'], ['class' => 'btn btn-primary', 'style' => 'margin-top:-7px; text-decoration:none']) ?>
        <?php } ?>
        </div>
    </div>

    <div class="detail-view-container box-body" style="padding:0 10px">
    <h3 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Akun</h3>
    <p class="text-muted small">Alamat email berfungsi untuk login sistem SWK. Pastikan alamat email anda valid sehingga dapat melakukan reset password apabila lupa, juga menerima informasi terbaru dari SWK.</p>
    <div class="detail-view-container" style="margin-bottom:10px">
    <?= DetailView::widget([
        'options' => ['class' => 'table detail-view'],
        'model' => $model,
        'attributes' => [
            'user.email:text:Email',
        ],
    ]) ?>
    </div>
    <p>
    <?= Html::a('<i class="fa fa-lock"></i> Ganti Password', ['/site/change-password'], ['class' => 'btn btn-xs']) ?>
    <?php // echo Html::a('<i class="fa fa-envelope"></i> Ganti Email', ['/site/change-email'], ['class' => 'btn btn-xs']) ?>
    </p>
    </div>

    <div class="detail-view-container box-body" style="padding:0 10px">
    <h3 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Data Diri</h3>
    <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Edit Data Diri', ['update'], [
        'class' => 'btn btn-sm btn-warning',
    ]) ?>
    <p></p>
    <div class="detail-view-container" style="margin-bottom:10px">
    <?= DetailView::widget([
        'options' => ['class' => 'table detail-view'],
        'model' => $model,
        'attributes' => [
            'identity_number',
            // 'registration_number',
            'phone',
            'name',                
            [
                'label' => 'Tempat/Tgl Lahir',
                'value' => $model->place_of_birth . ', ' . Yii::$app->formatter->asDate($model->date_of_birth),
            ],
            [
                'attribute' => 'sex',
                'value' => $model->sexes($model->sex),
            ],
            [
                'attribute' => 'religion',
                'value' => $model->religions($model->religion),
            ],
            [
                'attribute' => 'education_level',
                'value' => $model->educationLevels($model->education_level),
            ],
            'family_number',
            'mother_name',
            [
                'attribute' => 'nationality',
                'value' => $model->nationalities($model->nationality),
            ],
            [
                'label' => 'Tinggi/Berat Badan',
                'value' => $model->height . 'cm / ' . $model->weight . 'kg',
            ],
            [
                'attribute' => 'marital_status',
                'value' => $model->maritalStatuses($model->marital_status),
            ],
            [
                'attribute' => 'blood_type',
                'value' => $model->bloodTypes($model->blood_type),
            ],
            [
                'label' => 'Alamat sesuai KTP',
                'value' => $model->addressText,
            ],
            [
                'label' => 'Alamat domisili',
                'value' => $model->domicileText,
            ],
            [
                'label' => 'Rekening',
                'value' => $model->bank_name . ' ' . $model->bank_account,
            ],
        ],
    ]) ?>
    </div>
    </div>

    <div class="detail-view-container box-body" style="padding:0 10px">
    <h3 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Dokumen</h3>
    <div class="detail-view-container" style="margin-bottom:10px">
        <table class="table detail-view">
            <?= !$model->employeeFiles ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
            <?php foreach ($model->employeeFiles as $employeeFile) {?>
                <tr>
                    <th><?= Employee::fileNameLabels($employeeFile->name) ?></th>
                    <td><?= Html::a('Download', ['download', 'id' => $employeeFile->id], ['class' => 'btn btn-default btn-xs']) ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    </div>
    
    <div class="detail-view-container box-body" style="padding:0 10px">
    <h3 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Pendidikan</h3>
    <h4 style="margin-bottom:0; margin-top:15px">Formal</h4>
    <p class="text-muted small">SD, SMP, SMA, Perguruan Tinggi</p>
    <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-education-formal']), 'title' => 'Pendidikan Formal', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
    <p></p>
    <div class="detail-view-container" style="margin-bottom:10px">
        <table class="table table-hover detail-view">
        <?= !$model->employeeEducationsFormal ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
        <?php $i = 0; foreach ($model->employeeEducationsFormal as $education) : ?>
            <tr>
                <td style="width:1px; white-space: nowrap">
                    <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['form-education-formal', 'id' => $education->id]), 'title' => 'Edit Pendidikan Formal', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['education-delete', 'id' => $education->id], [
                        'title' => 'Delete', 
                        'class' => 'btn btn-xs btn-default btn-text-danger',
                        'data-method' => 'post',
                        'data-confirm' => 'Hapus?',
                    ]); ?>
                </td>
                <td style="width:1px; white-space: nowrap"><?= ++$i ?></td>
                <td>
                    <b><?= $education->name ?></b>, <span class="text-muted"><?= $education->place ?></span>
                    <br><span class="text-muted"><?= $education->year_start ?> - <?= $education->year_end ?></span> <?= $education->major ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
    <h4 style="margin-bottom:0; margin-top:15px">Informal</h4>
    <p class="text-muted small">Pelatihan, Kursus, Workshop, Sertifikasi, dll</p>
    <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-education-informal']), 'title' => 'Pendidikan Informal', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
    <p></p>
    <div class="detail-view-container" style="margin-bottom:10px">
        <table class="table table-hover detail-view">
        <?= !$model->employeeEducationsInformal ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
        <?php $i = 0; foreach ($model->employeeEducationsInformal as $education) : ?>
            <tr>
                <td style="width:1px; white-space: nowrap">
                    <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['form-education-informal', 'id' => $education->id]), 'title' => 'Edit Pendidikan Informal', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['education-delete', 'id' => $education->id], [
                        'title' => 'Delete', 
                        'class' => 'btn btn-xs btn-default btn-text-danger',
                        'data-method' => 'post',
                        'data-confirm' => 'Hapus?',
                    ]); ?>
                </td>
                <td style="width:1px; white-space: nowrap"><?= ++$i ?></td>
                <td>
                    <b><?= $education->name ?></b>, <span class="text-muted"><?= $education->place ?></span>
                    <br><span class="text-muted"><?= $education->year_start ?> - <?= $education->year_end ?></span> <?= $education->remark ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
    </div>

    <div class="detail-view-container box-body" style="padding:0 10px">
    <h3 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Keluarga</h3>
    <h4 style="margin-bottom:0; margin-top:15px">Keluarga Inti</h4>
    <p class="text-muted small">Data Keluarga dimana anda sebagai orang tua / suami / istri. Masukkan data semua anggota keluarga termasuk anda.</p>
    <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-family-self']), 'title' => 'Keluarga Inti', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
    <p></p>
    <div class="detail-view-container" style="margin-bottom:10px">
        <table class="table table-hover detail-view">
        <?= !$model->employeeFamiliesSelf ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
        <?php $i = 0; foreach ($model->employeeFamiliesSelf as $family) : ?>
            <tr>
                <td style="width:1px; white-space: nowrap">
                    <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['form-family-self', 'id' => $family->id]), 'title' => 'Edit Keluarga Inti', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['family-delete', 'id' => $family->id], [
                        'title' => 'Delete', 
                        'class' => 'btn btn-xs btn-default btn-text-danger',
                        'data-method' => 'post',
                        'data-confirm' => 'Hapus?',
                    ]); ?>
                </td>
                <td style="width:1px; white-space: nowrap"><?= ++$i ?></td>
                <td>
                    <b><?= $family->name ?></b>, <span class="text-muted"><?= $family->positions($family->position) ?> <?= $family->sequence ? 'ke ' . $family->sequence : '' ?></span>
                    <br><span class="text-muted"><?= $family->place_of_birth ?>, <?= Yii::$app->formatter->asDate($family->date_of_birth) ?></span> <?= $family->occupation ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
    <h4 style="margin-bottom:0; margin-top:15px">Keluarga Orang Tua</h4>
    <p class="text-muted small">Data Keluarga dimana anda sebagai anak. Masukkan data semua anggota keluarga termasuk anda.</p>
    <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-family-parent']), 'title' => 'Keluarga Orang Tua', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
    <p></p>
    <div class="detail-view-container" style="margin-bottom:10px">
        <table class="table table-hover detail-view">
        <?= !$model->employeeFamiliesParent ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
        <?php $i = 0; foreach ($model->employeeFamiliesParent as $family) : ?>
            <tr>
                <td style="width:1px; white-space: nowrap">
                    <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['form-family-parent', 'id' => $family->id]), 'title' => 'Edit Keluarga Orang Tua', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['family-delete', 'id' => $family->id], [
                        'title' => 'Delete', 
                        'class' => 'btn btn-xs btn-default btn-text-danger',
                        'data-method' => 'post',
                        'data-confirm' => 'Hapus?',
                    ]); ?>
                </td>
                <td style="width:1px; white-space: nowrap"><?= ++$i ?></td>
                <td>
                    <b><?= $family->name ?></b>, <span class="text-muted"><?= $family->positions($family->position) ?> <?= $family->sequence ? 'ke ' . $family->sequence : '' ?></span>
                    <br><span class="text-muted"><?= $family->place_of_birth ?>, <?= Yii::$app->formatter->asDate($family->date_of_birth) ?></span> <?= $family->occupation ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
    </div>
    
    <div class="detail-view-container box-body" style="padding:0 10px">
    <h3 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Experience</h3>
    <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-experience']), 'title' => 'Experience', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
    <p></p>
    <div class="detail-view-container" style="margin-bottom:10px">
        <table class="table table-hover detail-view">
        <?= !$model->employeeExperiences ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
        <?php $i = 0; foreach ($model->employeeExperiences as $experience) : ?>
            <tr>
                <td style="width:1px; white-space: nowrap">
                    <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['form-experience', 'id' => $experience->id]), 'title' => 'Edit Experience', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['experience-delete', 'id' => $experience->id], [
                        'title' => 'Delete', 
                        'class' => 'btn btn-xs btn-default btn-text-danger',
                        'data-method' => 'post',
                        'data-confirm' => 'Hapus?',
                    ]); ?>
                </td>
                <td style="width:1px; white-space: nowrap"><?= ++$i ?></td>
                <td>
                    <b><?= $experience->name ?></b>, <span class=""><?= $experience->position ?></span>
                    <br><span class="text-muted"><?= $experience->year_start ?>-<?= $experience->year_end ?> </span>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
    </div>
    
    <div class="detail-view-container box-body" style="padding:0 10px">
    <h3 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Emergency Contact</h3>
    <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-emergency']), 'title' => 'Emergency Contact', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
    <p></p>
    <div class="detail-view-container" style="margin-bottom:10px">
        <table class="table table-hover detail-view">
        <?= !$model->employeeEmergencies ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
        <?php $i = 0; foreach ($model->employeeEmergencies as $emergency) : ?>
            <tr>
                <td style="width:1px; white-space: nowrap">
                    <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['form-emergency', 'id' => $emergency->id]), 'title' => 'Edit Emergency Contact', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['emergency-delete', 'id' => $emergency->id], [
                        'title' => 'Delete', 
                        'class' => 'btn btn-xs btn-default btn-text-danger',
                        'data-method' => 'post',
                        'data-confirm' => 'Hapus?',
                    ]); ?>
                </td>
                <td style="width:1px; white-space: nowrap"><?= ++$i ?></td>
                <td>
                    <b><?= $emergency->name ?></b>, <span class="text-muted"><?= $emergency->relationship ?></span>
                    <br><span class="text-muted"><?= $emergency->phone ?></span> <?= $emergency->address ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
    </div>
    
</div>
