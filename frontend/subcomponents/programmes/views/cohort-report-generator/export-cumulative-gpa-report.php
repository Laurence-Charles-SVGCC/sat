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
