<?php

use common\models\entity\Employee;
use common\models\entity\Placement;
use common\models\entity\PlacementPlan;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Employee */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Employee', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-md-12">
        
        <?php /* echo Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Update', ['update', 'id' => $model->id], [
            'class' => 'btn btn-default btn-text-warning',
        ]) */ ?>
        <?= $model->placementPlans || $model->contracts ? null : '<p>'. Html::a('<i class="glyphicon glyphicon-trash"></i> ' . 'Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-default btn-text-danger',
            'data'  => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method'  => 'post',
            ],
        ]).'</p>' ?>
        
    </div>


    <div class="col-md-8">

        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Akun</h4>
            <!-- <p class="text-muted small">Alamat email berfungsi untuk login sistem SWK. Pastikan alamat email karyawan valid sehingga dapat melakukan reset password apabila lupa, juga menerima informasi terbaru dari SWK.</p> -->
            <div class="detail-view-container" style="margin-bottom:10px">
                <?= DetailView::widget([
                    'options' => ['class' => 'table detail-view'],
                    'model' => $model,
                    'attributes' => [
                        'user.email:text:Email',
                        [
                            'attribute' => 'registration_number',
                            'format' => 'raw',
                            'value' => (!$model->registration_number) ? 
                                Html::button(
                                    '<i class="fa fa-card"></i>&nbsp; Set NRK', [
                                        'value' => Url::to(['update-nrk', 'id' => $model->id]), 
                                        'title' => 'Set NRK', 'class' => 'showModalButton btn btn-xs btn-default'
                                    ], 
                                    ['class' => 'btn btn-xs btn-default']) : $model->registration_number,
                        ],
                    ],
                ]) ?>
            </div>
            <p class="small">
            Created at: <?= Yii::$app->formatter->asDate($model->user->created_at) ?>
            </p>
        </div>

        <div class="detail-view-container box-body" style="padding:0 10px">
        <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Data Diri</h4>
        <?php /* echo Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Edit Data Diri', ['update'], [
            'class' => 'btn btn-sm btn-warning',
        ]) */ ?>
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
        <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Dokumen</h4>
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
        <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Pendidikan</h4>
        <h4 style="margin-bottom:0; margin-top:15px">Formal</h4>
        <p class="text-muted small">SD, SMP, SMA, Perguruan Tinggi</p>
        <?php // echo Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-education-formal']), 'title' => 'Pendidikan Formal', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
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
        <?php // echo Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-education-informal']), 'title' => 'Pendidikan Informal', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
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
        <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Keluarga</h4>
        <h4 style="margin-bottom:0; margin-top:15px">Keluarga Inti</h4>
        <p class="text-muted small">Data Keluarga dimana karyawan sebagai orang tua / suami / istri. Data anggota keluarga termasuk karyawan.</p>
        <?php // echo Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-family-self']), 'title' => 'Keluarga Inti', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
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
        <p class="text-muted small">Data Keluarga dimana karyawan sebagai anak. Data anggota keluarga termasuk karyawan.</p>
        <?php // echo Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-family-parent']), 'title' => 'Keluarga Orang Tua', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
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
        <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">Emergency Contact</h4>
        <?php // echo Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['form-emergency']), 'title' => 'Emergency Contact', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
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
        <!-- end of employee data -->

    </div>
    <div class="col-md-4">
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
            RENCANA PENEMPATAN
            <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', [
                'value' => Url::to(['placement-plan-form', 'id' => $model->id]), 
                'title' => 'Tempatkan', 
                'class' => 'showModalButton btn btn-xs btn-success pull-right'
            ]) ?>
            </h4>
            <?= !$model->placementPlans ? '<div class="detail-view-container text-muted small bg-gray-light box-body" style="margin-bottom:10px"><i>Tidak ada data.</i></div>' : '' ?>
            <?php foreach ($model->placementPlans as $placementPlan) { ?>
                <div class="detail-view-container box-body" style="margin-bottom:10px">
                    <div class="panel-title">
                        <b><?= $placementPlan->client->name ?></b>
                        <div class="pull-right">
                            <?php if (!$placementPlan->contractPlacements) echo Html::a('<i class="fa fa-trash"></i>', ['placement-plan-delete', 'placement_plan_id' => $placementPlan->id], [
                                'class' => 'btn btn-xs btn-default btn-text-danger', 
                                'data-method' => 'post', 
                                'data-confirm' => 'Hapus?',
                            ]) ?> 
                            <?= Html::button('<i class="fa fa-pencil"></i>', [
                                'value' => Url::to(['placement-plan-form', 'id' => $model->id, 'placement_plan_id' => $placementPlan->id]), 
                                'title' => 'Ubah Penempatan', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning'
                            ], 
                            ['class' => 'btn btn-xs btn-default']) ?>
                        </div>
                    </div>
                    <div><span class="text-muted"><?= Yii::$app->formatter->asDate($placementPlan->submitted_at) ?> : </span> Diajukan </div>
                    <div><span class="text-muted"><?= Yii::$app->formatter->asDate($placementPlan->responded_at) ?> : </span> <?= $placementPlan->responseTypeText ?> </div>
                    
                    <?php /* if ($placementPlan->contracts) { ?>
                        <p></p>KONTRAK
                        <?php foreach ($placementPlan->contracts as $contract) { ?>
                            <div style="margin-bottom:5px">
                                <?= Html::a('<i class="fa fa-eye"></i>', ['contract-view', 'contract_id' => $contract->id], ['class' => 'btn btn-xs btn-default btn-text-info']) ?>
                                <?= Html::a('<i class="fa fa-pencil"></i>', ['contract-update', 'contract_id' => $contract->id], ['class' => 'btn btn-xs btn-default btn-text-warning']) ?>
                                <?= Html::a('<i class="fa fa-trash"></i>', ['contract-delete', 'contract_id' => $contract->id], [
                                    'class' => 'btn btn-xs btn-default btn-text-danger', 
                                    'data-method' => 'post', 
                                    'data-confirm' => 'Hapus?',
                                ]) ?> &nbsp;
                                <span class="text-muted"><?= Yii::$app->formatter->asDate($contract->started_at) ?> - <?= Yii::$app->formatter->asDate($contract->ended_at) ?> : </span> <?= $contract->employeeType->name ?> - <?= $contract->position ?>
                            </div>
                        <?php } ?>
                    <?php } */ ?>
                    <!-- <p></p> -->
                    <?php // if ($placementPlan->response_type == PlacementPlan::RESPONSE_ACCEPTED && !$placementPlan->contracts) echo Html::a('Buat Kontrak', ['contract-create', 'placementPlan_id' => $placementPlan->id], ['class' => 'btn btn-sm btn-default']) ?>
                    <?php // if ($placementPlan->response_type == PlacementPlan::RESPONSE_ACCEPTED && $placementPlan->contracts) echo Html::a('Perpanjang Kontrak', ['contract-create', 'placementPlan_id' => $placementPlan->id], ['class' => 'btn btn-sm btn-default']) ?>
                </div>
            <?php } ?>
        </div>

        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="text-transform:uppercase; background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">
                KONTRAK
                <?= Html::a('<i class="fa fa-plus"></i>&nbsp; Tambah', ['contract-create', 'employee_id' => $model->id], ['class' => 'btn btn-xs btn-success pull-right']) ?>
            </h4>
            <?= !$model->contracts ? '<div class="detail-view-container text-muted small bg-gray-light box-body" style="margin-bottom:10px"><i>Tidak ada data.</i></div>' : '' ?>
            <?php foreach ($model->contracts as $contract) { ?>
                <div class="detail-view-container box-body" style="margin-bottom:10px">
                    <div class="pull-right">
                        <!-- 
                        <?= Html::a('<i class="fa fa-trash"></i>', ['contract-delete', 'contract_id' => $contract->id], [
                            'class' => 'btn btn-xs btn-default btn-text-danger', 
                            'data-method' => 'post', 
                            'data-confirm' => 'Hapus?',
                        ]) ?>
                        <?= Html::a('<i class="fa fa-pencil"></i>', ['contract-update', 'contract_id' => $contract->id], ['class' => 'btn btn-xs btn-default btn-text-warning']) ?>
                         -->
                         <?= Html::a('<i class="fa fa-eye"></i> Detail', ['contract-view', 'contract_id' => $contract->id], ['class' => 'btn btn-xs btn-default btn-text-info']) ?>                        
                    </div>
                    <div class="panel-title">
                        <b><?= $contract->id .': '. $contract->employeeType->description ?></b>
                    </div>
                    <span class="text-muted"><?= Yii::$app->formatter->asDate($contract->started_at) ?> - <?= Yii::$app->formatter->asDate($contract->ended_at) ?> </span>
                </div>
            <?php } ?>
        </div>


        <?php 
        /* $dataProvider = new \yii\data\ActiveDataProvider([
            'query'      => Placement::find()->where(['employee_id' => $model->id]),
            'pagination' => false,
            'sort'       => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        echo \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            // 'pjax' => true,
            'hover'        => true,
            'striped'      => false,
            'bordered'     => false,
            'panel'        => false,
            'summary'      => false,
            'pjaxSettings' => ['options' => ['id' => 'grid']],
            // 'filterModel' => $searchModel,
            'columns' => [
                [
                    'class'          => 'yii\grid\SerialColumn',
                    'headerOptions'  => ['class' => 'text-right'],
                    'contentOptions' => ['class' => 'text-right'],
                ],
                'client.name:text:Mitra',
                'submitted_at:date',
                'responded_at:date',
                'processStatus:text:Hasil',
            ],
        ]); */
        ?>
    </div>
</div>
