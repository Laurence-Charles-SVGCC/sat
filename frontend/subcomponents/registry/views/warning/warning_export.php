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
                    'attribute' => 'fails',
                    'format' => 'text',
                    'label' => 'Fails'
                ],  
                [
                    'attribute' => 'total_courses',
                    'format' => 'text',
                    'label' => 'Total Courses'
                ],   
                [
                    'attribute' => 'percentage_failed',
                    'format' => 'text',
                    'label' => 'Failure Rate (%)'
                ],  
                [
                    'attribute' => 'student_status',
                    'format' => 'text',
                    'label' => 'Status'
                ],
                [
                    'attribute' => 'proposed_status',
                    'format' => 'text',
                    'label' => 'Proposed Status'
                ],
                [
                    'attribute' => 'email',
                    'format' => 'text',
                    'label' => 'Email'
                ],
            ],
        ]);
