<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
    //App's Modules.
    'modules' => [
        //Custom built modules for subcomponents
        'subcomponents' => [
            'class' => 'app\subcomponents\SubcomponentsModule',
            'modules' =>[
                'admissions' => [
                    'class' => 'app\subcomponents\admissions\AdmissionsModule'
                 ],
                'applications' => [
                    'class' => 'app\subcomponents\applications\ApplicationsModule'
                 ],
                'general' => [
                    'class' => 'app\subcomponents\general\General'
                 ],
                'gradebook' => [
                    'class' => 'app\subcomponents\gradebook\GradebookModule',
                ],
                'graduation' => [
                    'class' => 'app\subcomponents\graduation\GraduationModule',
                ],
                'legacy' => [
                    'class' => 'app\subcomponents\legacy\Module',
                ],
                'payments' => [
                    'class' => 'app\subcomponents\payments\PaymentsModule',
                ],
                'programmes' => [
                    'class' => 'app\subcomponents\programmes\ProgrammesModule',
                ],
                'registry' => [
                    'class' => 'app\subcomponents\registry\RegistryModule',
                ],
                'students' => [
                    'class' => 'app\subcomponents\students\StudentsModule',
                ],
              ]
          ]
    ]
];
