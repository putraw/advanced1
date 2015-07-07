<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
         'js/leaflet/Control.MiniMap.css',
       'js/leaflet/leaflet.css', 
       'js/sidebar/L.Control.Sidebar.css',
        'js/MousePosition/L.Control.MousePosition.css',
        'js/marker/dist/leaflet.awesome-markers.css',
        'css/site.css',
        'css/site2.css', 
    ];
    public $js = [   
      'js/leaflet/leaflet.js',
    'js/leaflet/Control.MiniMap.js',
    'js/html2canvas/html2canvas.js',   
     'js/sidebar/L.Control.Sidebar.js',
     'js/chart/Chart.js',
     'js/marker/dist/leaflet.awesome-markers.js',
     'js/selectize/js/jqueryui.js',
     'js/selectize/js/index.js',
     'js/selectize/js/selectize.js',
     'js/selectize/js/es5.js',
        'js/nusantaragis.js',
        'js/MousePosition/L.Control.MousePosition.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
