<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
          ],
//        'urlManager' => [
//            'enablePrettyUrl' => false,
//            'showScriptName' => false,
//          ]
    ],
    'as beforeRequest' => [
        'class' => 'frontend\components\CheckApplicationSettings',
        'class' => 'frontend\components\CheckLoginStatus',
    ],
    'modules' => [
        'gridview' =>  [
             'class' => '\kartik\grid\Module'
             // enter optional module parameters below - only if you need to  
             // use your own export download action or custom translation 
             // message source
             // 'downloadAction' => 'gridview/export/download',
             // 'i18n' => []
         ]
     ],
];
