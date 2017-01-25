<?php
    
    
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
            'extension'=>'csv',
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
                    'label' => 'Student No.'
                ],
                [
                    'attribute' => 'title',
                    'format' => 'text',
                    'label' => 'Title'
                ],
                [
                    'attribute' => 'first_name',
                    'format' => 'text',
                    'label' => 'First Name'
                ],
                [
                    'attribute' => 'middle_name',
                    'format' => 'text',
                    'label' => 'Middle Name'
                ],
                [
                    'attribute' => 'last_name',
                    'format' => 'text',
                    'label' => 'Last Name'
                ],
                [
                    'attribute' => 'programme',
                    'format' => 'text',
                    'label' => 'Programme'
                ],
                [
                    'attribute' => 'current_level',
                    'format' => 'text',
                    'label' => 'Level'
                ],
                [
                    'attribute' => 'student_status',
                    'format' => 'text',
                    'label' => 'Status'
                ],
                [
                    'attribute' => 'email',
                    'format' => 'text',
                    'label' => 'Email'
                ],
            ],
        ]);
