<?php

use common\models\entity\ClientAgreement;
use common\models\entity\ClientUser;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Client */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mitra', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="client-view box-- box-info--">

    <div class="box-body--">
        <p>
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Update', ['update', 'id' => $model->id], [
            'class' => 'btn btn-warning',
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i> ' . 'Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        </p>

        <div class="detail-view-container">
        <?= DetailView::widget([
            'options' => ['class' => 'table detail-view'],
            'model' => $model,
            'attributes' => [
                // 'id',
                'name',
                'address:ntext',
                'phone',
                [
                    'attribute' => 'file_logo',
                    'format' => 'raw',
                    'value' => $model->file_logo ? Html::img(['download', 'id' => $model->id], ['height' => '20px']) : '',
                ],
                // 'created_at:datetime',
                // 'updated_at:datetime',
                // 'createdBy.username:text:Created By',
                // 'updatedBy.username:text:Updated By',
            ],
        ]) ?>
        </div>


        <div class="detail-view-container box-body" style="padding:0 10px">
        <h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">MoU</h4>
        <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['agreement-form', 'client_id' => $model->id]), 'title' => 'MoU', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
        <p></p>
        <?php $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => ClientAgreement::find()->where(['client_id' => $model->id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]); ?>
        <div class="detail-view-container" style="margin-bottom:10px">
            <table class="table table-hover detail-view">
            <?= !$dataProvider->models ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
            <?php $i = 0; foreach ($dataProvider->models as $agreement) : ?>
                <tr>
                    <td style="width:1px; white-space: nowrap">
                        <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['agreement-form', 'client_id' => $model->id, 'id' => $agreement->id]), 'title' => 'Edit MoU', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['agreement-delete', 'id' => $agreement->id], [
                            'title' => 'Delete', 
                            'class' => 'btn btn-xs btn-default btn-text-danger',
                            'data-method' => 'post',
                            'data-confirm' => 'Hapus?',
                        ]); ?>
                    </td>
                    <!-- <td style="width:1px; white-space: nowrap"><?= ++$i ?></td> -->
                    <td>
                        <b><?= $agreement->document_number ?></b><br>
                        <?= Yii::$app->formatter->asDate($agreement->started_at) ?> &nbsp; <i class="fa fa-arrow-right text-muted"></i> &nbsp; <?= Yii::$app->formatter->asDate($agreement->ended_at) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>
        </div>


        <div class="detail-view-container box-body" style="padding:0 10px">
        <h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">USER</h4>
        <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['user-form', 'client_id' => $model->id]), 'title' => 'User', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
        <p></p>
        <?php $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => ClientUser::find()->joinWith(['user'])->where(['client_id' => $model->id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]); ?>
        <div class="detail-view-container" style="margin-bottom:10px">
            <table class="table table-hover detail-view">
            <?= !$dataProvider->models ? '<tr><td class="text-muted small bg-gray-light"><i>Tidak ada data.</i></tr></td>' : '' ?>
            <?php $i = 0; foreach ($dataProvider->models as $user) : ?>
                <tr>
                    <td style="width:1px; white-space: nowrap">
                        <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', ['value' => Url::to(['user-form', 'client_id' => $model->id, 'id' => $user->id]), 'title' => 'Edit User', 'class' => 'showModalButton btn btn-xs btn-default btn-text-warning']); ?>
                        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['user-delete', 'id' => $user->id], [
                            'title' => 'Delete', 
                            'class' => 'btn btn-xs btn-default btn-text-danger',
                            'data-method' => 'post',
                            'data-confirm' => 'Hapus?',
                        ]); ?>
                    </td>
                    <!-- <td style="width:1px; white-space: nowrap"><?= ++$i ?></td> -->
                    <td>
                        <b><?= $user->user->email ?></b>
                        <br><?= $user->user->statuses($user->user->status) ?>  <span class="text-muted">registered since <?= Yii::$app->formatter->asDate($user->user->created_at) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>
        </div>


    </div>
</div>
