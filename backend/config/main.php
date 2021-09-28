<?php
use kartik\datecontrol\Module;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'SWK-backend',
    'name' => 'SWK',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
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
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
            'displaySettings' => [
                Module::FORMAT_DATE => 'dd MMM yyyy',
                Module::FORMAT_TIME => 'hh:mm:ss a',
                Module::FORMAT_DATETIME => 'dd-MMM-yyyy hh:mm', 
            ],
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:Y-m-d',
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:U',
            ],
            'displayTimezone' => 'Asia/Jakarta',
            'saveTimezone' => 'UTC',
            'autoWidget' => true,
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['type' => 2, 'pluginOptions' => ['autoclose' => true]], 
                Module::FORMAT_DATETIME => ['pluginOptions' => ['autoclose' => true]], 
                Module::FORMAT_TIME => ['pluginOptions' => ['autoclose' => true]], 
            ],
            // custom widget settings that will be used to render the date input instead of kartik\widgets,
            // this will be used when autoWidget is set to false at module or widget level.
            'widgetSettings' => [
                Module::FORMAT_DATE => [
                    'class' => 'kartik\widgets\DatePicker', 'type' => 1
                ]
            ]                    
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\entity\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'SWK-backend',
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
            'class' => '\bedezign\yii2\audit\components\web\ErrorHandler',
        ],        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // 'defaultRoles' => ['superuser'],
            // 'cache' => 'cache',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@mdm/admin/views' => '@backend/views/_acf',
                    '@vendor/bedezign/yii2-audit/src/views' => '@backend/views/_audit',
                ],
            ],
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
            // 'acf/*',
            // '*',
        ]
    ],
];
