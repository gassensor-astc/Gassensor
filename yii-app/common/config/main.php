<?php

defined('ROLE_NAME_ADMIN') or define('ROLE_NAME_ADMIN', 'admin');
defined('ROLE_NAME_MANAGER') or define('ROLE_NAME_MANAGER', 'manager');
defined('ROLE_NAME_DEVELOPER') or define('ROLE_NAME_DEVELOPER', 'developer');

$i18n = [];
foreach (['app', 'seo', 'order',] as $v) {
    $i18n['translations'][$v] = [
        'class' => 'yii\i18n\PhpMessageSource',
        'basePath' => '@common/messages',
        'forceTranslation' => true,
    ];
}

return [
    'name' => 'gas sensor',
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'components' => [

        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            //'enableSchemaCache' => true,
        ],

        'i18n' => $i18n,

        'assetManager' => [
            'appendTimestamp' => true,
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

        'user' => [
            'class' => 'common\components\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
            'loginUrl' => '/site/login',
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'formatter' => [
            //'class' => 'common\components\CustomFormatter',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss',
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'HH:mm:ss',
        ],

        'log' => [
            //'traceLevel' => YII_DEBUG ? 3 : 0,
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => [], //context off
                ]
            ],
        ],

        'cart' => [
            'class' => 'common\components\cart\Cart'
        ],

    ],

    'container' => [
        'definitions' => [
            \yii\widgets\LinkPager::class => \yii\bootstrap5\LinkPager::class,
        ],
    ],

    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],

   /*
    'on beforeRequest' => function ($event) {
        if(!Yii::$app->request->isSecureConnection){
            $url = Yii::$app->request->getAbsoluteUrl();
            $url = str_replace('http:', 'https:', $url);
            Yii::$app->getResponse()->redirect($url);
            Yii::$app->end();
        }

    },
   */
];
