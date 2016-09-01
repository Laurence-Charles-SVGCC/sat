<?php
    
    
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
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
                ['class' => 'yii\grid\SerialColumn'],
                'username',
                'firstname',
                'middlename',
                'lastname',
                'programme',
                [
                    'attribute' => 'subjects_no',
                    'format' => 'text',
                    'label' => 'No. of Subjects'
                ],
                [
                    'attribute' => 'ones_no',
                    'format' => 'text',
                    'label' => 'No. of Ones'
                ],
                [
                    'attribute' => 'twos_no',
                    'format' => 'text',
                    'label' => 'No. of Twos'
                ],
                [
                    'attribute' => 'threes_no',
                    'format' => 'text',
                    'label' => 'No. of Threes'
                ],
            ],
        ]);
