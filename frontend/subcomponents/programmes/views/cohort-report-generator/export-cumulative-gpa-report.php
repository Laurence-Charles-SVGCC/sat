<?php

    \app\components\ExcelGrid::widget([
            'dataProvider' => $data_provider,
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
                 'attribute' => 'studentid',
                 'format' => 'text',
                 'label' => 'Student ID'
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
                   'attribute' => 'gender',
                   'format' => 'text',
                   'label' => 'Gender'
               ],
               [
                   'attribute' => 'institution_email',
                   'format' => 'text',
                   'label' => 'institution_email'
               ],
               [
                   'attribute' => 'personal_email',
                   'format' => 'text',
                   'label' => 'personal_email'
               ],
               [
                   'attribute' => 'phone',
                   'format' => 'text',
                   'label' => 'phone'
               ],
               [
                 'attribute' => 'final',
                 'format' => 'text',
                 'label' => 'Cumulative GPA'
               ],
               [
                  'attribute' => 'division',
                  'format' => 'text',
                  'label' => 'Division'
                ],
                [
                   'attribute' => 'cohort',
                   'format' => 'text',
                   'label' => 'Cohort'
                 ],
                [
                  'attribute' => 'programme',
                  'format' => 'text',
                  'label' => 'Programme'
                ],
            ],
        ]);
