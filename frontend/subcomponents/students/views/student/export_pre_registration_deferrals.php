<?php
    
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            //'extension'=>'xlsx',
            'filename'=> $filename,
            'properties' =>[
                //'creator' =>'',
                //'title'   => '',
                //'subject'     => '',
                //'category'    => '',
                //'keywords'    => '',
                //'manager'     => '',
                //'description'=>'BSOURCECODE',
                //'company' =>'BSOURCE',
            ],
            'columns' => [
                [
                    'attribute' => 'username',
                    'format' => 'text',
                    'label' => 'StudentID'
                ],
                [
                    'attribute' => 'firstname',
                    'format' => 'text',
                    'label' => 'First Name'
                ],
                [
                    'attribute' => 'lastname',
                    'format' => 'text',
                    'label' => 'Last Name'
                ], 
                [
                    'attribute' => 'details',
                    'format' => 'text',
                    'label' => 'Details'
                ], 
                [
                    'attribute' => 'deferraldate',
                    'format' => 'text',
                    'label' => 'Date Deferred'
                ],
                [
                    'attribute' => 'deferredby',
                    'format' => 'text',
                    'label' => 'Deferral Officer'
                ], 
                [
                    'attribute' => 'dateresumed',
                    'format' => 'text',
                    'label' => 'Date Enrolled'
                ], 
                [
                    'attribute' => 'resumedby',
                    'format' => 'text',
                    'label' => 'Enrollment Officer'
                ], 
            ],
        ]);