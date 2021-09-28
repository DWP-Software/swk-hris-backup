<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'id-ID',
            'nullDisplay' => '',
            'dateFormat' => 'php:d F Y',
        ],
    ],
    'timeZone' => 'Asia/Jakarta',
];
