<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'SWK-frontend',
    'name' => 'SWK',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => '/employee/index',
    'language' => 'id-ID',
    'params' => $params,
    'modules' => [
        'acf' => [
            'class' => 'mdm\admin\Module',
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'layout' => null,
            'ignoreActions' => [
                'audit/*', 
                'debug/*', 
                'area/*', 
                'log/*',
            ],
            'accessRoles' => ['superuser', 'admin', 'Administrator'],
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
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\entity\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'SWK-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // 'defaultRoles' => ['superuser'],
            // 'cache' => 'cache',
        ],        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],       
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'area/*',
            'site/*',
            'gridview/*',
            'datecontrol/*',
            // 'debug/*',
            'acf/*',
            // '*',
        ]
    ],
];
