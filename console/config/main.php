<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'layout' => null,
            'ignoreActions' => [
                'audit/*', 
                'debug/*', 
                'area/*', 
                'log/*',
                'gii/*',
            ],
            'accessRoles' => ['superuser'],
            'userIdentifierCallback' => ['common\models\entity\User', 'userIdentifierCallback'],
            'userFilterCallback' => ['common\models\entity\User', 'filterByUserIdentifierCallback'],
            'panels' => [
                'audit/request',
                'audit/trail',
                'audit/mail',
                'audit/error',
                'audit/javascript',
            ],
            'panelsMerge' => [
                'audit/curl' => [
                    'log'     => true,
                    'headers' => true,
                    'content' => true,
                ],
            ],
        ],
    ],
];
