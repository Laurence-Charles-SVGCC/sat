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
                'lastname',
                'email',
            ],
        ]);

