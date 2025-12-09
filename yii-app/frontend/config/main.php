<?php

use common\helpers\MyBehavior;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'session' => [
            'name' => 'sessid',
        ],
        'urlManager' => [
            'rules' => [
                'catalog/manufacture/<slug>' => 'catalog/manufacture',
                'news/page/<page:\d+>' => 'news/index',
                'news/<slug>' => 'news/slug',
                'ajax/mainnews' => 'ajax/mainnews',
                'manufacture/<slug>' => 'manufacture/slug',
                'applications/page/<page:\d+>' => 'applications/index',
                'remains/page/<page:\d+>' => 'remains/index',
                'applications/<slug>' => 'applications/slug',
                'product/<slug>' => 'product/slug',
                'catalog/<page:\d+>' => 'catalog/index',
                'catalog/<slugGaz>/<slug>' => 'product/slug',
                'catalog/<slug>' => 'catalog/gas',
                'catalog' => 'catalog/index',
                'page/<slug>' => 'page/index',
                '/<page:\d+>' => 'site/index',

                [
                    'pattern' => 'products',
                    'route' => 'redirector/index',
                    'defaults' => ['url' => '/catalog',],
                ], [
                    'pattern' => 'sensors',
                    'route' => 'redirector/index',
                    'defaults' => ['url' => '/manufacture',],
                ],
            ],
        ],
    ],
    'params' => $params,

    'as redirector' => [
        'class' => MyBehavior::class,
    ],

];
