<?php
$params = array_merge(
    require __DIR__ . '../../common/config/params.php',
    require __DIR__ . '../../common/config/params-local.php'
);

return [
    'id' => 'api-app',
    'basePath' => __DIR__,
    'controllerNamespace' => 'api\controllers',
    'aliases' => [
        '@api' => __DIR__,
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
        'user' => [
            'identityClass' => 'common\models\entity\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCsrfCookie' => false,
        ],
        /* 'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ], */
        /* 'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // 'cache' => 'cache',
        ], */
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['check'],
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['auth'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        // 'POST login-by-phone' => 'login-by-phone',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['employee'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET profile'  => 'profile',
                        'GET presence' => 'presence',
                        'GET salary'   => 'salary',
                        'GET print'    => 'print',
                    ],
                ],

                /* [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['order-detail'],
                    'ruleConfig' => [
                        'class' => 'yii\web\UrlRule',
                        'defaults' => [
                            'expand' => 'orderItems',
                        ]
                    ],
                ], */
            ],
        ],
    ],
];
 
