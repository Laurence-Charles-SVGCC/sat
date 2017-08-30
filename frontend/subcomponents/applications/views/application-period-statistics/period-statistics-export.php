<?php
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
            'filename'=> $filename,
            'properties' =>[
                'creator' => $generating_officer,
                'title'   => $title,
            ],
            'columns' => [
                [
                    'attribute' => 'title',
                    'format' => 'text',
                    'label' => 'Year'
                ],
                [
                    'attribute' => 'applicantintent_name',
                    'format' => 'text',
                    'label' => 'Type'
                ],
                [
                    'attribute' => 'total_number_of_applications_started',
                    'format' => 'text',
                    'label' => ' Commenced'
                ],
                [
                    'attribute' => 'total_number_of_applications_completed',
                    'format' => 'text',
                    'label' => 'Completed'
                ],
                [
                    'attribute' => 'total_number_of_applications_incomplete',
                    'format' => 'text',
                    'label' => 'Incomplete'
                ],
                [
                    'attribute' => 'total_number_of_applications_removed',
                    'format' => 'text',
                    'label' => 'Removed'
                ],
                [
                    'attribute' => 'total_number_of_applications_verified',
                    'format' => 'text',
                    'label' => 'Verified'
                ],
                [
                    'attribute' => 'total_number_of_applications_unverified',
                    'format' => 'text',
                    'label' =>'Verification Pending'
                ],
            ],
        ]);

