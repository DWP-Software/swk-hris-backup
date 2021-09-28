<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
	<nav class="navbar navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<a href="<?= Yii::$app->homeUrl ?>" class="navbar-brand"><?= Html::img('@web/img/logo-sm.png', ['style' => 'height:40px;margin-top:-8px']) ?></a>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<i class="fa fa-bars"></i>
				</button>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<!-- <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
				<ul class="nav navbar-nav">
					<li><?= Html::a('Beranda', ['/site/index']) ?></li>
					<li><?= Html::a('Kehadiran', ['/presence/index']) ?></li>
					<li><?= Html::a('Gaji', ['/payment/index']) ?></li>
				</ul>
			</div> -->
			<!-- /.navbar-collapse -->

			<!-- Navbar Right Menu -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<?php if (Yii::$app->user->isGuest) { ?>
					<li class=""><a href="<?= Url::to(['site/login']) ?>">Login</a></li>
					<li class=""><a href="<?= Url::to(['site/register']) ?>">Daftar</a></li>
					<?php } else { ?>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= Yii::$app->user->identity->email ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<!-- <li class="" style="text-align:right;">
	                            <?= Html::a(
	                                'Profile',
	                                ['/site/profile'],
	                                ['style' => 'padding:10px; color:#444']
	                            ) ?>
	                        </li> -->
							<li class="" style="text-align:right;">
	                            <?= Html::a(
	                                'Ganti Password',
	                                ['/site/change-password'],
	                                ['style' => 'padding:10px; color:#444']
	                            ) ?>
	                        </li>
	                        <li class="" style="text-align:right;">
	                            <?= Html::a(
	                                'Sign out',
	                                ['/site/logout'],
	                                ['style' => 'padding:10px; color:#444', 'data-method' => 'post']
	                            ) ?>
	                        </li>
						</ul>
					</li>

					<?php } ?>

				</ul>
			</div>
			<!-- /.navbar-custom-menu -->
		</div>
		<!-- /.container-fluid -->
	</nav>
</header>
