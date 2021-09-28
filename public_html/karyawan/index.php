<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../../employee/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../../employee/config/main.php',
    require __DIR__ . '/../../employee/config/main-local.php'
);

require __DIR__ . '/../../common/config/functions.php';

\Yii::$container->set('yii\data\Pagination', [
    'pageSize' => 10,
]);

(new yii\web\Application($config))->run();
