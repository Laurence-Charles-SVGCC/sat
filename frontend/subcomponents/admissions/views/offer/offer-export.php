<?php
\app\components\ExcelGrid::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'extension'=>'xlsx',
        'filename'=>'Offers',
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
            'offerid',
            'applicationid',
            'firstname',
            'lastname',
            'programme',
            'issuedby',
            'issuedate',
            'revokedby',
            'ispublished',
        ],
    ]);

