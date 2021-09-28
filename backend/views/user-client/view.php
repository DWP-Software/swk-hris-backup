<?php

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
    </div>
</div>
