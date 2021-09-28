<?php

use common\models\entity\PicClient;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\User */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view box-- box-info--">

    <div class="box-body--">
        <p>
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> '. 'Update', ['update', 'id' => $model->id], [
            'class' => 'btn btn-warning',
        ]) ?>
        <?php echo Html::a('<i class="glyphicon glyphicon-trash"></i> ' . 'Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if (Yii::$app->user->can('superuser') && $model->id != Yii::$app->user->id) echo Html::a('<i class="glyphicon glyphicon-user"></i> ' . 'Impersonate', ['impersonate', 'id' => $model->id], [
            'class' => 'btn btn-text-info pull-right',
            'data' => [
                'confirm' => 'Are you sure you want to impersonate this user?',
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
                // 'username',
                // 'auth_key',
                // 'password_hash',
                // 'password_reset_token',
                'email:email',
                'name',
                [
                    'attribute' => 'status',
                    'value' => $model->statuses($model->status),
                ],
                'roles',
                'created_at:datetime',
                // 'updated_at:datetime',
                // 'verification_token',
            ],
        ]) ?>
        </div>

        <?php 
            $flag_presence = 0;
            $flag_finance  = 0;
            foreach ($model->authAssignments as $role) { 
                if ($role->item_name == 'Kehadiran') { 
                    $flag_presence = 1;
                } 
                if ($role->item_name == 'Keuangan') { 
                    $flag_finance = 1;
                } 
            } 
        ?>

<?php if ($flag_presence) { ?>
        
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">PIC Kehadiran</h4>
            <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['pic-client-form-presence', 'user_id' => $model->id]), 'title' => 'Tambah PIC Kehadiran', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
            <p></p>
            <?php $dataProvider = new \yii\data\ActiveDataProvider([
                'query' => PicClient::find()->where(['user_id' => $model->id, 'role' => 'Kehadiran']),
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
                <?php $i = 0; foreach ($dataProvider->models as $picClient) : ?>
                    <tr>
                        <td style="width:1px; white-space: nowrap">
                            <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['pic-client-delete', 'id' => $picClient->id], [
                                'title' => 'Delete', 
                                'class' => 'btn btn-xs btn-default btn-text-danger',
                                'data-method' => 'post',
                                'data-confirm' => 'Hapus?',
                            ]); ?>
                        </td>
                        <!-- <td style="width:1px; white-space: nowrap"><?= ++$i ?></td> -->
                        <td>
                            <b><?= $picClient->client->name ?></b><br>
                            <?= $picClient->client->address ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>

    <?php } ?>

    <?php if ($flag_finance) { ?>
        
        <div class="detail-view-container box-body" style="padding:0 10px">
            <h4 style="background:#f4f4f4; padding:10px; margin:0 -10px 10px -10px">PIC Keuangan</h4>
            <?= Html::button('<i class="fa fa-plus"></i>&nbsp; Tambah', ['value' => Url::to(['pic-client-form-finance', 'user_id' => $model->id]), 'title' => 'Tambah PIC Keuangan', 'class' => 'showModalButton btn btn-xs btn-success']); ?>
            <p></p>
            <?php $dataProvider = new \yii\data\ActiveDataProvider([
                'query' => PicClient::find()->where(['user_id' => $model->id, 'role' => 'Keuangan']),
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
                <?php $i = 0; foreach ($dataProvider->models as $picClient) : ?>
                    <tr>
                        <td style="width:1px; white-space: nowrap">
                            <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['pic-client-delete', 'id' => $picClient->id], [
                                'title' => 'Delete', 
                                'class' => 'btn btn-xs btn-default btn-text-danger',
                                'data-method' => 'post',
                                'data-confirm' => 'Hapus?',
                            ]); ?>
                        </td>
                        <!-- <td style="width:1px; white-space: nowrap"><?= ++$i ?></td> -->
                        <td>
                            <b><?= $picClient->client->name ?></b><br>
                            <?= $picClient->client->address ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>

    <?php } ?>
    
    </div>
</div>
