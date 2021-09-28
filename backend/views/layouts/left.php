<?php 
    use yii\helpers\Url;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <?php if (!Yii::$app->user->isGuest) { ?>
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Url::base().'/img/user.jpg' ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->name ?></p>

                <a href="#"><i class="circle text-success"></i> <?= Yii::$app->user->identity->roles ?></a>
            </div>
        </div>
        <?php } ?>

        <?php   
            $menuItems = [
                ['label' => '<b>MENU</b>', 'encode' => false, 'options' => ['class' => 'header']],
                ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                // ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site/index']],
                
                // ['label' => 'Mitra', 'icon' => '', 'url' => ['/client/index']],
                [
                    'label' => 'Mitra',
                    'icon' => '',
                    'url' => '#',
                    'options' => ['class' => 'treeview'],
                    'items' => [
                        ['label' => 'Semua',                    'url' => ['/client/index']],
                        ['label' => 'MoU berakhir dlm 1 bulan', 'url' => ['/client/index-agreement-ending']],
                        ['label' => 'MoU Kadaluarsa',           'url' => ['/client/index-agreement-expired']],
                    ],
                ],
                ['label' => 'Calon Karyawan', 'icon' => '', 'url' => ['/employee-pre/index']],
                /* [
                    'label' => 'Calon Karyawan',
                    'icon' => '',
                    'url' => '#',
                    'options' => ['class' => 'treeview'],
                    'items' => [
                        ['label' => 'Semua',                'url' => ['/employee/index-pre']],
                        ['label' => 'Belum Ditempatkan',    'url' => ['/employee/index-pre-unplaced']],
                        ['label' => 'Menunggu',             'url' => ['/employee/index-pre-waiting']],
                        ['label' => 'Diterima',             'url' => ['/employee/index-pre-accepted']],
                        ['label' => 'Ditolak',              'url' => ['/employee/index-pre-rejected']],
                    ],
                ], */
                [
                    'label' => 'Karyawan',
                    'icon' => '',
                    'url' => '#',
                    'options' => ['class' => 'treeview'],
                    'items' => [
                        ['label' => 'Semua',                        'url' => ['/employee/index']],
                        ['label' => 'Menunggu Kontrak',             'url' => ['/employee/index-contract-waiting']],
                        ['label' => 'Sedang Ttd Kontrak',           'url' => ['/employee/index-contract-opened']],
                        ['label' => 'Dalam Kontrak',                'url' => ['/employee/index-contract-closed']],
                        ['label' => 'Kontrak berakhir dlm 1 bulan', 'url' => ['/employee/index-contract-ending']],
                        ['label' => 'Kontrak Kadaluarsa',           'url' => ['/employee/index-contract-expired']],
                    ],
                ],
                ['label' => 'Kehadiran', 'icon' => '', 'url' => ['/presence/index']],
                ['label' => 'Pembayaran Gaji', 'icon' => '', 'url' => ['/payroll/index']],
                // ['label' => 'User Account', 'icon' => '', 'url' => ['/user/index']],
                [
                    'label' => 'User Account',
                    'icon' => '',
                    'url' => '#',
                    'options' => ['class' => 'treeview'],
                    'items' => [
                        ['label' => 'Internal', 'url' => ['/user-internal/index']],
                        ['label' => 'Mitra',    'url' => ['/user-client/index']],
                        ['label' => 'Karyawan', 'url' => ['/user-employee/index']],
                    ],
                ],
                // ['label' => 'Penempatan', 'icon' => '', 'url' => ['/placement/index']],
                // ['label' => 'Kontrak', 'icon' => '', 'url' => ['/placement-contract/index']],
                
                // ['label' => 'User', 'icon' => 'user', 'url' => ['/user/index'], /* 'visible' => Yii::$app->user->can('superuser') */],
                /* [
                    'label' => 'Access Control',
                    'icon' => 'lock',
                    'url' => '#',
                    'options' => ['class' => 'treeview'],
                    'visible' => Yii::$app->user->can('superuser'),
                    'items' => [
                        ['label' => 'User',         'url' => ['/user/index']],
                        ['label' => 'Assignment',   'url' => ['/acf/assignment']],
                        ['label' => 'Role',         'url' => ['/acf/role']],
                        ['label' => 'Permission',   'url' => ['/acf/permission']],
                        ['label' => 'Route',        'url' => ['/acf/route']],
                        ['label' => 'Rule',         'url' => ['/acf/rule']],
                    ],
                ], */
                // ['label' => 'Log', 'icon' => 'clock-o', 'url' => ['/log/index'],'visible' => Yii::$app->user->can('superuser')],
            ];

            $menuItems = mdm\admin\components\Helper::filter($menuItems);
        ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $menuItems,
            ]
        ) ?>
        
        <!-- <ul class="sidebar-menu"><li><a href="<?= \yii\helpers\Url::to(['site/logout']) ?>" data-method="post"><i class="sign-out"></i>  <span>Logout</span></a></li></ul> -->
    
    </section>

</aside>
