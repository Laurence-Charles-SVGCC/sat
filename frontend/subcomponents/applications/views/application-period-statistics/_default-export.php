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
                'firstname',
                'lastname',
                'email',
                'programme',
                'issuedby',
                'issuedate',
                'revokedby',
                'ispublished',
            ],
        ]);

