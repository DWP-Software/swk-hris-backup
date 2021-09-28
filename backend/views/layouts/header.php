<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><big><i class="fa fa-bug"></i></big></span><span class="logo-lg"><b>'. Yii::$app->name .'</b> / <small> internal</small></span>', Yii::$app->homeUrl, ['class' => 'logo hidden-xs']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        
        <?= Html::a('<span class="logo-lg"<b>'. Yii::$app->name .'</b></span>', Yii::$app->homeUrl, ['class' => 'visible-xs', 'style' => 'color:#fff; padding:10px; font-size:18px; font-weight:bold !important; position:absolute; margin-left: 35px;']) ?>

        <!-- <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a> -->

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- User Account: style can be found in dropdown.less -->
				<?php if (!Yii::$app->user->isGuest) { ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Url::base().'/img/user.jpg' ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= !Yii::$app->user->isGuest ? Yii::$app->user->identity->email : '' ?></span> &nbsp;<i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="" style="text-align: right">
                            <?= Html::a(
                                'Ganti Password',
                                ['/site/change-password'],
                                ['style' => 'padding:10px; color:#444']
                            ) ?>
                        </li>
                        <li class="" style="text-align: right">
                            <?= Html::a(
                                'Sign out',
                                ['/site/logout'],
                                ['style' => 'padding:10px; color:#444', 'data-method' => 'post']
                            ) ?>
                        </li>
                    </ul>
				</li>
				<?php } ?>

                <!-- <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li> -->
            </ul>
        </div>
    </nav>
</header>
