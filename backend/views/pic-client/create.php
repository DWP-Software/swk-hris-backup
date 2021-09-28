<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\entity\PicClient */

$this->title = 'Create Pic Client';
$this->params['breadcrumbs'][] = ['label' => 'Pic Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-client-create">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
    
</div>
