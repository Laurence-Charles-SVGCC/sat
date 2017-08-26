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
                'qualification_name',
                'qualification_grade',
                'qualification_exam_centre',
                'qualification_candidate_number'
            ],
        ]);

