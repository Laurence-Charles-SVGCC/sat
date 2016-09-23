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
                    'attribute' => 'previous_year_programme',
                    'format' => 'text',
                    'label' => 'Previous Programme'
                ],
                [
                    'attribute' => 'current_year_programme',
                    'format' => 'text',
                    'label' => 'Current Programme'
                ], 
                [
                    'attribute' => 'deferral_officer_name',
                    'format' => 'text',
                    'label' => 'Transfer Officer'
                ], 
                [
                    'attribute' => 'date',
                    'format' => 'text',
                    'label' => 'Date'
                ],
            ],
        ]);