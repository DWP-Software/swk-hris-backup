<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

Yii::$app->params['showTitle'] = false;
$this->title = $name;
?>

<center>

<h3><?= Html::encode($name) ?></h3>

        <div style="padding:10px; border-radius: 4px; border: 1px solid pink; background: white; color:darkred; margin: 20px 0px;">
            <big><?= nl2br(Html::encode($message)) ?></big>
        </div>

        <div style="margin: 0 0px">
            <p class="">
                The above error occurred while the Web server was processing your request.
                Please contact your system administrator if you think this is a server error. 
                <br>Thank you.
            </p>
            <p class="">
                Meanwhile, you may <a href='<?= Yii::$app->homeUrl ?>'>return to dashboard</a>.
            </p>
        </div>
</center>