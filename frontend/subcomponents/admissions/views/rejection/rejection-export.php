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
                'programme',
                'email',
                'issuedby',
                'issuedate',
                'revokedby',
                'ispublished',
            ],
        ]);

