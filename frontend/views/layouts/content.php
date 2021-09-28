<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\helpers\Html;

?>
<div class="content-wrapper" style="padding-bottom:30px">
    <div class="container">
    <div class="row">
    <section class="content-header">
        <?php if (Yii::$app->params['showTitle']) { ?>
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1 class="hidden-xs" style="width:70%"><?= $this->blocks['content-header'] ?></h1>
            <h1 class="visible-xs"><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1 class="hidden-xs" style="width:70%">
                <?php
                if ($this->title !== null) {
                    // echo \yii\helpers\Html::encode($this->title);
                    echo $this->title;
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
            <h1 class="visible-xs">
                <?php
                if ($this->title !== null) {
                    // echo \yii\helpers\Html::encode($this->title);
                    echo $this->title;
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'encodeLabels' => false,
            ]
        ) ?>
        <?php } ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
    </div>
    </div>
</div>

<footer class="main-footer">
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <?= Html::img('@web/img/logo.png') ?>
            </div>
            <div class="col-md-6">
                <br>
                <!-- <b><big>PT SALAM WADAH KARYA</big></b>
                <p>HR Management, Real Estate Management and Services</p> -->
            </div>
        </div>
    </div>
</footer>
