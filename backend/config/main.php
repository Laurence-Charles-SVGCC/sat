<?php

//    use \yii\web\Request;
//    $baseUrl = str_replace('/backend/web', '/backend/web', (new Request)->getBaseUrl());
//    $frontEndBaseUrl = str_replace('/backend/web', '/frontend/web', (new Request)->getBaseUrl());

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
    
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => false,
//            'baseUrl' => $baseUrl,
            'baseUrl' => '/backend/web',
        ],        
        'urlManagerFrontEnd' => [
            'class' => 'yii\web\urlManager',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
//            'baseURL' => $frontEndBaseUrl,
            'baseUrl' => '/frontend/web',
        ],
        
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
