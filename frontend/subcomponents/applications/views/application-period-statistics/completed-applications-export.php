<?php
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
            'filename'=> $filename,
            'properties' =>[
                'creator' => $generating_officer,
                'title'   => $title,
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'username',
                'title',
                'firstname',
                'middlename',
                'lastname',
                'email',
                'programmes',
                'institutions',
                'total_csec_qualifications',
                'csec_ones',
                'csec_twos',
                'csec_threes',
                'application_duration (mins)'
            ],
        ]);

