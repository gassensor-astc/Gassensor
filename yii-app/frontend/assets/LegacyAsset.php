<?php
/**
 *
 * @since 2021-11-26 14:34
 */
namespace frontend\assets;

use yii\web\AssetBundle;

class LegacyAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/old.css?v=5',
        'admin/css/font-awesome.min.css'
    ];
    public $js = [
        'js/old.js?v=2',
        //'js/scripts.js',
    ];

}
