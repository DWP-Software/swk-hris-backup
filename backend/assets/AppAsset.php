<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/override.css',
        'css/addition.css',
    ];
    public $js = [
        'js/jquery.slimscroll.min.js',
        'js/ajax-handler.js',
        'js/ajax-modal-popup.js',
        'js/yii-overrides.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
