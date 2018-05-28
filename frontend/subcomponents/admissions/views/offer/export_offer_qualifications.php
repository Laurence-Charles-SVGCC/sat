<?php
    
    \app\components\ExcelGrid::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
//            'extension'=>'xlsx',
//            'extension'=>'csv',
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
//                ['class' => 'yii\grid\SerialColumn'],
                'fullname',
                'address',
                'phone',
                'programme',
            ],
        ]);

