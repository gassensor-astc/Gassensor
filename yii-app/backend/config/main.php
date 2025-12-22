<?php

use yii\filters\AccessControl;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$accessRules = [
    ['allow' => true, 'roles' => [ROLE_NAME_DEVELOPER]],

    //deny developer actions
    ['allow' => false, 'actions' => ['fix-filename',],],

    //allow admin
    ['allow' => true, 'roles' => [ROLE_NAME_ADMIN], 'controllers' => [
        'slider', 'applications', 'site', 'news', 'user', 'manufacture', 'product', 'seo', 'gaz', 'order', 'page', 'redirect', 'measurement-type', 'url', 'setting', 'ajax'
    ]],

    ['allow' => true, 'roles' => [ROLE_NAME_MANAGER], 'controllers' => [
        'slider', 'applications', 'site', 'news', 'manufacture', 'product', 'seo', 'gaz', 'order', 'page', 'redirect', 'measurement-type', 'url', 'ajax'
    ]],

    //allow all
    ['allow' => true, 'controllers' => ['site'], 'actions' => ['error',],],

];

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
        ],
        'session' => [
            'name' => 'sessid',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'rules' => [
                'seo/robots' => 'seo/robots',
                'PUT,POST seo/update-robots' => 'seo/update-robots',
                'seo/sitemap' => 'seo/sitemap',
                'seo/upload-sitemap' => 'seo/upload-sitemap',
            ],
        ],
    ],
    'params' => $params,

    'as access' => [
        'class' => AccessControl::class,
        'rules' => $accessRules,
    ],

];
