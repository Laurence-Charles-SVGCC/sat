<?php
    
    
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            //'extension'=>'xlsx',
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
                'lastname',
                'email',
                'programme',
                'appointment',
                'issuedby',
                'issuedate',
                'revokedby',
                'ispublished',
            ],
        ]);

